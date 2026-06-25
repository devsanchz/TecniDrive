<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;
use App\Models\TallerModel;
use App\Models\CalificacionTallerModel;
use CodeIgniter\HTTP\RedirectResponse;

class MecanicoCalificacion extends BaseController
{
    // ── Helper privado: obtener id_mecanico de la sesión ───────────────────
    // Sesión y rol (mecánico = id_rol 3) ya verificados por AuthFilter
    // ('auth:3' en el grupo 'mecanico' de Routes.php). Por diseño de
    // Ingresar::establecerSesion(), si usuario_rol = 3 entonces id_mecanico
    // siempre está presente — mismo criterio aplicado en MecanicoControl.php.
    private function idMecanico(): int
    {
        return (int) session()->get('id_mecanico');
    }

    // ══════════════════════════════════════════════════════════════════════
    // GET — Listado de calificaciones del taller del mecánico
    // ══════════════════════════════════════════════════════════════════════
    public function calificacion(): string
    {
        $idMecanico = $this->idMecanico();
        $taller     = (new TallerModel())->buscarPorMecanico($idMecanico);

        if (! $taller) {
            return view('Mecanico/mecanico_calificacion', [
                'titulo_pagina'  => 'CALIFICACION_MECANICO',
                'calificaciones' => [],
                'promedio'       => 0,
                'nuevas_hoy'     => 0,
            ]);
        }

        $idTaller    = (int) $taller['id_taller'];
        $califModelo = new CalificacionTallerModel();

        // 'visto' ya viene directo de la BD dentro de cada fila
        $calificaciones = $califModelo->obtenerPorTaller($idTaller);

        // ── Contadores para las tarjetas superiores ───────────────────────
        $promedio  = $califModelo->promedio($idTaller);
        $hoy       = date('Y-m-d');
        $nuevasHoy = count(array_filter($calificaciones, function ($c) use ($hoy) {
            return substr($c['fecha_registro'], 0, 10) === $hoy;
        }));

        return view('Mecanico/mecanico_calificacion', [
            'titulo_pagina'  => 'CALIFICACION_MECANICO',
            'calificaciones' => $calificaciones,
            'promedio'       => $promedio,
            'nuevas_hoy'     => $nuevasHoy,
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // POST — Marcar una calificación como "visto" (persistido en BD)
    // ══════════════════════════════════════════════════════════════════════
    public function marcarVisto(): RedirectResponse
    {
        $idMecanico = $this->idMecanico();
        $taller     = (new TallerModel())->buscarPorMecanico($idMecanico);

        if (! $taller) {
            return redirect()->to(site_url('mecanico/calificacion'));
        }

        $idTaller       = (int) $taller['id_taller'];
        $idCalificacion = (int) $this->request->getPost('id_calificacion');

        $califModelo = new CalificacionTallerModel();

        // ── Seguridad: la calificación debe pertenecer a este taller ──────
        // Autorización a nivel de recurso — se conserva exactamente igual.
        $calificacion = $califModelo
            ->where('id_calificacion',  $idCalificacion)
            ->where('taller_id_taller', $idTaller)
            ->first();

        if ($calificacion) {
            $califModelo->marcarVisto($idCalificacion);
        }

        return redirect()->to(site_url('mecanico/calificacion'));
    }
}