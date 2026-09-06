<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\CalificacionTallerModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminCalificacion extends BaseController
{
    // =========================================================================
    // GET administrador/calificacion
    //
    // Sesión y rol (administrador = id_rol 1) ya verificados por AuthFilter
    // ('auth:1' en el grupo 'administrador' de Routes.php).
    // =========================================================================

    public function calificacion(): string
    {
        $calificacionModel = new CalificacionTallerModel();
        $calificaciones    = $calificacionModel->obtenerTodas();

        $totalRechazadas  = count(array_filter($calificaciones, fn($c) => $c['estado'] === 'rechazada'));
        $totalComentarios = count(array_filter($calificaciones, fn($c) => ! empty($c['comentario'])));

        $datos = [
            'titulo_pagina'    => 'ADMIN-CALIFICACIONES',
            'calificaciones'   => $calificaciones,
            'total'            => count($calificaciones),
            'totalRechazadas'  => $totalRechazadas,
            'totalComentarios' => $totalComentarios,
        ];

        return view('Administrador/admin_calificacion', $datos);
    }

    // ── POST admin/calificacion/aceptar ────────────────────────────────────
    // El administrador tiene autoridad sobre TODAS las calificaciones; no
    // aplica aquí verificación de "propiedad" como en propietario/mecánico.
    public function aceptar(): ResponseInterface
    {
        $datos = $this->request->getJSON(true) ?? [];
        $id    = (int) ($datos['id'] ?? $this->request->getPost('id') ?? 0);

        if ($id <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'ID inválido.']);
        }

        $modelo = new CalificacionTallerModel();
        $ok     = $modelo->aprobar($id);

        return $this->response->setJSON([
            'ok'               => $ok,
            'csrf_token_name'  => csrf_token(),
            'csrf_token_value' => csrf_hash(),
        ]);
    }

    // ── POST admin/calificacion/rechazar ───────────────────────────────────
    public function rechazar(): ResponseInterface
    {
        $datos = $this->request->getJSON(true) ?? [];
        $id    = (int) ($datos['id'] ?? $this->request->getPost('id') ?? 0);

        if ($id <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'ID inválido.']);
        }

        $modelo = new CalificacionTallerModel();
        $ok     = $modelo->rechazar($id);

        return $this->response->setJSON([
            'ok'               => $ok,
            'csrf_token_name'  => csrf_token(),
            'csrf_token_value' => csrf_hash(),
        ]);
    }
}