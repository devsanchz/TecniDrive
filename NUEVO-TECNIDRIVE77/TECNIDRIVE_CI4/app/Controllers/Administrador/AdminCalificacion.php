<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class AdminCalificacion extends BaseController
{
    public function calificacion(): string
    {
        $datos = [
            'titulo_pagina' => 'ADMIN-CALIFICACIONES'];

        return view('Administrador/admin_calificacion', $datos);
    }
}