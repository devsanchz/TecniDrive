<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;

class ProCita extends BaseController
{
    public function cita(): string
    {
        $datos = [
            'titulo_pagina' => 'CITA-PROPIETARIO'
        ];

        return view('Propietario/pro_cita', $datos);
    }
}