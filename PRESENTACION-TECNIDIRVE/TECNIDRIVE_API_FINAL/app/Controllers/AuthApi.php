<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Config\JWT as JWTConfig;


class AuthApi extends ResourceController
{
    protected $format = 'json';

  // POST /api/v1/registro
    public function registro()
    {
        $data = $this->request->getJSON(true);

        // ── Validar que llegaron datos 
        if (!$data) {
            return $this->fail('No se recibieron datos JSON', 400);
        }

        // ── Reglas de validación (CI4 nativo) 
        $reglas = [
            'primer_nombre'    => 'required|min_length[2]|max_length[30]',
            'segundo_nombre'   => 'permit_empty|max_length[30]',
            'primer_apellido'  => 'required|min_length[2]|max_length[25]',
            'segundo_apellido' => 'required|min_length[2]|max_length[25]',
            'email'            => 'required|valid_email|max_length[60]',
            'password'         => 'required|min_length[8]',
            'telefono'         => 'required|numeric|min_length[7]|max_length[10]',
            'rol'              => 'required|in_list[2,3]',
        ];

        $mensajes = [
            'primer_nombre'    => ['required' => 'El primer nombre es obligatorio'],
            'primer_apellido'  => ['required' => 'El primer apellido es obligatorio'],
            'segundo_apellido' => ['required' => 'El segundo apellido es obligatorio'],
            'email'            => [
                'required'    => 'El email es obligatorio',
                'valid_email' => 'El email no tiene formato válido',
            ],
            'password'         => [
                'required'    => 'La contraseña es obligatoria',
                'min_length'  => 'Mínimo 8 caracteres',
            ],
            'telefono'         => [
                'required' => 'El teléfono es obligatorio',
                'numeric'  => 'Solo números, sin prefijo internacional',
            ],
            'rol'              => ['in_list' => 'Rol inválido. Usa 2 (Propietario) o 3 (Mecánico)'],
        ];

        if (!$this->validate($reglas, $mensajes)) {
            // Devuelve todos los errores de campo juntos
            return $this->fail($this->validator->getErrors(), 422);
        }

        $modelo = new PersonaModel();

        // ── Verificar email duplicado ──────────────────────────────────
        if ($modelo->buscarPorEmail($data['email'])) {
            return $this->fail(['email' => 'Este email ya está registrado'], 409);
        }

        // ── Preparar datos para insertar ───────────────────────────────
        $persona = [
            'primer_nombre'    => $data['primer_nombre'],
            'segundo_nombre'   => $data['segundo_nombre'] ?? null,
            'primer_apellido'  => $data['primer_apellido'],
            'segundo_apellido' => $data['segundo_apellido'],
            'email'            => $data['email'],
            // Hashear contraseña — NUNCA guardar en texto plano
            'password_hash'    => password_hash($data['password'], PASSWORD_DEFAULT),
        ];

        $rol      = (int) $data['rol'];
        $telefono = (int) $data['telefono'];

        // ── Insertar persona + rol + tabla específica (transacción) ────
        $idPersona = $modelo->registrarConRol($persona, $rol, $telefono);

        if (!$idPersona) {
            return $this->failServerError('Error al crear la cuenta. Intenta de nuevo.');
        }

        // ── Generar JWT con los datos del nuevo usuario ────────────────
        $token = $this->_generarToken($idPersona, $data['primer_nombre'], $rol);

        // ── Respuesta 201 Created ──────────────────────────────────────
        return $this->respondCreated([
            'mensaje' => 'Cuenta creada exitosamente',
            'token'   => $token,
            'usuario' => [
                'id'     => $idPersona,
                'nombre' => $data['primer_nombre'] . ' ' . $data['primer_apellido'],
                'email'  => $data['email'],
                'rol'    => $rol,
            ],
        ]);
    }

   
    // POST /api/v1/login
   
    public function login()
    {
        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->fail('No se recibieron datos JSON', 400);
        }

        // ── Validación básica ──────────────────────────────────────────
        $reglas = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($reglas)) {
            return $this->fail($this->validator->getErrors(), 422);
        }

        $modelo  = new PersonaModel();
        $persona = $modelo->buscarPorEmail($data['email']);

        // ── Mensajes genéricos para no revelar si el email existe ──────
        if (!$persona || !password_verify($data['password'], $persona['password_hash'])) {
            return $this->failUnauthorized('Credenciales incorrectas');
        }

        // ── Obtener rol activo del usuario desde la tabla pivote ───────
        $db  = \Config\Database::connect();
        $rol = $db->table('roles_has_persona')
                  ->where('personas_id_persona', $persona['id_persona'])
                  ->get()
                  ->getRowArray();

        $rolId = $rol['roles_id_rol'] ?? null;

        // ── Generar token ──────────────────────────────────────────────
        $token = $this->_generarToken(
            $persona['id_persona'],
            $persona['primer_nombre'],
            $rolId
        );

        return $this->respond([
            'token'   => $token,
            'usuario' => [
                'id'     => $persona['id_persona'],
                'nombre' => $persona['primer_nombre'] . ' ' . $persona['primer_apellido'],
                'email'  => $persona['email'],
                'rol'    => $rolId,
            ],
        ]);
    }

    // PRIVADO: genera el JWT — reutilizado en registro y login
    private function _generarToken(int $uid, string $nombre, ?int $rol): string
    {
        $payload = [
            'iat'    => time(),                          // emitido en
            'exp'    => time() + JWTConfig::$expireTime, // expira en
            'uid'    => $uid,
            'nombre' => $nombre,
            'rol'    => $rol,
        ];

        return JWT::encode($payload, JWTConfig::$key, JWTConfig::$algorithm);
    }
}