<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;

class ProVehiculo extends BaseController
{
    public function vehiculo(): string
    {
        $datos = [
            'titulo_pagina' => 'VEHICULO-PROPIETARIO'];

        return view('Propietario/pro_vehiculo', $datos);
    }
}