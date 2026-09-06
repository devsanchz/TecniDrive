<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class AdminReporte extends BaseController
{
    public function reporte(): string
    {
        $datos = [
            'titulo_pagina' => 'ADMIN-REPORTES'];

        return view('Administrador/admin_reporte', $datos);
    }
}