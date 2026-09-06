<?php

namespace App\Models;

use CodeIgniter\Model;

class TallerServicioModel extends Model
{
    protected $table      = 'taller_has_servicios';
    protected $primaryKey = 'taller_id_taller';

    protected $allowedFields = [
        'taller_id_taller',
        'servicios_id_servicio',
        'precio_servicio',
    ];

    protected $useTimestamps = false;

    // Obtener servicios con precio de un taller específico
    public function obtenerPorTaller(int $idTaller): array
    {
        return $this->db->table('taller_has_servicios ths')
            ->select('s.nombre_servicio, ths.precio_servicio, ths.servicios_id_servicio')
            ->join('servicios s', 's.id_servicio = ths.servicios_id_servicio')
            ->where('ths.taller_id_taller', $idTaller)
            ->get()
            ->getResultArray();
    }
}