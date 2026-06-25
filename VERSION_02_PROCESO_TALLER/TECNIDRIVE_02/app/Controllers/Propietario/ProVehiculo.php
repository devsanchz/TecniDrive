<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;
use App\Models\PropietarioModel;
use App\Models\CategoriaLicenciaModel;
use App\Models\CategoriaHasPropietarioModel;
use App\Models\VehiculoModel;
use App\Models\MarcaModel;
use App\Models\ModeloModel;
use App\Models\PapelVehiculoModel;
use CodeIgniter\HTTP\RedirectResponse;

class ProVehiculo extends BaseController
{
    // ══════════════════════════════════════════════════════════════════════
    // GET — Mostrar vista principal
    //
    // Sesión y rol (propietario = id_rol 2) ya verificados por AuthFilter
    // ('auth:2' en el grupo 'propietario' de Routes.php). Por eso ya no
    // se valida si $idPropietario existe: si este método se ejecuta, ya
    // sabemos que hay un propietario logueado.
    // ══════════════════════════════════════════════════════════════════════
    public function vehiculo(): string
    {
        $idPropietario = session()->get('id_propietario');

        $propietarioModel = new PropietarioModel();
        $tieneLicencia    = $propietarioModel->tieneLicencia($idPropietario);

        $datos = [
            'titulo_pagina'  => 'MIS VEHÍCULOS',
            'tiene_licencia' => $tieneLicencia,
        ];

        // ── Si ya tiene licencia cargar sus datos ─────────────────────────
        if ($tieneLicencia) {
            $chpModel    = new CategoriaHasPropietarioModel();
            $propietario = $propietarioModel->find($idPropietario);

            $datos['numero_licencia'] = $propietario['numero_licencia'];
            $datos['mis_categorias']  = $chpModel->obtenerPorPropietario($idPropietario);

            // ── Vehículos del propietario ──────────────────────────────────
            $vehiculoModel          = new VehiculoModel();
            $datos['mis_vehiculos'] = $vehiculoModel->obtenerPorPropietario($idPropietario);

            // ── Documentos de cada vehículo (agrupados por placa) ───────────
            $placas                  = array_column($datos['mis_vehiculos'], 'placa');
            $papelModel              = new PapelVehiculoModel();
            $datos['mis_documentos'] = empty($placas) ? [] : $papelModel->obtenerPorPlacas($placas);
        }

        return view('Propietario/pro_vehiculo', $datos);
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Guardar licencia y categorías
    // ══════════════════════════════════════════════════════════════════════
    public function guardarLicencia(): RedirectResponse
    {
        $idPropietario = session()->get('id_propietario');

        // ── Validación ────────────────────────────────────────────────────
        $rules = [
            'numero_licencia' => 'required|max_length[11]',
            'categoria.*'     => 'required',
            'fecha.*'         => 'required',
        ];

        $messages = [
            'numero_licencia' => [
                'required' => 'El número de licencia es obligatorio.',
            ],
            'categoria.*' => [
                'required' => 'Selecciona al menos una categoría.',
            ],
            'fecha.*' => [
                'required' => 'La fecha de vigencia es obligatoria.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', implode(' ', array_values($this->validator->getErrors())));
            return redirect()->to(site_url('propietario/vehiculo'));
        }

        $categorias = $this->request->getPost('categoria');
        $fechas     = $this->request->getPost('fecha');

        // ── Guardar número de licencia en propietarios ────────────────────
        $propietarioModel = new PropietarioModel();
        $propietarioModel->update($idPropietario, [
            'numero_licencia' => $this->request->getPost('numero_licencia'),
        ]);

        // ── Guardar cada categoría con su vigencia ────────────────────────
        $chpModel       = new CategoriaHasPropietarioModel();
        $categoriaModel = new CategoriaLicenciaModel();

        foreach ($categorias as $index => $tipoCategoria) {
            $tipoCategoria = strtoupper(trim($tipoCategoria));

            if (empty($tipoCategoria) || empty($fechas[$index])) {
                continue;
            }

            // Buscar si la categoría ya existe en categoria_licencia
            $cat = $categoriaModel
                ->where('tipo_categoria', $tipoCategoria)
                ->first();

            // Si no existe, crearla
            if (!$cat) {
                $categoriaModel->insert(['tipo_categoria' => $tipoCategoria]);
                $idCategoria = $categoriaModel->insertID(); // ← simplificado: insertID() del propio modelo
            } else {
                $idCategoria = $cat['id_categoria'];
            }

            // Evitar duplicado para este propietario
            $existe = $chpModel
                ->where('categoria_licencia_id_categoria', $idCategoria)
                ->where('propietarios_id_propietario', $idPropietario)
                ->countAllResults();

            if ($existe) {
                continue;
            }

            $chpModel->insert([
                'categoria_licencia_id_categoria' => $idCategoria,
                'propietarios_id_propietario'     => $idPropietario,
                'vigencia_lice'                   => $fechas[$index],
                'estado_lice'                     => 1,
            ]);
        }

        return redirect()->to(site_url('propietario/vehiculo'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Guardar nuevo vehículo
    // ══════════════════════════════════════════════════════════════════════
    public function guardarVehiculo(): RedirectResponse
    {
        $idPropietario = session()->get('id_propietario');

        // ── Validación ────────────────────────────────────────────────────
        $rules = [
            'placa'         => 'required|max_length[6]',
            'marca'         => 'required|max_length[25]',
            'modelo'        => 'required|max_length[28]',
            'ano'           => 'required|max_length[4]',
            'tipo_vehi'     => 'required|integer',
            'tipo_servicio' => 'required|integer',
        ];

        $messages = [
            'placa'         => ['required' => 'La placa es obligatoria.'],
            'marca'         => ['required' => 'La marca es obligatoria.'],
            'modelo'        => ['required' => 'El modelo es obligatorio.'],
            'ano'           => ['required' => 'El año es obligatorio.'],
            'tipo_vehi'     => ['required' => 'Selecciona el tipo de vehículo.'],
            'tipo_servicio' => ['required' => 'Selecciona el servicio del vehículo.'],
        ];

        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error_vehiculo', implode(' ', array_values($this->validator->getErrors())));
            return redirect()->to(site_url('propietario/vehiculo'));
        }

        $placa = strtoupper(trim($this->request->getPost('placa')));

        // ── Resolver/crear marca y modelo (sin duplicar) ──────────────────
        $marcaModel  = new MarcaModel();
        $modeloModel = new ModeloModel();

        $idMarca  = $marcaModel->obtenerOCrear($this->request->getPost('marca'));
        $idModelo = $modeloModel->obtenerOCrear($this->request->getPost('modelo'), $idMarca);

        // ── Insertar vehículo ──────────────────────────────────────────────
        $vehiculoModel = new VehiculoModel();
        $vehiculoModel->insert([
            'placa'                               => $placa,
            'model_year'                          => $this->request->getPost('ano'),
            'estado_vehi'                         => 1,
            'propietarios_id_propietario'         => $idPropietario,
            'tipos_vehiculo_id_tipo_vehi'         => (int) $this->request->getPost('tipo_vehi'),
            'modelos_id_modelo'                   => $idModelo,
            'servicio_vehiculo_id_tipo_servicio'  => (int) $this->request->getPost('tipo_servicio'),
        ]);

        session()->setFlashdata('vehiculo_guardado', true);
        return redirect()->to(site_url('propietario/vehiculo'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Activar vehículo
    //
    // El check de "¿hay sesión?" se elimina (lo cubre AuthFilter), pero el
    // check de "¿este vehículo es de ESTE propietario?" se CONSERVA: es
    // autorización a nivel de recurso, no autenticación, y AuthFilter no
    // tiene forma de saber a quién pertenece cada placa.
    // ══════════════════════════════════════════════════════════════════════
    public function activarVehiculo(): RedirectResponse
    {
        $idPropietario = session()->get('id_propietario');
        $placa         = $this->request->getPost('placa');

        $vehiculoModel = new VehiculoModel();

        // Verificar que el vehículo pertenece a este propietario
        $vehiculo = $vehiculoModel
            ->where('placa', $placa)
            ->where('propietarios_id_propietario', $idPropietario)
            ->first();

        if ($vehiculo) {
            $vehiculoModel->activar($placa);
        }

        return redirect()->to(site_url('propietario/vehiculo'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Desactivar vehículo con motivo
    // ══════════════════════════════════════════════════════════════════════
    public function desactivarVehiculo(): RedirectResponse
    {
        $idPropietario = session()->get('id_propietario');
        $placa         = $this->request->getPost('placa');
        $motivo        = trim($this->request->getPost('motivo'));

        if (empty($motivo)) {
            session()->setFlashdata('error_vehiculo', 'Debes indicar un motivo para desactivar el vehículo.');
            return redirect()->to(site_url('propietario/vehiculo'));
        }

        $vehiculoModel = new VehiculoModel();

        // Verificar que el vehículo pertenece a este propietario (autorización, se conserva)
        $vehiculo = $vehiculoModel
            ->where('placa', $placa)
            ->where('propietarios_id_propietario', $idPropietario)
            ->first();

        if ($vehiculo) {
            $vehiculoModel->desactivar($placa, $motivo);
        }

        return redirect()->to(site_url('propietario/vehiculo'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Guardar/actualizar documentos (SOAT y Tecnomecánica)
    // ══════════════════════════════════════════════════════════════════════
    public function guardarDocumentos(): RedirectResponse
    {
        $idPropietario = session()->get('id_propietario');
        $placa         = $this->request->getPost('placa');
        $soat          = $this->request->getPost('soat');
        $tecno         = $this->request->getPost('tecnomecanica');

        $vehiculoModel = new VehiculoModel();

        // Verificar que el vehículo pertenece a este propietario (autorización, se conserva)
        $vehiculo = $vehiculoModel
            ->where('placa', $placa)
            ->where('propietarios_id_propietario', $idPropietario)
            ->first();

        if (!$vehiculo) {
            return redirect()->to(site_url('propietario/vehiculo'));
        }

        $papelModel = new PapelVehiculoModel();

        if (!empty($soat)) {
            $papelModel->guardarDocumento($placa, 1, $soat); // 1 = SOAT
        }

        if (!empty($tecno)) {
            $papelModel->guardarDocumento($placa, 2, $tecno); // 2 = Tecnomecánica
        }

        return redirect()->to(site_url('propietario/vehiculo'));
    }
}