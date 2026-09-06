<?php

namespace App\Controllers\Autentificar;

use App\Controllers\BaseController;

class Recuperar extends BaseController
{
    public function restablecer(): string
    {
        $datos = [
            'titulo_pagina' => 'RECUPERAR CONTRASEÑA'];

        return view('Autentificar/recuperar', $datos);
    }
}