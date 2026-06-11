<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;

class MecanicoCita extends BaseController
{
    public function cita(): string
    {
        $datos = [
            'titulo_pagina' => 'TALLER_MECANICO'];

        return view('Mecanico/mecanico_citas', $datos);
    }
}