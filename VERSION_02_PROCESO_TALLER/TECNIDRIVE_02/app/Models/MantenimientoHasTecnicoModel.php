<?php

namespace App\Models;

use CodeIgniter\Model;

class MantenimientoHasTecnicoModel extends Model
{
    protected $table      = 'mantenimiento_has_tecnico';
    protected $primaryKey = 'gestion_mantenimiento_id_seguimiento';

    protected $allowedFields = [
        'gestion_mantenimiento_id_seguimiento',
        'tecnicos_id_tecnico',
    ];

    protected $useTimestamps = false;

    // ── Asignar varios técnicos a una ficha de mantenimiento ──────────────
    // Borra asignaciones previas e inserta las nuevas (simple y sin duplicados)
    public function asignarTecnicos(int $idSeguimiento, array $idsTecnico): void
    {
        $this->where('gestion_mantenimiento_id_seguimiento', $idSeguimiento)->delete();

        foreach ($idsTecnico as $idTecnico) {
            $this->insert([
                'gestion_mantenimiento_id_seguimiento' => $idSeguimiento,
                'tecnicos_id_tecnico'                  => (int) $idTecnico,
            ]);
        }
    }

    // ── Obtener técnicos asignados a una ficha (con nombre) ───────────────
    public function obtenerPorSeguimiento(int $idSeguimiento): array
    {
        return $this->db->table('mantenimiento_has_tecnico mht')
            ->select('t.id_tecnico, t.nombre_tecnico')
            ->join('tecnicos t', 't.id_tecnico = mht.tecnicos_id_tecnico')
            ->where('mht.gestion_mantenimiento_id_seguimiento', $idSeguimiento)
            ->get()
            ->getResultArray();
    }
}