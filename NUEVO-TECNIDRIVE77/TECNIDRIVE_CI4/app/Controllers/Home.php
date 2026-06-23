<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
         $datos = ['titulo_pagina'=>'TECNIDRIVE-PRINCIPAL'];
        return view('index', $datos);
    }
}
