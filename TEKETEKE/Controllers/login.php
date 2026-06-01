<?php

namespace app\Controllers;
use Codeigniter\Controller;
use app\Models\UserModels;

class login extends BaseController {
    Public Function Index(){
        return View('index');
        //cabe aclarar que redirecciona a la pagina login llamada index
        // si se cambia el nombre del archivo login; cambiarlo en la funcion View('aqui.')
    }
    Public Function authenticate(){
        $_SESSION = sesion();
        $Model = New UserModel();

        $Email = $this ->request->getVar('email');
        $Pss = $this->request->getVar('contrasena');

        $Usr = $Model->where('email',$Email)->first();

        if ($Usr) {
            $Pss = $Usr['contrasena'];
            //aqui va la contraseña encriptada, e este caso usare Argoni2i desde el form de login
            //aunque se le puede dejar igual si encriptar, perotener eso e cuenta
            if (password_verify($Pss, $pass)) {
                $_SESSION = [

                'id'          => $Usr['id_persona'],
                'Email'       => $Usr['email'],
                'contrasena'  => $Usr['contrasena'],
                'rol'         => $Usr['rol'],
                'logged_in'   => TRUE

                ];

                $session->set($_SESSION);

                switch(/*aqui va la sparacion por funciones*/ $rol){
                    case 'Administrador':
                        header('/pages/Panel_administrador.php');
                        break;
                    case 'Mecanico':
                        header('/pages/Panel_mecanico.php');
                        break;
                    case 'Propietario':
                        header('/pages/Panel_propietario.php');
                        break;
                    default:
                        echo "usuario invalido";
                        return redirect()->to('/pages/');
                    break;
                }
                
            }
        } else {
            $_SESSION ->setFlashdata('msg','correo no encontrado');
            return redirect()->to('/index');
        }
    }
    public function logout(){
        $_SESSION = session();
        $_SESSION->
    }
}


?>