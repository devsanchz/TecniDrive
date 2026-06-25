<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    // ── Tabla y clave primaria ────────────────────────────────────────────
    protected $table      = 'personas';
    protected $primaryKey = 'id_persona';

    // ── Campos permitidos para inserción/actualización (whitelist) ────────
    // Protege contra mass-assignment accidental
    protected $allowedFields = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'password_hash',
        'avatarcolor',
    ];

    // ── Tipo de retorno ───────────────────────────────────────────────────
    protected $returnType = 'array';

    // ── Timestamps automáticos ────────────────────────────────────────────
    // fecha_registro tiene DEFAULT CURRENT_TIMESTAMP en el schema,
    // no necesitamos que el modelo la gestione manualmente
    protected $useTimestamps = false;

   
    // ── Método auxiliar: verificar email duplicado ────────────────────────
    // Se llama en el paso 1 para advertir al usuario antes de llegar al paso 2
    public function emailExiste(string $email): bool
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }
}