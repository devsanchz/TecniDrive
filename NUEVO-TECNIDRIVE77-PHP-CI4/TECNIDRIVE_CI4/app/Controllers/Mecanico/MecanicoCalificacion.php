<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;

class MecanicoCalificacion extends BaseController
{
    public function calificacion(): string
    {
        $datos = [
            'titulo_pagina' => 'CALIFICACION_MECANICO'];

        return view('Mecanico/mecanico_calificacion', $datos);
    }
}