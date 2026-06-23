<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class PanelAdmin extends BaseController
{
    public function dashboard(): string
    {
        $datos = [
            'titulo_pagina' => 'PANEL_ADMIN'];

        return view('Administrador/panel_admin', $datos);
    }
    
}