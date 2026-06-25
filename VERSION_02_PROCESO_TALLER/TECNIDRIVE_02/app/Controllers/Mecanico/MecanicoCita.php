<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;
use App\Models\CitaTallerModel;
use App\Models\TallerModel;
use CodeIgniter\HTTP\RedirectResponse;

class MecanicoCita extends BaseController
{
    // ══════════════════════════════════════════════════════════════════════
    // GET — Mostrar agenda de citas del mecánico
    //
    // Sesión y rol (mecánico = id_rol 3) ya verificados por AuthFilter
    // ('auth:3' en el grupo 'mecanico' de Routes.php). obtenerPorMecanico()
    // ya filtra por $idMecanico en el modelo, así que no hay riesgo de
    // IDOR aquí: solo se listan citas del propio taller.
    // ══════════════════════════════════════════════════════════════════════
    public function cita(): string
    {
        $idMecanico = (int) session()->get('id_mecanico');
        $citaModel  = new CitaTallerModel();

        // ── Traer citas con datos completos ───────────────────────────────
        $citas = $citaModel->obtenerPorMecanico($idMecanico);

        // ── Agregar servicios a cada cita ─────────────────────────────────
        foreach ($citas as &$cita) {
            $cita['servicios'] = $citaModel->obtenerServicios($cita['id_cita']);
        }
        unset($cita);

        return view('Mecanico/mecanico_citas', [
            'titulo_pagina' => 'AGENDA DE CITAS',
            'citas'         => $citas,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Confirmar cita (pendiente → confirmada + código confirmación)
    //
    // ⚠️ CORREGIDO: se agrega verificación de propiedad. Antes se buscaba
    // la cita solo por $idCita (find()), sin confirmar que pertenece a un
    // taller de ESTE mecánico. Esto permitía a cualquier mecánico logueado
    // confirmar/cancelar citas de OTROS talleres con solo cambiar el
    // id_cita enviado por el formulario (vulnerabilidad IDOR).
    // ══════════════════════════════════════════════════════════════════════
    public function confirmar(): RedirectResponse
    {
        $idMecanico = (int) session()->get('id_mecanico');
        $idCita     = (int) $this->request->getPost('id_cita');

        $citaModel = new CitaTallerModel();

        // Verificar que la cita pertenece a un taller de este mecánico
        $cita = $citaModel
            ->join('taller', 'taller.id_taller = cita_taller.taller_id_taller')
            ->where('cita_taller.id_cita', $idCita)
            ->where('taller.mecanicos_id_mecanico', $idMecanico)
            ->first();

        if (!$cita || $cita['estado_cita'] !== 'pendiente') {
            session()->setFlashdata('error_cita', 'La cita no puede confirmarse.');
            return redirect()->to(site_url('mecanico/cita'));
        }

        // ── Generar código de confirmación único ──────────────────────────
        $codigo = strtoupper(substr(bin2hex(random_bytes(5)), 0, 8));

        $citaModel->update($idCita, [
            'estado_cita'         => 'confirmada',
            'codigo_confirmacion' => $codigo,
        ]);

        session()->setFlashdata('exito_cita', 'Cita confirmada. Código: ' . $codigo);
        return redirect()->to(site_url('mecanico/cita'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Cancelar cita por el mecánico
    //
    // ⚠️ CORREGIDO: misma verificación de propiedad que en confirmar().
    // ══════════════════════════════════════════════════════════════════════
    public function cancelar(): RedirectResponse
    {
        $idMecanico = (int) session()->get('id_mecanico');
        $idCita     = (int) $this->request->getPost('id_cita');
        $motivo     = trim($this->request->getPost('motivo') ?? '');

        if (empty($motivo)) {
            session()->setFlashdata('error_cita', 'Debes indicar un motivo.');
            return redirect()->to(site_url('mecanico/cita'));
        }

        $citaModel = new CitaTallerModel();

        // Verificar que la cita pertenece a un taller de este mecánico
        $cita = $citaModel
            ->join('taller', 'taller.id_taller = cita_taller.taller_id_taller')
            ->where('cita_taller.id_cita', $idCita)
            ->where('taller.mecanicos_id_mecanico', $idMecanico)
            ->first();

        if (!$cita || !in_array($cita['estado_cita'], ['pendiente', 'confirmada'])) {
            session()->setFlashdata('error_cita', 'La cita no puede cancelarse.');
            return redirect()->to(site_url('mecanico/cita'));
        }

        $citaModel->update($idCita, [
            'estado_cita'        => 'cancelada_mecanico',
            'cancelado_por'      => 'mecanico',
            'motivo_cancelacion' => $motivo,
        ]);

        session()->setFlashdata('exito_cita', 'Cita cancelada correctamente.');
        return redirect()->to(site_url('mecanico/cita'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Verificar código de confirmación (confirmada → en_atencion)
    //
    // ⚠️ CORREGIDO: se agrega el mismo filtro de propiedad por defensa en
    // profundidad. El código de 8 caracteres aleatorios (40 bits) ya es
    // difícil de adivinar por fuerza bruta, pero igual no debería ser
    // posible que un mecánico use el código de OTRO taller si por algún
    // motivo llegara a conocerlo (filtración, error de UI, etc.).
    // ══════════════════════════════════════════════════════════════════════
    public function verificarCodigo(): RedirectResponse
    {
        $idMecanico = (int) session()->get('id_mecanico');
        $codigo     = strtoupper(trim($this->request->getPost('codigo') ?? ''));

        $citaModel = new CitaTallerModel();

        // ── Buscar cita confirmada con ese código, dentro de un taller propio ──
        $cita = $citaModel
            ->join('taller', 'taller.id_taller = cita_taller.taller_id_taller')
            ->where('cita_taller.codigo_confirmacion', $codigo)
            ->where('cita_taller.estado_cita', 'confirmada')
            ->where('taller.mecanicos_id_mecanico', $idMecanico)
            ->first();

        if (!$cita) {
            session()->setFlashdata('error_cita', 'Código incorrecto o cita no confirmada.');
            return redirect()->to(site_url('mecanico/cita'));
        }

        // ── Pasar cita a en_atencion ──────────────────────────────────────
        // El trigger inserta automáticamente la fila en gestion_mantenimiento.
        // codigo_entrega se generará más adelante en la gestión, cuando el
        // mecánico marque el vehículo como listo para entrega.
        $citaModel->update($cita['id_cita'], [
            'estado_cita'           => 'en_atencion',
            'fecha_inicio_atencion' => date('Y-m-d'),
        ]);

        session()->setFlashdata('exito_cita', 'Cita en atención. El vehículo ha sido recibido.');
        return redirect()->to(site_url('mecanico/cita'));
    }
}