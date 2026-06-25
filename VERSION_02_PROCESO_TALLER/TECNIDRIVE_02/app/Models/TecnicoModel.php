<?php

namespace App\Models;

use CodeIgniter\Model;

class TecnicoModel extends Model
{
    protected $table      = 'tecnicos';
    protected $primaryKey = 'id_tecnico';

    protected $allowedFields = [
        'taller_id_taller',
        'nombre_tecnico',
    ];

    protected $useTimestamps = false;

    // ── Técnicos de un taller específico ──────────────────────────────────
    public function obtenerPorTaller(int $idTaller): array
    {
        return $this->where('taller_id_taller', $idTaller)
                    ->orderBy('nombre_tecnico', 'ASC')
                    ->findAll();
    }

    // ── Verificar que un técnico pertenece a un taller (seguridad) ────────
    public function perteneceATaller(int $idTecnico, int $idTaller): bool
    {
        return $this->where('id_tecnico', $idTecnico)
                    ->where('taller_id_taller', $idTaller)
                    ->countAllResults() > 0;
    }
}