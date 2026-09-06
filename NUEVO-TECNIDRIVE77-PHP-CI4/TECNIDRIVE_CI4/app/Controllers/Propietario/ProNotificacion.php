<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;

class ProNotificacion extends BaseController
{
    public function notificacion(): string
    {
        $datos = [
            'titulo_pagina' => 'NOTIFICACION-PROPIETARIO'];

        return view('Propietario/pro_notificacion', $datos);
    }
}