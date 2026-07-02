<?php

namespace App\Controllers;

use App\Models\UsuarioModel; // Importamos el modelo
use CodeIgniter\RESTful\ResourceController;

class AuthController extends ResourceController
{
    public function login()
    {
        $model = new UsuarioModel();
        
        // 1. Recibir datos del Android (@Field)
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // 2. Buscar al usuario por email
        $user = $model->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $response = [
                    'status'     => true,
                    'mensaje'    => 'Login exitoso',
                    'id_persona' => (int)$user['id_persona'],
                    'nombre'     => $user['nombre'],
                    'rol'        => (int)$user['rol']
                ];
                return $this->respond($response, 200);
            }
        }

        // 4. Si falla el usuario o la contraseña
        return $this->respond([
            'status'  => false,
            'mensaje' => 'Correo o contraseña incorrectos'
        ], 200); // Enviamos 200 ->status:false
    }
}