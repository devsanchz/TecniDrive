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
        'codigo_recuperacion',  // 6 dígitos
        'fecha_expiracion',   // 5 minutos
    ];
    protected $returnType = 'array';

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