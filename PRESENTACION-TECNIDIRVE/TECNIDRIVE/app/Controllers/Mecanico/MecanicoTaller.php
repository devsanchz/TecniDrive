<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;
use App\Models\TallerModel;
use App\Models\TallerServicioModel;
use App\Models\TallerEspecialidadModel;
use CodeIgniter\HTTP\RedirectResponse;

class MecanicoTaller extends BaseController
{
    // =========================================================================
    // GET mecanico/taller
    // =========================================================================

    public function taller(): string|RedirectResponse
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $idMecanico = session()->get('usuario_id');
        $taller     = (new TallerModel())->buscarPorMecanico($idMecanico);

        $datos = [
            'titulo_pagina'  => 'Mi Taller',
            'taller'         => $taller,
            'servicios'      => [],
            'especialidades' => [],
            'exito'          => session()->getFlashdata('exito') ?? null,
            'error'          => session()->getFlashdata('error') ?? null,
        ];

        if ($taller) {
            $datos['servicios']      = (new TallerServicioModel())->obtenerPorTaller($taller['id_taller']);
            $datos['especialidades'] = (new TallerEspecialidadModel())->obtenerPorTaller($taller['id_taller']);
        }

        return view('Mecanico/mecanico_taller', $datos);
    }

    // =========================================================================
    // POST mecanico/taller/registrar
    // =========================================================================

    public function registrar(): RedirectResponse
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $idMecanico = session()->get('usuario_id');

        if ((new TallerModel())->buscarPorMecanico($idMecanico)) {
            session()->setFlashdata('error', 'Ya tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/taller'));
        }

        // --- Validación ------------------------------------------------------
        $reglas = [
            'nombre'       => 'required|max_length[80]',
            'especialidad' => 'required|max_length[70]',
            'descripcion'  => 'required|max_length[150]',
            'ubicacion'    => 'required|max_length[80]',
        ];

        $mensajes = [
            'nombre'       => ['required' => 'El nombre del taller es obligatorio.'],
            'especialidad' => ['required' => 'La especialidad es obligatoria.'],
            'descripcion'  => ['required' => 'La descripción es obligatoria.'],
            'ubicacion'    => ['required' => 'La ubicación es obligatoria.'],
        ];

        if (! $this->validate($reglas, $mensajes)) {
            session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
            return redirect()->to(site_url('mecanico/taller'));
        }

        // --- Foto (opcional) -------------------------------------------------
        $nombreFoto = null;
        $foto       = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            if (! in_array($foto->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                session()->setFlashdata('error', 'Solo se permiten imágenes JPG, PNG o WEBP.');
                return redirect()->to(site_url('mecanico/taller'));
            }
            if ($foto->getSize() > 2_000_000) {
                session()->setFlashdata('error', 'La imagen no puede superar 2MB.');
                return redirect()->to(site_url('mecanico/taller'));
            }
            $nombreFoto = 'taller_' . $idMecanico . '_' . time() . '.' . $foto->getExtension();
            $foto->move(FCPATH . 'uploads/talleres', $nombreFoto);
        }

        // --- Horario como texto ----------------------------------------------
        $dias   = $this->request->getPost('dias')  ?? [];
        $horas  = $this->request->getPost('horas') ?? [];
        $partes = [];

        foreach ($dias as $i => $dia) {
            if (trim($dia) === '') continue;
            $partes[] = trim($dia) . ': ' . trim($horas[$i] ?? '');
        }

        $horarioTexto = substr(implode(' | ', $partes), 0, 150);

        // --- Transacción manual ----------------------------------------------
        $db = \Config\Database::connect();
        $db->query('START TRANSACTION');

        try {
            // 1. Insertar taller
            $db->table('taller')->insert([
                'foto_taller'           => $nombreFoto,
                'nombre_taller'         => trim($this->request->getPost('nombre')),
                'descripcion_taller'    => trim($this->request->getPost('descripcion')),
                'direccion_taller'      => trim($this->request->getPost('ubicacion')),
                'horario_taller'        => $horarioTexto,
                'estado_taller'         => 1,
                'mecanicos_id_mecanico' => $idMecanico,
            ]);

            $idTaller = $db->query('SELECT LAST_INSERT_ID() as id')->getRow()->id;

            if (! $idTaller) {
                throw new \Exception('No se pudo obtener el ID del taller insertado.');
            }

            log_message('info', 'MecanicoTaller — id_taller generado: ' . $idTaller);

            // 2. Especialidad
            $nombreEspecialidad = trim($this->request->getPost('especialidad'));

            $filaEsp = $db->table('especialidades')
                ->where('nombre_especialidad', $nombreEspecialidad)
                ->get()->getRowArray();

            if ($filaEsp) {
                $idEspecialidad = $filaEsp['id_especialidad'];
            } else {
                $db->table('especialidades')->insert([
                    'nombre_especialidad' => $nombreEspecialidad,
                ]);
                $idEspecialidad = $db->query('SELECT LAST_INSERT_ID() as id')->getRow()->id;
            }

            $db->table('taller_especialidad')->insert([
                'taller_id_taller'               => $idTaller,
                'especialidades_id_especialidad' => $idEspecialidad,
            ]);

            // 3. Servicios
            $servicios = $this->request->getPost('servicio') ?? [];
            $precios   = $this->request->getPost('precio')   ?? [];

            foreach ($servicios as $i => $nombreServicio) {
                $nombreServicio = trim($nombreServicio);
                if ($nombreServicio === '') continue;

                $precioDecimal = $this->parsearPrecio($precios[$i] ?? '0');

                $filaServ = $db->table('servicios')
                    ->where('nombre_servicio', $nombreServicio)
                    ->get()->getRowArray();

                if ($filaServ) {
                    $idServicio = $filaServ['id_servicio'];
                } else {
                    $db->table('servicios')->insert([
                        'nombre_servicio' => $nombreServicio,
                    ]);
                    $idServicio = $db->query('SELECT LAST_INSERT_ID() as id')->getRow()->id;
                }

                $db->table('taller_has_servicios')->insert([
                    'taller_id_taller'      => $idTaller,
                    'servicios_id_servicio' => $idServicio,
                    'precio_servicio'       => $precioDecimal,
                ]);
            }

            $db->query('COMMIT');

        } catch (\Exception $e) {
            $db->query('ROLLBACK');

            if ($nombreFoto && file_exists(FCPATH . 'uploads/talleres/' . $nombreFoto)) {
                unlink(FCPATH . 'uploads/talleres/' . $nombreFoto);
            }

            log_message('error', 'MecanicoTaller::registrar — ' . $e->getMessage());
            session()->setFlashdata('error', 'Error al registrar el taller: ' . $e->getMessage());
            return redirect()->to(site_url('mecanico/taller'));
        }

        session()->setFlashdata('exito', '¡Taller registrado correctamente!');
        return redirect()->to(site_url('mecanico/taller'));
    }

    // =========================================================================
    // POST mecanico/taller/actualizar
    // Actualiza nombre, descripción, ubicación, horario, especialidad,
    // foto (opcional) y reconstruye completamente los servicios del taller.
    // =========================================================================

    public function actualizar(): RedirectResponse
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $idMecanico = session()->get('usuario_id');
        $taller     = (new TallerModel())->buscarPorMecanico($idMecanico);

        if (! $taller) {
            session()->setFlashdata('error', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/taller'));
        }

        $idTaller = $taller['id_taller'];

        // --- Validación ------------------------------------------------------
        $reglas = [
            'nombre'       => 'required|max_length[80]',
            'especialidad' => 'required|max_length[70]',
            'descripcion'  => 'required|max_length[150]',
            'ubicacion'    => 'required|max_length[80]',
        ];

        $mensajes = [
            'nombre'       => ['required' => 'El nombre del taller es obligatorio.'],
            'especialidad' => ['required' => 'La especialidad es obligatoria.'],
            'descripcion'  => ['required' => 'La descripción es obligatoria.'],
            'ubicacion'    => ['required' => 'La ubicación es obligatoria.'],
        ];

        if (! $this->validate($reglas, $mensajes)) {
            session()->setFlashdata('error', implode(' ', $this->validator->getErrors()));
            return redirect()->to(site_url('mecanico/taller'));
        }

        // --- Foto (opcional: solo se reemplaza si el usuario sube una nueva) -
        $nombreFoto    = $taller['foto_taller']; // conservar la existente por defecto
        $fotoAnterior  = $taller['foto_taller'];
        $foto          = $this->request->getFile('foto');

        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            if (! in_array($foto->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                session()->setFlashdata('error', 'Solo se permiten imágenes JPG, PNG o WEBP.');
                return redirect()->to(site_url('mecanico/taller'));
            }
            if ($foto->getSize() > 2_000_000) {
                session()->setFlashdata('error', 'La imagen no puede superar 2MB.');
                return redirect()->to(site_url('mecanico/taller'));
            }
            $nombreFoto = 'taller_' . $idMecanico . '_' . time() . '.' . $foto->getExtension();
            $foto->move(FCPATH . 'uploads/talleres', $nombreFoto);
        }

        // --- Horario como texto ----------------------------------------------
        $dias   = $this->request->getPost('dias')  ?? [];
        $horas  = $this->request->getPost('horas') ?? [];
        $partes = [];

        foreach ($dias as $i => $dia) {
            $dia = trim($dia);
            if ($dia === '') continue;
            $partes[] = $dia . ': ' . trim($horas[$i] ?? '');
        }

        $horarioTexto = substr(implode(' | ', $partes), 0, 150);

        // --- Transacción manual ----------------------------------------------
        $db = \Config\Database::connect();
        $db->query('START TRANSACTION');

        try {
            // 1. Actualizar datos principales del taller ──────────────────────
            $db->table('taller')
                ->where('id_taller', $idTaller)
                ->update([
                    'nombre_taller'      => trim($this->request->getPost('nombre')),
                    'descripcion_taller' => trim($this->request->getPost('descripcion')),
                    'direccion_taller'   => trim($this->request->getPost('ubicacion')),
                    'horario_taller'     => $horarioTexto,
                    'foto_taller'        => $nombreFoto,
                ]);

            // 2. Actualizar especialidad ──────────────────────────────────────
            // Estrategia: borrar el vínculo actual e insertar el nuevo.
            // Si la especialidad no existe en el catálogo, se crea.
            $nombreEspecialidad = trim($this->request->getPost('especialidad'));

            $filaEsp = $db->table('especialidades')
                ->where('nombre_especialidad', $nombreEspecialidad)
                ->get()->getRowArray();

            if ($filaEsp) {
                $idEspecialidad = $filaEsp['id_especialidad'];
            } else {
                $db->table('especialidades')->insert([
                    'nombre_especialidad' => $nombreEspecialidad,
                ]);
                $idEspecialidad = $db->query('SELECT LAST_INSERT_ID() as id')->getRow()->id;
            }

            // Borrar vínculo anterior y crear el nuevo
            $db->table('taller_especialidad')
                ->where('taller_id_taller', $idTaller)
                ->delete();

            $db->table('taller_especialidad')->insert([
                'taller_id_taller'               => $idTaller,
                'especialidades_id_especialidad' => $idEspecialidad,
            ]);

            // 3. Reconstruir servicios ────────────────────────────────────────
            // Estrategia: borrar todos los vínculos actuales del taller
            // e insertar los nuevos. Así se manejan adiciones, ediciones
            // y eliminaciones en una sola pasada sin comparar filas.
            $db->table('taller_has_servicios')
                ->where('taller_id_taller', $idTaller)
                ->delete();

            $servicios = $this->request->getPost('servicio') ?? [];
            $precios   = $this->request->getPost('precio')   ?? [];

            foreach ($servicios as $i => $nombreServicio) {
                $nombreServicio = trim($nombreServicio);
                if ($nombreServicio === '') continue;

                $precioDecimal = $this->parsearPrecio($precios[$i] ?? '0');

                // Reutilizar servicio del catálogo o crear uno nuevo
                $filaServ = $db->table('servicios')
                    ->where('nombre_servicio', $nombreServicio)
                    ->get()->getRowArray();

                if ($filaServ) {
                    $idServicio = $filaServ['id_servicio'];
                } else {
                    $db->table('servicios')->insert([
                        'nombre_servicio' => $nombreServicio,
                    ]);
                    $idServicio = $db->query('SELECT LAST_INSERT_ID() as id')->getRow()->id;
                }

                $db->table('taller_has_servicios')->insert([
                    'taller_id_taller'      => $idTaller,
                    'servicios_id_servicio' => $idServicio,
                    'precio_servicio'       => $precioDecimal,
                ]);
            }

            $db->query('COMMIT');

            // Eliminar foto antigua del disco solo si se subió una nueva
            if ($foto && $foto->isValid() && $fotoAnterior
                && $fotoAnterior !== $nombreFoto
                && file_exists(FCPATH . 'uploads/talleres/' . $fotoAnterior)
            ) {
                unlink(FCPATH . 'uploads/talleres/' . $fotoAnterior);
            }

        } catch (\Exception $e) {
            $db->query('ROLLBACK');

            // Si se subió una foto nueva pero falló la transacción, borrarla
            if ($foto && $foto->isValid() && $nombreFoto !== $fotoAnterior
                && file_exists(FCPATH . 'uploads/talleres/' . $nombreFoto)
            ) {
                unlink(FCPATH . 'uploads/talleres/' . $nombreFoto);
            }

            log_message('error', 'MecanicoTaller::actualizar — ' . $e->getMessage());
            session()->setFlashdata('error', 'Error al actualizar el taller: ' . $e->getMessage());
            return redirect()->to(site_url('mecanico/taller'));
        }

        session()->setFlashdata('exito', '¡Taller actualizado correctamente!');
        return redirect()->to(site_url('mecanico/taller'));
    }

    // =========================================================================
    // POST mecanico/taller/desactivar
    // =========================================================================

    public function desactivar(): RedirectResponse
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $idMecanico = session()->get('usuario_id');
        $taller     = (new TallerModel())->buscarPorMecanico($idMecanico);

        if (! $taller) {
            session()->setFlashdata('error', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/taller'));
        }

        $motivo = trim($this->request->getPost('motivo') ?? '');

        if ($motivo === '') {
            session()->setFlashdata('error', 'Debes indicar el motivo de desactivación.');
            return redirect()->to(site_url('mecanico/taller'));
        }

        $db = \Config\Database::connect();
        $db->table('taller')
            ->where('id_taller', $taller['id_taller'])
            ->update([
                'estado_taller' => 0,
                'motivo_estado' => substr($motivo, 0, 100),
            ]);

        session()->setFlashdata('exito', 'Taller desactivado correctamente.');
        return redirect()->to(site_url('mecanico/taller'));
    }

    // =========================================================================
    // POST mecanico/taller/activar
    // =========================================================================

    public function activar(): RedirectResponse
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $idMecanico = session()->get('usuario_id');
        $taller     = (new TallerModel())->buscarPorMecanico($idMecanico);

        if (! $taller) {
            session()->setFlashdata('error', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/taller'));
        }

        $db = \Config\Database::connect();
        $db->table('taller')
            ->where('id_taller', $taller['id_taller'])
            ->update([
                'estado_taller' => 1,
                'motivo_estado' => null,
            ]);

        session()->setFlashdata('exito', '¡Taller activado correctamente!');
        return redirect()->to(site_url('mecanico/taller'));
    }

    // =========================================================================
    // HELPER PRIVADO — parsearPrecio()
    // Convierte un precio en formato colombiano o anglosajón a float.
    // Ejemplos: "$20.000" → 20000 | "$15.500,50" → 15500.50 | "20000" → 20000
    // =========================================================================

    private function parsearPrecio(string $raw): float
    {
        // Eliminar símbolo de moneda y espacios
        $raw = str_replace(['$', ' '], '', $raw);

        // Conservar solo dígitos, coma y punto
        $raw = preg_replace('/[^0-9,.]/', '', $raw);

        if (str_contains($raw, ',')) {
            // Formato colombiano con decimal: "$15.500,50" → 15500.50
            $raw = str_replace('.', '', $raw);   // quitar puntos de miles
            $raw = str_replace(',', '.', $raw);   // coma decimal → punto
        } else {
            // Sin coma: "$20.000" es miles, NO decimal
            // Contar cuántos dígitos hay después del punto
            $partes = explode('.', $raw);
            $ultimaParte = end($partes);
            // Si hay exactamente 3 dígitos tras el punto → es separador de miles
            // Si hay 1 o 2 dígitos → es decimal (ej: "15.50")
            if (strlen($ultimaParte) === 3) {
                $raw = str_replace('.', '', $raw); // quitar puntos de miles
            }
            // Si tiene 1 o 2 decimales lo dejamos como está
        }

        return (float) $raw;
    }
}