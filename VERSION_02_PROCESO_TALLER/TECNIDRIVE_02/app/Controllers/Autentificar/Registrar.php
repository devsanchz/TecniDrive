<?php

namespace App\Controllers\Autentificar;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use App\Models\RolHasPersonaModel;
use App\Models\PropietarioModel;
use App\Models\MecanicoModel;
use CodeIgniter\HTTP\RedirectResponse;

class Registrar extends BaseController
{
    // IDs de rol según la tabla roles de la BD
    private const ROL_PROPIETARIO = 2;
    private const ROL_MECANICO    = 3;

    // =========================================================================
    // PASO 1 — Mostrar formulario de registro
    // =========================================================================

    public function registro(): string
    {
        return view('Autentificar/registro', [
            'titulo_pagina' => 'REGISTRO',
            'errores'       => session()->getFlashdata('errores') ?? [],
            'old'           => session()->getFlashdata('old')     ?? [],
        ]);
    }

    // =========================================================================
    // PASO 1 — Procesar formulario de registro
    // =========================================================================

    public function procesarRegistro(): RedirectResponse
    {
        // --- Reglas de validación --------------------------------------------
        $reglas = [
            'primer_nombre'    => 'required|min_length[2]|max_length[30]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/u]',
            'segundo_nombre'   => 'permit_empty|min_length[2]|max_length[30]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/u]',
            'primer_apellido'  => 'required|min_length[2]|max_length[25]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/u]',
            'segundo_apellido' => 'required|min_length[2]|max_length[25]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/u]',
            'email'            => 'required|valid_email|max_length[60]',
            'telefono' => 'required|regex_match[/^3[0-9]{9}$/]',
            'password'         => 'required|min_length[8]|max_length[72]',
        ];

        $mensajes = [
            'primer_nombre'    => ['required' => 'El primer nombre es obligatorio.',    'regex_match' => 'El nombre solo puede contener letras.'],
            'segundo_nombre'   => ['regex_match' => 'El nombre solo puede contener letras.'],
            'primer_apellido'  => ['required' => 'El primer apellido es obligatorio.',  'regex_match' => 'El apellido solo puede contener letras.'],
            'segundo_apellido' => ['required' => 'El segundo apellido es obligatorio.', 'regex_match' => 'El apellido solo puede contener letras.'],
            'email'            => ['required' => 'El correo es obligatorio.',           'valid_email' => 'Ingresa un correo electrónico válido.'],
            'telefono'         => ['required' => 'El teléfono es obligatorio.',         'regex_match' => 'Ingresa un celular colombiano válido (10 dígitos, empieza en 3).'],
            'password'         => ['required' => 'La contraseña es obligatoria.',       'min_length'  => 'La contraseña debe tener al menos 8 caracteres.'],
        ];

        // --- Si la validación falla, volver al formulario con errores --------
        if (! $this->validate($reglas, $mensajes)) {
            session()->setFlashdata('old', $this->request->getPost());
            session()->setFlashdata('errores', $this->validator->getErrors());

            return redirect()->to(site_url('autentificar/registro'));
        }

        // --- Verificar que el correo no esté ya registrado -------------------
        if ((new PersonaModel())->emailExiste($this->request->getPost('email'))) {
            session()->setFlashdata('old', $this->request->getPost());
            session()->setFlashdata('errores', ['email' => 'Este correo ya está registrado.']);

            return redirect()->to(site_url('autentificar/registro'));
        }

        // --- Guardar datos en sesión para usarlos en el paso 2 ---------------
        session()->set([
            'reg_primer_nombre'    => trim($this->request->getPost('primer_nombre')),
            'reg_segundo_nombre'   => trim($this->request->getPost('segundo_nombre')),
            'reg_primer_apellido'  => trim($this->request->getPost('primer_apellido')),
            'reg_segundo_apellido' => trim($this->request->getPost('segundo_apellido')),
            'reg_email'            => strtolower(trim($this->request->getPost('email'))),
            'reg_telefono'         => preg_replace('/\s+/', '', $this->request->getPost('telefono')),
            'reg_password'         => $this->request->getPost('password'),
            'reg_timestamp'        => time(),
        ]);

        return redirect()->to(site_url('autentificar/rol'));
    }

    // =========================================================================
    // PASO 2 — Mostrar vista de selección de rol
    // =========================================================================

    public function rol_p(): string|RedirectResponse
    {
        if (! session()->has('reg_email')) {
            return redirect()->to(site_url('autentificar/registro'))
                             ->with('error_general', 'Completa primero el paso 1.');
        }

        if ((time() - session()->get('reg_timestamp')) > 3800) {
            $this->limpiarSesionRegistro();

            return redirect()->to(site_url('autentificar/registro'))
                             ->with('error_general', 'La sesión expiró. Por favor inicia de nuevo.');
        }

        return view('Autentificar/rol', [
            'titulo_pagina' => 'ESTABLECER ROL',
            'errores'       => session()->getFlashdata('errores_rol') ?? [],
        ]);
    }

