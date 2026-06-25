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
    // NOTA: Se convierte en public para que Registrar pueda acceder a él.
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
        // Se delega a establecerSesion() para que Registrar pueda reusar
        // exactamente la misma lógica sin duplicar código.
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
    //
    // Se declara public static para que Registrar::procesarRol() pueda
    // llamarlo como Ingresar::establecerSesion(...) sin instanciar el
    // controlador (instanciar un controlador de CI4 manualmente es un
    // antipatrón). Devuelve la ruta del panel o null si el rol no existe.
    //
    // Recibe el array $persona tal como lo devuelve PersonaModel (mismas
    // claves que usa procesarIngreso), garantizando que ambos flujos
    // —login y registro— produzcan sesiones idénticas.
    // =========================================================================

    public static function establecerSesion(array $persona, int $idRol): ?string
    {
        // Consultar el texto del rol (ej: "Propietario", "Mecánico")
        $rol      = (new RolesModel())->find($idRol);
        $rolTexto = $rol['texto_rol'] ?? 'Usuario';

        // Verificar que el rol tenga un panel configurado
        $rutaPanel = self::PANELES[$idRol] ?? null;

        if ($rutaPanel === null) {
            return null;
        }

        // Escribir sesión con exactamente las mismas claves que antes
    session()->set([
    'usuario_id'        => $persona['id_persona'],
    'usuario_nombre'    => $persona['primer_nombre'],
    'usuario_apellidos' => trim(($persona['primer_apellido'] ?? '') . ' ' . ($persona['segundo_apellido'] ?? '')),
    'usuario_email'     => $persona['email'],
    'usuario_avatar'    => $persona['avatarcolor'],
    'usuario_rol'       => $idRol,
    'usuario_rol_texto' => $rolTexto,
    'logueado'          => true,
]);

        return $rutaPanel;
    }
}