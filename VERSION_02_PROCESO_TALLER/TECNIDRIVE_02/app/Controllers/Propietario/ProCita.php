<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;
use App\Models\CitaTallerModel;
use App\Models\PersonaModel;
use App\Models\VehiculoModel;
use CodeIgniter\HTTP\RedirectResponse;

class ProCita extends BaseController
{
    // ══════════════════════════════════════════════════════════════════════
    // GET — Mostrar citas del propietario
    //
    // Sesión y rol (propietario = id_rol 2) ya verificados por AuthFilter
    // ('auth:2' en el grupo 'propietario' de Routes.php). id_propietario
    // siempre está presente en sesión cuando usuario_rol = 2, porque
    // Ingresar::establecerSesion() lo asigna en ese mismo caso — por eso
    // no se vuelve a comprobar aquí.
    // ══════════════════════════════════════════════════════════════════════
    public function cita(): string
    {
        $idPropietario = (int) session()->get('id_propietario');
        $citaModel      = new CitaTallerModel();

        // ── Traer citas con todos los datos relacionados ──────────────────
        $citas = $citaModel->obtenerCompletasPorPropietario($idPropietario);

        // ── Agregar servicios y técnicos a cada cita ──────────────────────
        foreach ($citas as &$cita) {
            $cita['servicios'] = $citaModel->obtenerServicios($cita['id_cita']);

            // Solo si ya existe ficha de gestión (cita en_atencion, en_cierre o finalizada)
            $cita['tecnicos'] = !empty($cita['id_seguimiento'])
                ? $citaModel->obtenerTecnicosPorSeguimiento((int) $cita['id_seguimiento'])
                : [];
        }
        unset($cita);

        // ── Datos del propietario para contacto ───────────────────────────
        $persona = (new PersonaModel())->find($idPropietario);

        return view('Propietario/pro_cita', [
            'titulo_pagina' => 'MIS CITAS',
            'citas'         => $citas,
            'persona'       => $persona,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Cancelar una cita (solo si está pendiente)
    // ══════════════════════════════════════════════════════════════════════
    public function cancelar(): RedirectResponse
    {
        $idPropietario = (int) session()->get('id_propietario');
        $idCita        = (int) $this->request->getPost('id_cita');
        $motivo        = trim($this->request->getPost('motivo') ?? '');

        // ── Validar motivo ANTES de tocar la BD ───────────────────────────
        if (empty($motivo)) {
            session()->setFlashdata('error_cita', 'Debes escribir un motivo para cancelar.');
            return redirect()->to(site_url('propietario/cita'));
        }

        $citaModel = new CitaTallerModel();
        $cita      = $citaModel->find($idCita);

        // ── Verificar que existe y está pendiente ─────────────────────────
        if (!$cita || $cita['estado_cita'] !== 'pendiente') {
            session()->setFlashdata('error_cita', 'Solo puedes cancelar citas en estado pendiente.');
            return redirect()->to(site_url('propietario/cita'));
        }

        // ── Verificar que el vehículo pertenece al propietario ────────────
        // Autorización a nivel de recurso: AuthFilter no puede saber a quién
        // pertenece esta cita, así que esta comprobación se mantiene siempre.
        $vehiculo = (new VehiculoModel())
            ->where('placa', $cita['vehiculo_placa'])
            ->where('propietarios_id_propietario', $idPropietario)
            ->first();

        if (!$vehiculo) {
            session()->setFlashdata('error_cita', 'No tienes permiso para cancelar esta cita.');
            return redirect()->to(site_url('propietario/cita'));
        }

        // ── Todo válido: actualizar en BD ─────────────────────────────────
        $citaModel->update($idCita, [
            'estado_cita'        => 'cancelada_propietario',
            'cancelado_por'      => 'propietario',
            'motivo_cancelacion' => $motivo,
        ]);

        session()->setFlashdata('exito_cita', 'Cita cancelada correctamente.');
        return redirect()->to(site_url('propietario/cita'));
    }
}