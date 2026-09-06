<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;

class PanelPro extends BaseController
{
    public function dashboard(): string
    {
        $datos = [
            'titulo_pagina' => 'PANEL_PROPIETARIO'];

        return view('Propietario/panel_pro', $datos);
    }
}