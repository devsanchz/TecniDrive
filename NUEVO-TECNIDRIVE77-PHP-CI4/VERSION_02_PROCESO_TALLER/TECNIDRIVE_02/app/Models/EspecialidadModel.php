<?php

namespace App\Models;

use CodeIgniter\Model;

class EspecialidadModel extends Model
{
    protected $table      = 'especialidades';
    protected $primaryKey = 'id_especialidad';

    protected $allowedFields = ['nombre_especialidad'];
    protected $useTimestamps = false;
}