<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;

class ProTaller extends BaseController
{
    public function taller(): string
    {
        $datos = [
            'titulo_pagina' => 'TALLER-PROPIETARIO'
        ];

        return view('Propietario/pro_taller', $datos);
    }
}