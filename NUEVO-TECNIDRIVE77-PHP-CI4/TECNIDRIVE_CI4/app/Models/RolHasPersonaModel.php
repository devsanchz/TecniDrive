<?php

namespace App\Models;

use CodeIgniter\Model;

class RolHasPersonaModel extends Model
{
    // ── Tabla intermedia N:M ──────────────────────────────────────────────
    protected $table      = 'roles_has_persona';
    protected $primaryKey = 'roles_id_rol'; // CI4 lo necesita declarado

    protected $allowedFields = [
        'roles_id_rol',
        'personas_id_persona',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;
}