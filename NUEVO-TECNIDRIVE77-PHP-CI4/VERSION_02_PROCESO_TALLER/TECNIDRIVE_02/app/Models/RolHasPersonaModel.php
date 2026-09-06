<?php

namespace App\Models;

use CodeIgniter\Model;

class RolHasPersonaModel extends Model
{
    //Tabla intermedia 
    protected $table      = 'roles_has_persona';
    protected $primaryKey = 'roles_id_rol'; 

    protected $allowedFields = [
        'roles_id_rol',
        'personas_id_persona',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;
}