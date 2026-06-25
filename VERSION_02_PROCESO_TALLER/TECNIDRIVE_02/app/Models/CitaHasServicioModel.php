<?php

namespace App\Models;

use CodeIgniter\Model;

class CitaHasServicioModel extends Model
{
    protected $table      = 'cita_has_servicio';
    protected $primaryKey = 'cita_taller_id_cita';

    protected $allowedFields = [
        'cita_taller_id_cita',
        'taller_id_taller',
        'servicios_id_servicio',
    ];

    protected $useTimestamps = false;
}