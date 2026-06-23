<?php

namespace App\Controllers\Autentificar;

use App\Controllers\BaseController;

class Registrar extends BaseController
{
    public function registro(): string
    {
        $datos = [
            'titulo_pagina' => 'REGISTRO'];

        return view('Autentificar/registro', $datos);
    }
     public function rol_p(): string
    {
        $datos = [
            'titulo_pagina' => 'ESTABLECER ROL'];

        return view('Autentificar/rol', $datos);
    }
}