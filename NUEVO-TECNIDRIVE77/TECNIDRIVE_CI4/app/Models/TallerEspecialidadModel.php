<?php

namespace App\Models;

use CodeIgniter\Model;

class TallerEspecialidadModel extends Model
{
    protected $table      = 'taller_especialidad';
    protected $primaryKey = 'taller_id_taller';

    protected $allowedFields = [
        'taller_id_taller',
        'especialidades_id_especialidad',
    ];

    protected $useTimestamps = false;

    // Obtener especialidades de un taller con su nombre
    public function obtenerPorTaller(int $idTaller): array
    {
        return $this->db->table('taller_especialidad te')
            ->select('e.nombre_especialidad, te.especialidades_id_especialidad')
            ->join('especialidades e', 'e.id_especialidad = te.especialidades_id_especialidad')
            ->where('te.taller_id_taller', $idTaller)
            ->get()
            ->getResultArray();
    }
}