    // =========================================================================
    // PASO 2 — Procesar rol, guardar en BD e iniciar sesión automáticamente
    // =========================================================================

    public function procesarRol(): RedirectResponse
    {
        // Si no hay datos del paso 1, regresar al inicio
        if (! session()->has('reg_email')) {
            return redirect()->to(site_url('autentificar/registro'))
                             ->with('error_general', 'Sesión no encontrada. Inicia el proceso de nuevo.');
        }

        // Validar que se haya seleccionado un rol permitido
        $rolSeleccionado = (int) $this->request->getPost('rol');

        if (! in_array($rolSeleccionado, [self::ROL_PROPIETARIO, self::ROL_MECANICO], true)) {
            session()->setFlashdata('errores_rol', ['rol' => 'Debes seleccionar un rol para continuar.']);

            return redirect()->to(site_url('autentificar/rol'));
        }

        // Recuperar datos guardados en sesión en el paso 1
        $telefono = session()->get('reg_telefono');

        // Hashear la contraseña justo antes de insertar (mínimo tiempo en memoria)
        $passwordHash = password_hash(session()->get('reg_password'), PASSWORD_DEFAULT, ['cost' => 12]);

        // Conectar a la BD e iniciar transacción
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Insertar en personas
            (new PersonaModel())->insert([
                'primer_nombre'    => session()->get('reg_primer_nombre'),
                'segundo_nombre'   => session()->get('reg_segundo_nombre') ?: null,
                'primer_apellido'  => session()->get('reg_primer_apellido'),
                'segundo_apellido' => session()->get('reg_segundo_apellido'),
                'email'            => session()->get('reg_email'),
                'password_hash'    => $passwordHash,
                'avatarcolor'      => '#000000',
            ]);

            $idPersona = $db->insertID();

            // 2. Relacionar persona con su rol
            (new RolHasPersonaModel())->insert([
                'roles_id_rol'        => $rolSeleccionado,
                'personas_id_persona' => $idPersona,
            ]);

            // 3. Insertar en la tabla específica según el rol elegido
            if ($rolSeleccionado === self::ROL_PROPIETARIO) {
                (new PropietarioModel())->insert([
                    'id_propietario'       => $idPersona,
                    'telefono_propietario' => (int) $telefono,
                    'numero_licencia'      => null,
                ]);
            } else {
                (new MecanicoModel())->insert([
                    'id_mecanico'       => $idPersona,
                    'telefono_mecanico' => (int) $telefono,
                ]);
            }

        } catch (\Exception $e) {
            $db->transRollback();

            return redirect()->to(site_url('autentificar/registro'))
                             ->with('error_general', 'Error al registrar. Por favor intenta más tarde.');
        }

        $db->transComplete();

        // Si la transacción falló silenciosamente
        if ($db->transStatus() === false) {
            return redirect()->to(site_url('autentificar/registro'))
                             ->with('error_general', 'Error al completar el registro. Intenta de nuevo.');
        }

        // --- Capturar los datos necesarios ANTES de limpiar la sesión temporal --
        // limpiarSesionRegistro() borra todas las claves reg_*, por eso
        // extraemos aquí lo que necesitamos para construir la sesión de usuario.
     
     $primerNombre    = session()->get('reg_primer_nombre');
$primerApellido  = session()->get('reg_primer_apellido');
$segundoApellido = session()->get('reg_segundo_apellido');
$emailUsuario    = session()->get('reg_email');

$this->limpiarSesionRegistro(); // una sola vez, aquí

$persona = [
    'id_persona'       => $idPersona,
    'primer_nombre'    => $primerNombre,
    'primer_apellido'  => $primerApellido,
    'segundo_apellido' => $segundoApellido,
    'email'            => $emailUsuario,
    'avatarcolor'      => '#000000',
];

$rutaPanel = Ingresar::establecerSesion($persona, $rolSeleccionado);

        if ($rutaPanel === null) {
            // Situación anómala: rol insertado pero no mapeado en PANELES.
            // No destruimos la sesión porque el usuario ya está registrado;
            // lo enviamos al login para que acceda normalmente.
            return redirect()->to(site_url('autentificar/ingreso'))
                             ->with('exito', '¡Cuenta creada! Inicia sesión para continuar.');
        }

        // Redirigir directamente al panel con mensaje de bienvenida.
        // Usamos $primerNombre (capturado antes de limpiar) en lugar de
        // session()->get('usuario_nombre') para no depender del orden
        // en que CI4 escribe y lee la sesión en la misma request.
        return redirect()->to(site_url($rutaPanel));
    }

    // =========================================================================
    // MÉTODO PRIVADO — Limpiar variables de sesión del registro
    // =========================================================================

    private function limpiarSesionRegistro(): void
    {
        session()->remove([
            'reg_primer_nombre',
            'reg_segundo_nombre',
            'reg_primer_apellido',
            'reg_segundo_apellido',
            'reg_email',
            'reg_password',
            'reg_telefono',
            'reg_timestamp',
        ]);
    }
}