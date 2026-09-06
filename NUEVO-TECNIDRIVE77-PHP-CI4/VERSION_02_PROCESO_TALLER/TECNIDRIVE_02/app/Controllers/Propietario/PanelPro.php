<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use App\Models\PropietarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class PanelPro extends BaseController
{
    public function dashboard(): string
    {
        $propietario = (new PropietarioModel())
            ->find(session()->get('usuario_id'));

        return view('propietario/panel_pro', [
            'titulo_pagina'   => 'PANEL_PROPIETARIO',
            'nombre_completo' => session()->get('usuario_nombre') . ' ' . session()->get('usuario_apellidos'),
            'email'           => session()->get('usuario_email'),
            'rol_texto'       => session()->get('usuario_rol_texto'),
            'avatarcolor'     => session()->get('usuario_avatar'),
            'telefono'        => $propietario['telefono_propietario'] ?? '',
        ]);
    }

    public function actualizarPerfil(): ResponseInterface
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Acceso no permitido.']]);
        }

        if (! session()->get('logueado') || (int) session()->get('usuario_rol') !== 2) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['ok' => false, 'errores' => ['general' => 'Sesión inválida.']]);
        }

        $datos = $this->request->getJSON(true);

        $reglas = [
            'email'       => 'required|valid_email|max_length[60]',
            'telefono'    => 'required|regex_match[/^3[0-9]{9}$/]',
            'avatarcolor' => 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]',
        ];

        $mensajes = [
            'email'       => ['required' => 'El correo es obligatorio.',   'valid_email' => 'Correo inválido.'],
            'telefono'    => ['required' => 'El teléfono es obligatorio.', 'regex_match' => 'Celular colombiano inválido (10 dígitos, empieza en 3).'],
            'avatarcolor' => ['required' => 'El color es obligatorio.',    'regex_match' => 'Color hexadecimal inválido.'],
        ];

        if (! $this->validateData($datos ?? [], $reglas, $mensajes)) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'errores' => $this->validator->getErrors()]);
        }

        $email       = strtolower(trim($datos['email']));
        $telefono    = preg_replace('/\s+/', '', $datos['telefono']);
        $avatarcolor = $datos['avatarcolor'];
        $idPersona   = (int) session()->get('usuario_id');

        $modeloPersona = new PersonaModel();
        $emailExiste   = $modeloPersona
            ->where('email', $email)
            ->where('id_persona !=', $idPersona)
            ->countAllResults();

        if ($emailExiste > 0) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'errores' => ['email' => 'Este correo ya está en uso.']]);
        }

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

        session()->set([
            'usuario_email'  => $email,
            'usuario_avatar' => $avatarcolor,
        ]);

        // ── NUEVO: enviar el token CSRF regenerado ─────────────────────────
        // El filtro CSRF, al validar esta misma petición, ya generó un
        // hash nuevo en la sesión (regenerate = true por defecto). Si no
        // se lo devolvemos al JS, la SIGUIENTE edición de perfil en la
        // misma carga de página fallará con el mismo error 403 que
        // estamos resolviendo ahora, porque el meta tag del HTML seguiría
        // teniendo el token viejo, ya inválido.
        return $this->response->setJSON([
            'ok'               => true,
            'csrf_token_name'  => csrf_token(),
            'csrf_token_value' => csrf_hash(),
        ]);
    }
}