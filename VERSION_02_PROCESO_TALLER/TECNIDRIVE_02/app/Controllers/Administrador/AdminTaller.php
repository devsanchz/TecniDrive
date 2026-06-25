<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\TallerServicioModel;
use App\Models\TallerEspecialidadModel;
use App\Models\CalificacionTallerModel;
use CodeIgniter\HTTP\RedirectResponse;

class AdminTaller extends BaseController
{
    // =========================================================================
    // GET administrador/taller
    //
    // Sesión y rol (administrador = id_rol 1) ya verificados por AuthFilter
    // ('auth:1' en el grupo 'administrador' de Routes.php).
    // =========================================================================

    public function taller(): string
    {
        $db = \Config\Database::connect();

        // Traer todos los talleres con datos del mecánico (nombre, email, avatarcolor)
        $talleres = $db->table('taller t')
            ->select('
                t.id_taller,
                t.nombre_taller,
                t.foto_taller,
                t.descripcion_taller,
                t.direccion_taller,
                t.horario_taller,
                t.estado_taller,
                t.motivo_estado,
                t.fecha_registro,
                p.primer_nombre,
                p.primer_apellido,
                p.email,
                p.avatarcolor
            ')
            ->join('mecanicos m', 'm.id_mecanico = t.mecanicos_id_mecanico')
            ->join('personas p',  'p.id_persona  = m.id_mecanico')
            ->orderBy('t.fecha_registro', 'DESC')
            ->get()
            ->getResultArray();

        // Modelos auxiliares
        $tallerServicioModel     = new TallerServicioModel();
        $tallerEspecialidadModel = new TallerEspecialidadModel();
        $calificacionModel       = new CalificacionTallerModel();

        foreach ($talleres as &$t) {
            $id = (int) $t['id_taller'];

            $t['servicios']      = $tallerServicioModel->obtenerPorTaller($id);
            $t['especialidades'] = $tallerEspecialidadModel->obtenerPorTaller($id);
            $t['calificaciones'] = $calificacionModel->obtenerPorTaller($id);
            $t['promedio']       = $calificacionModel->promedio($id);
        }
        unset($t);

        // Contadores para las tarjetas superiores
        $total        = count($talleres);
        $desactivados = count(array_filter($talleres, fn($t) => ! $t['estado_taller']));

        return view('Administrador/admin_taller', [
            'titulo_pagina' => 'ADMIN-TALLERES',
            'talleres'      => $talleres,
            'total'         => $total,
            'desactivados'  => $desactivados,
        ]);
    }

    // =========================================================================
    // POST administrador/taller/activar
    //
    // Sin restricción adicional de "propiedad": el administrador tiene
    // autoridad sobre TODOS los talleres por diseño, no solo los suyos.
    // No aplica aquí el patrón de autorización por recurso que sí usamos
    // en los controladores de propietario/mecánico.
    // =========================================================================

    public function activar(): RedirectResponse
    {
        $idTaller = (int) $this->request->getPost('id_taller');

        \Config\Database::connect()
            ->table('taller')
            ->where('id_taller', $idTaller)
            ->update([
                'estado_taller'   => 1,
                'bloqueado_admin' => 0,
                'motivo_estado'   => null,
            ]);

        session()->setFlashdata('exito_admin', 'Taller activado correctamente.');
        return redirect()->to(site_url('administrador/taller'));
    }

    // =========================================================================
    // POST administrador/taller/desactivar
    // =========================================================================

    public function desactivar(): RedirectResponse
    {
        $idTaller = (int) $this->request->getPost('id_taller');
        $motivo   = trim($this->request->getPost('motivo') ?? '');

        if ($motivo === '') {
            session()->setFlashdata('error_admin', 'Debes indicar un motivo de desactivación.');
            return redirect()->to(site_url('administrador/taller'));
        }

        \Config\Database::connect()
            ->table('taller')
            ->where('id_taller', $idTaller)
            ->update([
                'estado_taller'   => 0,
                'bloqueado_admin' => 1,
                'motivo_estado'   => substr($motivo, 0, 100),
            ]);

        session()->setFlashdata('exito_admin', 'Taller desactivado correctamente.');
        return redirect()->to(site_url('administrador/taller'));
    }
}