<?php
// app/Models/PersonaModel.php
namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    // ── Tabla principal ────────────────────────────────────────────────
    protected $table      = 'personas';
    protected $primaryKey = 'id_persona';

    // ── Campos permitidos para insert/update ───────────────────────────
    protected $allowedFields = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'password_hash',
        'avatarcolor',
    ];

    // ── Timestamps manuales (la BD ya tiene DEFAULT CURRENT_TIMESTAMP) ─
    protected $useTimestamps = false;

    // ── Retornar arrays (más simple que objetos para la API) ───────────
    protected $returnType = 'array';

    // ────────────────────────────────────────────────────────────────────
    // MÉTODO: buscar persona por email (para login y validar duplicados)
    // Reutilizable en login, registro y recuperación de contraseña
    // ────────────────────────────────────────────────────────────────────
    public function buscarPorEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    // ────────────────────────────────────────────────────────────────────
    // MÉTODO: registrar persona completa con rol en una transacción
    // Parámetros:
    //   $persona  → datos de la tabla personas
    //   $rol      → 2 (Propietario) | 3 (Mecánico)
    //   $telefono → bigint, va a propietarios o mecanicos según rol
    // ────────────────────────────────────────────────────────────────────
    public function registrarConRol(array $persona, int $rol, int $telefono): int|false
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Insertar en personas
        $this->insert($persona);
        $idPersona = $this->getInsertID();

        // 2. Asignar rol en tabla pivote
        $db->table('roles_has_persona')->insert([
            'roles_id_rol'        => $rol,
            'personas_id_persona' => $idPersona,
        ]);

        // 3. Insertar en tabla específica según rol
        if ($rol === 2) {
            // Propietario (numero_licencia es opcional, puede ser null)
            $db->table('propietarios')->insert([
                'id_propietario'      => $idPersona,
                'telefono_propietario'=> $telefono,
                'numero_licencia'     => null,
            ]);
        } elseif ($rol === 3) {
            $db->table('mecanicos')->insert([
                'id_mecanico'    => $idPersona,
                'telefono_mecanico' => $telefono,
            ]);
        }

        $db->transComplete();

        // transStatus() devuelve false si algún paso falló y hizo rollback
        return $db->transStatus() ? $idPersona : false;
    }
}