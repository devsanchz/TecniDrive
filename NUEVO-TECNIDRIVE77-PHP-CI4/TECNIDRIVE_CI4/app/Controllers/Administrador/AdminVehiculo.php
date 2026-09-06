<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;

class AdminVehiculo extends BaseController
{
    public function vehiculo(): string
    {
        $datos = [
            'titulo_pagina' => 'ADMIN-VEHICULOS'];

        return view('Administrador/admin_vehiculo', $datos);
    }
}