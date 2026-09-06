<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;

class MecanicoControl extends BaseController
{
    public function control(): string
    {
        $datos = [
            'titulo_pagina' => 'CONTROL_MECANICO'];

        return view('Mecanico/mecanico_control', $datos);
    }
}