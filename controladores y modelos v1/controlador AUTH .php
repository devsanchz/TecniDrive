<?php

namespace App\Controllers;

use App\Models\PersonaModel;

class AuthController extends BaseController
{
    protected $personaModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
    }

    /**
     * Vista registro
     */
    public function registro()
    {
        return view('auth/registro');
    }

    /**
     * Guardar usuario
     */
    public function guardarRegistro()
    {
        $rules = [
            'primer_nombre'   => 'required|min_length[2]',
            'primer_apellido' => 'required|min_length[2]',
            'segundo_apellido'=> 'required|min_length[2]',
            'email'           => 'required|valid_email|is_unique[personas.email]',
            'password'        => 'required|min_length[8]',
            'password2'       => 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->personaModel->save([
            'primer_nombre'   => $this->request->getPost('primer_nombre'),
            'segundo_nombre'  => $this->request->getPost('segundo_nombre'),
            'primer_apellido' => $this->request->getPost('primer_apellido'),
            'segundo_apellido'=> $this->request->getPost('segundo_apellido'),
            'email'           => $this->request->getPost('email'),
            'password_hash'   => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'avatarcolor'     => '#3498db'
        ]);

        return redirect()->to('/login')
            ->with('success', 'Usuario registrado correctamente');
    }

    /**
     * Vista login
     */
    public function login()
    {
        return view('auth/login');
    }

    /**
     * Validar login
     */
    public function autenticar()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $usuario = $this->personaModel
            ->where('email', $email)
            ->first();

        if (!$usuario) {
            return redirect()->back()
                ->with('error', 'Correo no encontrado');
        }

        if (!password_verify($password, $usuario['password_hash'])) {
            return redirect()->back()
                ->with('error', 'Contraseña incorrecta');
        }

        session()->set([
            'id_persona'      => $usuario['id_persona'],
            'nombre'          => $usuario['primer_nombre'],
            'email'           => $usuario['email'],
            'logueado'        => true
        ]);

        return redirect()->to('/dashboard');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}