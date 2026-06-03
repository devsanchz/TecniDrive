<?php

namespace App\Controllers;

use App\Models\UserModel;

class Login extends BaseController
{
    // GET /login  →  muestra el formulario

    public function index()
    {
    // Si ya hay sesión activa manda directo al panel
        if (session()->get('logged_in')) {
            return $this->redirigirPorRol(session()->get('rol'));
        }

        return view('auth/login');
    }
    // POST /login  


    public function roles()
    {

        // 1. Validar campos del formulario
        $email      = $this->request->getPost('email');
        $passInput  = $this->request->getPost('contrasena');

#usar esto en caso de necesitar tomas los datos 
# dd($this->request->getPost());


        // 2. Buscar persona 

        $model  = new UserModel();
        $usr    = $model->buscarPorEmailConRol($email);

        // 3. Verificar que exista el email
        if (! $usr) {
            return redirect()->to(base_url('index'))
                             ->with('error', 'El correo no está registrado.');
        }

        // 4. Verificar contraseña
        //    password_verify usando bycript en cont 12
        if (! password_verify($passInput, $usr['contrasena'])) {
            return redirect()->to(base_url('index'))
                             ->with('error', 'Contraseña incorrecta.');
        }

        // 5. Crear sesión
        session()->set([
            'id'         => $usr['id_persona'],
            'nombre'     => $usr['primer_nombre'] . ' ' . $usr['primer_apellido'],
            'email'      => $usr['email'],
            'rol'        => $usr['texto_rol'],
            'logged_in'  => true,
        ]);

        // 6. Redirigir según rol
        return $this->redirigirPorRol($usr['texto_rol']);
    }
    //LogOut al terminar

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('mensaje', 'Sesión cerrada correctamente.');
    }
    // Método interno: centraliza las redirecciones por rol

    private function redirigirPorRol(?string $rol)
    {
        return match($rol) {
            'Administrador' => redirect()->to('/panel-administrador'),
            'Mecanico'      => redirect()->to('/panel-mecanico'),
            'Propietario'   => redirect()->to('/panel-propietario'),
            default         => redirect()->to('/login')->with('error', 'Rol no reconocido.')
        };
    }
// en caso de que se envien datos correctos y el mismo controlador los envie (SOLO PARA PRUEBAS)
    public function volverLogin(){
        return view('pages/roles');
    }
}
    