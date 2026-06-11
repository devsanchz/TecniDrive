<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use App\Models\PropietarioModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class PanelPro extends BaseController
{
    // =========================================================================
    // GET propietario/panel
    // =========================================================================

    public function dashboard(): string|RedirectResponse
    {
        // Guard: verificar sesión activa
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'))
                             ->with('errores', ['login' => 'Debes iniciar sesión para continuar.']);
        }

        // Guard: verificar que el rol sea propietario (id_rol = 2)
        if ((int) session()->get('usuario_rol') !== 2) {
            return redirect()->to(site_url('autentificar/ingreso'))
                             ->with('errores', ['login' => 'No tienes permiso para acceder a esta sección.']);
        }

        // Consultar teléfono desde la tabla propietarios
        $propietario = (new PropietarioModel())
            ->find(session()->get('usuario_id'));

        return view('propietario/panel_pro', [
            'titulo_pagina' => 'PANEL_PROPIETARIO',
            'nombre_completo' => session()->get('usuario_nombre') . ' ' . session()->get('usuario_apellidos'),
            'email'         => session()->get('usuario_email'),
            'rol_texto'     => session()->get('usuario_rol_texto'),
            'avatarcolor'   => session()->get('usuario_avatar'),
            'telefono'      => $propietario['telefono_propietario'] ?? ''
        ]);
    }

    // =========================================================================
    // POST propietario/perfil/actualizar  (llamado por fetch desde JS)
    //
    // Recibe JSON: { email, telefono, avatarcolor }
    // Actualiza tabla personas (email + avatarcolor) y tabla propietarios
    // (telefono_propietario) dentro de una transacción para garantizar
    // integridad si alguna de las dos falla.
    // Devuelve JSON: { ok: true } o { ok: false, errores: {...} }
    // =========================================================================

    public function actualizarPerfil(): ResponseInterface
    {
        // Guard AJAX
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Acceso no permitido.']]);
        }

        // Guard de sesión
        if (! session()->get('logueado') || (int) session()->get('usuario_rol') !== 2) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Sesión inválida.']]);
        }

        $datos = $this->request->getJSON(true);

        // --- Validar ----------------------------------------------------------
        $reglas = [
            'email'       => 'required|valid_email|max_length[60]',
            'telefono'    => 'required|regex_match[/^3[0-9]{9}$/]',
            'avatarcolor' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        ];

        $mensajes = [
            'email'       => ['required' => 'El correo es obligatorio.',   'valid_email'  => 'Correo inválido.'],
            'telefono'    => ['required' => 'El teléfono es obligatorio.', 'regex_match'  => 'Celular colombiano inválido (10 dígitos, empieza en 3).'],
            'avatarcolor' => ['required' => 'El color es obligatorio.',    'regex_match'  => 'Color hexadecimal inválido.'],
        ];

        if (! $this->validateData($datos ?? [], $reglas, $mensajes)) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'errores' => $this->validator->getErrors()]);
        }

        $email       = strtolower(trim($datos['email']));
        $telefono    = preg_replace('/\s+/', '', $datos['telefono']);
        $avatarcolor = $datos['avatarcolor'];
        $idPersona   = (int) session()->get('usuario_id');

        // Verificar que el nuevo email no lo use otra persona
        $modeloPersona = new PersonaModel();
        $emailExiste   = $modeloPersona
            ->where('email', $email)
            ->where('id_persona !=', $idPersona)
            ->countAllResults();

        if ($emailExiste > 0) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'errores' => ['email' => 'Este correo ya está en uso.']]);
        }

        // --- Actualizar en BD (transacción) -----------------------------------
        // Usamos transacción porque actualizamos dos tablas: si falla la
        // segunda, la primera se revierte automáticamente.
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $modeloPersona->update($idPersona, [
                'email'       => $email,
                'avatarcolor' => $avatarcolor,
            ]);

            (new PropietarioModel())->update($idPersona, [
                'telefono_propietario' => (int) $telefono,
            ]);
        } catch (\Exception $e) {
            $db->transRollback();

            return $this->response->setStatusCode(500)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Error al guardar. Intenta de nuevo.']]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setStatusCode(500)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Error al completar la actualización.']]);
        }

        // --- Actualizar sesión ------------------------------------------------
        session()->set([
            'usuario_email'  => $email,
            'usuario_avatar' => $avatarcolor,
        ]);

        return $this->response->setJSON(['ok' => true]);
    }
}