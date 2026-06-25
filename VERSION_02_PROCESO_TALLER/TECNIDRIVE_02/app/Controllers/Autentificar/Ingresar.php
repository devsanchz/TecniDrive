<?php

namespace App\Controllers\Autentificar;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use App\Models\RolHasPersonaModel;
use App\Models\RolesModel;
use CodeIgniter\HTTP\RedirectResponse;

class Ingresar extends BaseController
{
    // IDs de rol según la tabla roles de la BD
    private const ROL_ADMINISTRADOR = 1;
    private const ROL_PROPIETARIO   = 2;
    private const ROL_MECANICO      = 3;

    // Mapa rol → ruta del panel correspondiente
    public const PANELES = [
        self::ROL_ADMINISTRADOR => 'administrador/panel',
        self::ROL_PROPIETARIO   => 'propietario/panel',
        self::ROL_MECANICO      => 'mecanico/panel',
    ];

    // =========================================================================
    // GET — Mostrar formulario de login
    // =========================================================================

    public function ingreso(): string
    {
        return view('Autentificar/ingreso', [
            'titulo_pagina' => 'INGRESO',
            'errores'       => session()->getFlashdata('errores') ?? [],
            'old'           => session()->getFlashdata('old')     ?? [],
            'exito'         => session()->getFlashdata('exito')   ?? null,
        ]);
    }

    // =========================================================================
    // POST — Procesar login
    // =========================================================================

    public function procesarIngreso(): RedirectResponse
    {
        // --- Validar campos --------------------------------------------------
        $reglas = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $mensajes = [
            'email'    => ['required' => 'El correo es obligatorio.', 'valid_email' => 'Ingresa un correo válido.'],
            'password' => ['required' => 'La contraseña es obligatoria.'],
        ];

        if (! $this->validate($reglas, $mensajes)) {
            session()->setFlashdata('errores', $this->validator->getErrors());
            session()->setFlashdata('old', ['email' => $this->request->getPost('email')]);

            return redirect()->to(site_url('autentificar/ingreso'));
        }

        // --- Buscar persona por correo ---------------------------------------
        $email    = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $persona = (new PersonaModel())->where('email', $email)->first();

        // Mensaje genérico para no revelar si el correo existe o no
        if (! $persona || ! password_verify($password, $persona['password_hash'])) {
            session()->setFlashdata('errores', ['login' => 'Correo o contraseña incorrectos.']);
            session()->setFlashdata('old', ['email' => $email]);

            return redirect()->to(site_url('autentificar/ingreso'));
        }

        // --- Consultar el rol del usuario ------------------------------------
        $relacion = (new RolHasPersonaModel())
            ->where('personas_id_persona', $persona['id_persona'])
            ->first();

        if (! $relacion) {
            session()->setFlashdata('errores', ['login' => 'Tu cuenta no tiene rol asignado. Contacta al soporte.']);

            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $idRol = (int) $relacion['roles_id_rol'];

        // --- Obtener texto del rol y construir sesión (método compartido) ----
        $rutaPanel = self::establecerSesion($persona, $idRol);

        if ($rutaPanel === null) {
            session()->destroy();
            session()->setFlashdata('errores', ['login' => 'Rol no reconocido. Contacta al soporte.']);

            return redirect()->to(site_url('autentificar/ingreso'));
        }

        return redirect()->to(site_url($rutaPanel));
    }

    // =========================================================================
    // GET — Cerrar sesión
    // =========================================================================

    public function salir(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to(site_url('autentificar/ingreso'))
                         ->with('exito', 'Sesión cerrada correctamente.');
    }

    // =========================================================================
    // MÉTODO ESTÁTICO COMPARTIDO — Construir sesión y devolver ruta del panel
    // =========================================================================

    public static function establecerSesion(array $persona, int $idRol): ?string
    {
        $rol      = (new RolesModel())->find($idRol);
        $rolTexto = $rol['texto_rol'] ?? 'Usuario';

        $rutaPanel = self::PANELES[$idRol] ?? null;

        if ($rutaPanel === null) {
            return null;
        }

        $datosSesion = [
            'usuario_id'        => $persona['id_persona'],
            'usuario_nombre'    => $persona['primer_nombre'],
            'usuario_apellidos' => trim(($persona['primer_apellido'] ?? '') . ' ' . ($persona['segundo_apellido'] ?? '')),
            'usuario_email'     => $persona['email'],
            'usuario_avatar'    => $persona['avatarcolor'],
            'usuario_rol'       => $idRol,
            'usuario_rol_texto' => $rolTexto,
            'logueado'          => true,
        ];

        // id_persona == id_propietario == id_mecanico por diseño FK de la BD
        if ($idRol === 2) {
            $datosSesion['id_propietario'] = $persona['id_persona'];
        }
        if ($idRol === 3) {
            $datosSesion['id_mecanico'] = $persona['id_persona'];
        }

        session()->set($datosSesion);

        // ← NUEVO: regenera el ID de sesión tras login/registro exitoso.
        // Previene session fixation (un atacante fija un ID antes del
        // login y lo reutiliza después). El 'true' destruye el ID viejo
        // de inmediato.
        session()->regenerate(true);

        return $rutaPanel;
    }
}