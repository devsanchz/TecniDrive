<?php
// app/Controllers/AuthWeb.php
namespace App\Controllers;      // ← sin subcarpetas

use CodeIgniter\Controller;

class AuthWeb extends Controller
{
    public function registro(): string
    {
        return view('registro'); // ← busca app/Views/registro.php
    }

    public function ingreso(): string
    {
        return view('login');    // ← busca app/Views/login.php
    }
}