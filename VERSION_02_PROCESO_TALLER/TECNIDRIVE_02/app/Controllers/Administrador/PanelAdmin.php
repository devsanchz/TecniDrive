<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class PanelAdmin extends BaseController
{
    // =========================================================================
    // GET administrador/panel
    //
    // La verificación de sesión y de rol (administrador = id_rol 1) ya la
    // realiza AuthFilter, aplicado al grupo 'administrador' en Routes.php
    // con ['filter' => 'auth:1']. Si este método se ejecuta, es porque
    // el filtro ya confirmó que hay sesión activa con el rol correcto.
    // =========================================================================

    public function dashboard(): string
    {
        return view('administrador/panel_admin', [
            'titulo_pagina'   => 'PANEL_ADMIN',
            'nombre_completo' => session()->get('usuario_nombre') . ' ' . session()->get('usuario_apellidos'),
            'email'           => session()->get('usuario_email'),
            'rol_texto'       => session()->get('usuario_rol_texto'),
            'avatarcolor'     => session()->get('usuario_avatar'),
        ]);
    }
}