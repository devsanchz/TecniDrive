<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Se ejecuta ANTES de llegar al controlador.
     * $arguments contiene los roles permitidos para la ruta,
     * definidos en Routes.php como 'auth:1', 'auth:2', etc.
     */
   public function before(RequestInterface $request, $arguments = null)
{
    log_message('debug', 'AuthFilter -> logueado=' . json_encode(session()->get('logueado'))
        . ' | usuario_rol=' . json_encode(session()->get('usuario_rol'))
        . ' | arguments=' . json_encode($arguments));

    if (! session()->get('logueado')) {
            session()->setFlashdata('errores', ['login' => 'Debes iniciar sesión para continuar.']);

            return redirect()->to(site_url('autentificar/ingreso'));
        }

        if ($arguments) {
            $rolActual       = (int) session()->get('usuario_rol');
            $rolesPermitidos = array_map('intval', $arguments);

            if (! in_array($rolActual, $rolesPermitidos, true)) {
                session()->setFlashdata('errores', ['login' => 'No tienes permisos para acceder a este módulo.']);

                return redirect()->to(site_url('autentificar/ingreso'));
            }
        }
    }

   
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}