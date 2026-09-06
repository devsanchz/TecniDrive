<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    // ── Tabla y clave primaria ────────────────────────────────────────────
    protected $table      = 'personas';
    protected $primaryKey = 'id_persona';

    // ── Campos permitidos para inserción/actualización (whitelist) ────────
    protected $allowedFields = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'password_hash',
        'avatarcolor',
        'codigo_recuperacion',  // ← NUEVO: código de 6 dígitos
        'fecha_expiracion',     // ← NUEVO: ventana de 5 minutos
    ];

    // ── Tipo de retorno ───────────────────────────────────────────────────
    protected $returnType = 'array';

    // ── Timestamps automáticos ────────────────────────────────────────────
    // fecha_registro tiene DEFAULT CURRENT_TIMESTAMP en el schema
    protected $useTimestamps = false;

    // ── Método auxiliar: verificar email duplicado ────────────────────────
    public function emailExiste(string $email): bool
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    // ── Método auxiliar: buscar persona por email ─────────────────────────
    public function buscarPorEmail(string $email): array|null
    {
        return $this->where('email', $email)->first();
    }
}