<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class AdminTaller extends BaseController
{
    public function taller(): string
    {
        $datos = [
            'titulo_pagina' => 'ADMIN-TALLERES'];

        return view('Administrador/admin_taller', $datos);
    }
}