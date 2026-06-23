<?php

namespace App\Controllers\Mecanico;

use App\Controllers\BaseController;

class MecanicoTaller extends BaseController
{
    public function taller(): string
    {
        $datos = [
            'titulo_pagina' => 'TALLER_MECANICO'];

        return view('Mecanico/mecanico_taller', $datos);
    }
}