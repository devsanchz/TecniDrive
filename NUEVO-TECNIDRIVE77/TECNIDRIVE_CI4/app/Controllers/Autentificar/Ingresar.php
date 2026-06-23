<?php

namespace App\Controllers\Autentificar;

use App\Controllers\BaseController;

class Ingresar extends BaseController
{
    public function ingreso(): string
    {
        $datos = [
            'titulo_pagina' => 'INGRESO'];

        return view('Autentificar/ingreso', $datos);
    }
    
}