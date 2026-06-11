<?php

namespace App\Models;

use CodeIgniter\Model;

class PropietarioModel extends Model
{
    protected $table      = 'propietarios';
    protected $primaryKey = 'id_propietario';

    // id_propietario NO es auto_increment (es FK = id_persona),
    // por eso se incluye en allowedFields para poder insertarlo manualmente
    protected $allowedFields = [
        'id_propietario',
        'telefono_propietario',
        'numero_licencia',
    ];

    protected $useTimestamps = false;
}