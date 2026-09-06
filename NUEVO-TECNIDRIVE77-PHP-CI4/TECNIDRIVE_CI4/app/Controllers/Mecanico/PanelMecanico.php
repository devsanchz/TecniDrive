<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;

class PanelMecanico extends BaseController
{
    public function dashboard(): string
    {
        $datos = [
            'titulo_pagina' => 'PANEL_MECANICO'];

        return view('Mecanico/panel_mecanico', $datos);
    }
}