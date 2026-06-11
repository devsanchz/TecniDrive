<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class PanelAdmin extends BaseController
{
    // =========================================================================
    // GET administrador/panel
    // =========================================================================

    public function dashboard(): string|RedirectResponse
    {
        // Guard: verificar sesión activa
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'))
                             ->with('errores', ['login' => 'Debes iniciar sesión para continuar.']);
        }

        // Guard: verificar que el rol sea administrador (id_rol = 1)
        if ((int) session()->get('usuario_rol') !== 1) {
            return redirect()->to(site_url('autentificar/ingreso'))
                             ->with('errores', ['login' => 'No tienes permiso para acceder a esta sección.']);
        }

        return view('administrador/panel_admin', [
            'titulo_pagina' => 'PANEL_ADMIN',
'nombre_completo' => session()->get('usuario_nombre') . ' ' . session()->get('usuario_apellidos'),
            'email'         => session()->get('usuario_email'),
            'rol_texto'     => session()->get('usuario_rol_texto'),
            'avatarcolor'   => session()->get('usuario_avatar')
        ]);
    }

    // =========================================================================
    // POST administrador/perfil/actualizar  (llamado por fetch desde JS)
    //
    // Recibe JSON: { email, avatarcolor }
    // El administrador no tiene tabla propia con teléfono, solo actualiza
    // la tabla personas (email + avatarcolor).
    // Devuelve JSON: { ok: true } o { ok: false, errores: {...} }
    // =========================================================================

    public function actualizarPerfil(): ResponseInterface
    {
        // Guard AJAX: solo acepta peticiones con fetch/XMLHttpRequest
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Acceso no permitido.']]);
        }

        // Guard de sesión
        if (! session()->get('logueado') || (int) session()->get('usuario_rol') !== 1) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Sesión inválida.']]);
        }

        // Leer JSON del body
        $datos = $this->request->getJSON(true);

        // --- Validar -----------------------------------------------------------
        $reglas = [
            'email'       => 'required|valid_email|max_length[60]',
            'avatarcolor' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        ];

        $mensajes = [
            'email'       => ['required' => 'El correo es obligatorio.', 'valid_email' => 'Correo inválido.'],
            'avatarcolor' => ['required' => 'El color es obligatorio.',  'regex_match' => 'Color hexadecimal inválido.'],
        ];

        if (! $this->validateData($datos ?? [], $reglas, $mensajes)) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'errores' => $this->validator->getErrors()]);
        }

        $email       = strtolower(trim($datos['email']));
        $avatarcolor = $datos['avatarcolor'];
        $idPersona   = (int) session()->get('usuario_id');

        // Verificar que el nuevo email no lo use otra persona
        $modelo      = new PersonaModel();
        $emailExiste = $modelo
            ->where('email', $email)
            ->where('id_persona !=', $idPersona)
            ->countAllResults();

        if ($emailExiste > 0) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'errores' => ['email' => 'Este correo ya está en uso.']]);
        }

        // --- Actualizar en BD -------------------------------------------------
        $modelo->update($idPersona, [
            'email'       => $email,
            'avatarcolor' => $avatarcolor,
        ]);

        // --- Actualizar sesión ------------------------------------------------
        // Mantener la sesión sincronizada con la BD para que el próximo
        // render de la vista muestre los valores correctos sin relogin.
        session()->set([
            'usuario_email'  => $email,
            'usuario_avatar' => $avatarcolor,
        ]);

        return $this->response->setJSON(['ok' => true]);
    }
}