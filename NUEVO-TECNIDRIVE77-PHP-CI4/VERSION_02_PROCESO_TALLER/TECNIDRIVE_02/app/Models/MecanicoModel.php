<?php

namespace App\Models;

use CodeIgniter\Model;

class MecanicoModel extends Model
{
    protected $table      = 'mecanicos';
    protected $primaryKey = 'id_mecanico';

    // Igual que propietarios: id_mecanico es FK manual, no auto_increment
    protected $allowedFields = [
        'id_mecanico',
        'telefono_mecanico',
    ];

    protected $useTimestamps = false;
}