<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;
use App\Models\CitaTallerModel;
use App\Models\TallerModel;
use App\Models\TecnicoModel;
use App\Models\GestionMantenimientoModel;
use App\Models\MantenimientoHasTecnicoModel;
use CodeIgniter\HTTP\RedirectResponse;

class MecanicoControl extends BaseController
{
    // ── Helper privado: obtener id_mecanico de la sesión ───────────────────
    // Sesión y rol (mecánico = id_rol 3) ya verificados por AuthFilter
    // ('auth:3' en el grupo 'mecanico' de Routes.php). Por diseño de
    // Ingresar::establecerSesion(), si usuario_rol = 3 entonces id_mecanico
    // siempre está presente — por eso este helper ya no necesita devolver
    // RedirectResponse ni los métodos públicos necesitan el chequeo
    // instanceof que antes acompañaba cada llamada.
    private function idMecanico(): int
    {
        return (int) session()->get('id_mecanico');
    }

    // ══════════════════════════════════════════════════════════════════════
    // GET — Panel de control (citas en_atencion + lista de técnicos)
    // ══════════════════════════════════════════════════════════════════════
    public function control(): string|RedirectResponse
    {
        $idMecanico = $this->idMecanico();

        // ── Buscar el taller del mecánico (necesario para técnicos) ───────
        $taller = (new TallerModel())->buscarPorMecanico($idMecanico);

        if (!$taller) {
            session()->setFlashdata('error_control', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/taller'));
        }

        $idTaller = (int) $taller['id_taller'];

        $citaModel    = new CitaTallerModel();
        $tecnicoModel = new TecnicoModel();

        // Citas en_atencion con su ficha de gestion_mantenimiento
        $citas = $citaModel->obtenerEnAtencion($idMecanico);

        // Técnicos ya asignados a cada cita (para mostrarlos en la tarjeta de en_cierre)
        foreach ($citas as &$cita) {
            $cita['tecnicos_asignados'] = !empty($cita['id_seguimiento'])
                ? $citaModel->obtenerTecnicosPorSeguimiento((int) $cita['id_seguimiento'])
                : [];
        }
        unset($cita);

        // Técnicos del taller para mostrar en la lista y en los checkboxes
        $tecnicos = $tecnicoModel->obtenerPorTaller($idTaller);

        return view('Mecanico/mecanico_control', [
            'titulo_pagina' => 'CONTROL DE CITAS',
            'citas'         => $citas,
            'tecnicos'      => $tecnicos,
            'exito'         => session()->getFlashdata('exito_control'),
            'error'         => session()->getFlashdata('error_control'),
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Agregar técnico a la lista del taller
    // ══════════════════════════════════════════════════════════════════════
    public function agregarTecnico(): RedirectResponse
    {
        $idMecanico = $this->idMecanico();

        $taller = (new TallerModel())->buscarPorMecanico($idMecanico);
        if (!$taller) {
            session()->setFlashdata('error_control', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/control'));
        }

        $nombre = trim($this->request->getPost('nombre_tecnico') ?? '');

        if ($nombre === '') {
            session()->setFlashdata('error_control', 'El nombre del técnico es obligatorio.');
            return redirect()->to(site_url('mecanico/control'));
        }

        if (mb_strlen($nombre) > 100) {
            session()->setFlashdata('error_control', 'El nombre es demasiado largo.');
            return redirect()->to(site_url('mecanico/control'));
        }

        (new TecnicoModel())->insert([
            'taller_id_taller' => (int) $taller['id_taller'],
            'nombre_tecnico'   => $nombre,
        ]);

        session()->setFlashdata('exito_control', 'Técnico agregado correctamente.');
        return redirect()->to(site_url('mecanico/control'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Eliminar técnico de la lista del taller
    // ══════════════════════════════════════════════════════════════════════
    public function eliminarTecnico(): RedirectResponse
    {
        $idMecanico = $this->idMecanico();

        $taller = (new TallerModel())->buscarPorMecanico($idMecanico);
        if (!$taller) {
            session()->setFlashdata('error_control', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/control'));
        }

        $idTecnico    = (int) $this->request->getPost('id_tecnico');
        $tecnicoModel = new TecnicoModel();

        // ── Seguridad: solo puede borrar técnicos de SU propio taller ─────
        if (!$tecnicoModel->perteneceATaller($idTecnico, (int) $taller['id_taller'])) {
            session()->setFlashdata('error_control', 'Técnico no encontrado.');
            return redirect()->to(site_url('mecanico/control'));
        }

        $tecnicoModel->delete($idTecnico);

        session()->setFlashdata('exito_control', 'Técnico eliminado correctamente.');
        return redirect()->to(site_url('mecanico/control'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Guardar datos de pre-cierre (en_atencion → en_cierre)
    // ══════════════════════════════════════════════════════════════════════
    public function cerrar(): RedirectResponse
    {
        $idMecanico = $this->idMecanico();

        $taller = (new TallerModel())->buscarPorMecanico($idMecanico);
        if (!$taller) {
            session()->setFlashdata('error_control', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/control'));
        }

        $idTaller      = (int) $taller['id_taller'];
        $idCita        = (int) $this->request->getPost('id_cita');
        $idSeguimiento = (int) $this->request->getPost('id_seguimiento');

        $citaModel    = new CitaTallerModel();
        $gestionModel = new GestionMantenimientoModel();

        // ── Verificar que la cita existe, está en_atencion y es del taller ─
        $cita = $citaModel->find($idCita);

        if (!$cita || $cita['estado_cita'] !== 'en_atencion' || (int) $cita['taller_id_taller'] !== $idTaller) {
            session()->setFlashdata('error_control', 'La cita no es válida para cerrar.');
            return redirect()->to(site_url('mecanico/control'));
        }

        // ── Verificar que la ficha de gestión corresponde a esa cita ───────
        $gestion = $gestionModel->find($idSeguimiento);

        if (!$gestion || (int) $gestion['cita_taller_id_cita'] !== $idCita) {
            session()->setFlashdata('error_control', 'Ficha de mantenimiento no válida.');
            return redirect()->to(site_url('mecanico/control'));
        }

        // ── Recoger y limpiar datos del formulario ──────────────────────────
        $observaciones = trim($this->request->getPost('observaciones') ?? '');
        $textoGarantia = trim($this->request->getPost('texto_garantia') ?? '');
        $vigencia      = trim($this->request->getPost('garantia_vigencia') ?? '');
        $precioRaw     = trim($this->request->getPost('precio_total') ?? '');
        $tecnicosPost  = $this->request->getPost('tecnicos') ?? []; // array de id_tecnico (checkboxes)

        // ── Validar precio: solo números, punto y coma ─────────────────────
        $precioLimpio = preg_replace('/[^0-9]/', '', $precioRaw);

        if ($precioLimpio === '') {
            session()->setFlashdata('error_control', 'Debes indicar el precio total.');
            return redirect()->to(site_url('mecanico/control'));
        }

        $precioTotal = (float) $precioLimpio;

        // ── Validar que al menos un técnico fue seleccionado ───────────────
        if (empty($tecnicosPost) || !is_array($tecnicosPost)) {
            session()->setFlashdata('error_control', 'Debes seleccionar al menos un técnico.');
            return redirect()->to(site_url('mecanico/control'));
        }

        // ── Seguridad: todos los técnicos deben pertenecer a este taller ──
        $tecnicoModel = new TecnicoModel();
        foreach ($tecnicosPost as $idTec) {
            if (!$tecnicoModel->perteneceATaller((int) $idTec, $idTaller)) {
                session()->setFlashdata('error_control', 'Técnico no válido.');
                return redirect()->to(site_url('mecanico/control'));
            }
        }

        // ── Garantía es opcional: si no hay texto, no se guarda vigencia ──
        $vigenciaFinal = ($textoGarantia !== '' && $vigencia !== '') ? $vigencia : null;

        // ── Generar código de entrega único (igual criterio que codigo_confirmacion) ──
        // El propietario lo verá en su panel cuando estado_mantenimiento sea 'en_cierre'.
        do {
            $codigoEntrega = strtoupper(substr(bin2hex(random_bytes(5)), 0, 8));
            $yaExiste = $gestionModel->where('codigo_entrega', $codigoEntrega)->countAllResults() > 0;
        } while ($yaExiste);

        // ── Actualizar ficha de gestión: pasa a en_cierre ──────────────────
        $gestionModel->update($idSeguimiento, [
            'observaciones_tecnico' => $observaciones !== '' ? $observaciones : null,
            'precio_total'          => $precioTotal,
            'texto_garantia'        => $textoGarantia !== '' ? $textoGarantia : null,
            'garantia_vigencia'     => $vigenciaFinal,
            'estado_mantenimiento'  => 'en_cierre',
            'codigo_entrega'        => $codigoEntrega,
        ]);

        // ── Guardar técnicos asignados ──────────────────────────────────────
        (new MantenimientoHasTecnicoModel())->asignarTecnicos($idSeguimiento, $tecnicosPost);

        session()->setFlashdata('exito_control', 'Datos de cierre guardados. La cita pasó a estado en cierre.');
        return redirect()->to(site_url('mecanico/control'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Verificar código de entrega (en_cierre → finalizada)
    // ══════════════════════════════════════════════════════════════════════
    public function verificarEntrega(): RedirectResponse
    {
        $idMecanico = $this->idMecanico();

        $taller = (new TallerModel())->buscarPorMecanico($idMecanico);
        if (!$taller) {
            session()->setFlashdata('error_control', 'No tienes un taller registrado.');
            return redirect()->to(site_url('mecanico/control'));
        }

        $idTaller = (int) $taller['id_taller'];
        $codigo   = trim($this->request->getPost('codigo') ?? '');

        if ($codigo === '') {
            session()->setFlashdata('error_control', 'Debes ingresar el código de entrega.');
            return redirect()->to(site_url('mecanico/control'));
        }

        // Mismo criterio de formato que al generarlo en cerrar()
        $codigo = strtoupper($codigo);

        $citaModel    = new CitaTallerModel();
        $gestionModel = new GestionMantenimientoModel();

        // ── Buscar la ficha de gestión por código de entrega ───────────────
        $gestion = $gestionModel->where('codigo_entrega', $codigo)->first();

        if (!$gestion || $gestion['estado_mantenimiento'] !== 'en_cierre') {
            session()->setFlashdata('error_control', 'Código de entrega no válido.');
            return redirect()->to(site_url('mecanico/control'));
        }

        // ── Seguridad: la cita asociada debe pertenecer a este taller ─────
        $cita = $citaModel->find((int) $gestion['cita_taller_id_cita']);

        if (!$cita || (int) $cita['taller_id_taller'] !== $idTaller) {
            session()->setFlashdata('error_control', 'Código de entrega no válido.');
            return redirect()->to(site_url('mecanico/control'));
        }

        // ── Todo válido: la ficha pasa a finalizada ────────────────────────
        $gestionModel->update($gestion['id_seguimiento'], [
            'estado_mantenimiento' => 'finalizada',
            'fecha_cierre'         => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('exito_control', 'Código verificado. La cita fue finalizada y el vehículo entregado.');
        return redirect()->to(site_url('mecanico/control'));
    }
}