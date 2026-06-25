<?php

namespace App\Models;

use CodeIgniter\Model;

class TallerModel extends Model
{
    protected $table      = 'taller';
    protected $primaryKey = 'id_taller';

    protected $allowedFields = [
        'foto_taller',
        'nombre_taller',
        'descripcion_taller',
        'direccion_taller',
        'horario_taller',
        'estado_taller',
        'motivo_estado',
        'mecanicos_id_mecanico',
    ];

    protected $useTimestamps = false;

    // Buscar el taller de un mecánico específico
    public function buscarPorMecanico(int $idMecanico): array|null
    {
        return $this->where('mecanicos_id_mecanico', $idMecanico)->first();
    }
}