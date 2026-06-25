<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table      = 'servicios';
    protected $primaryKey = 'id_servicio';

    // Con AUTO_INCREMENT ya no necesitamos pasar el id manualmente
    protected $allowedFields = ['nombre_servicio'];
    protected $useTimestamps = false;
}