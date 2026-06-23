<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;

class ProCalificacion extends BaseController
{
    public function calificacion(): string
    {
        $datos = [
            'titulo_pagina' => 'CALIFIACION-PROPIETARIO'];

        return view('Propietario/pro_calificacion', $datos);
    }
}