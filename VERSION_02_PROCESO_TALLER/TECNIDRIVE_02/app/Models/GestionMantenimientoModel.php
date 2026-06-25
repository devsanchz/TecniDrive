<?php

namespace App\Models;

use CodeIgniter\Model;

class GestionMantenimientoModel extends Model
{
    protected $table      = 'gestion_mantenimiento';
    protected $primaryKey = 'id_seguimiento';

    protected $allowedFields = [
        'cita_taller_id_cita',
        'observaciones_tecnico',
        'precio_total',
        'garantia_vigencia',
        'texto_garantia',
        'estado_mantenimiento',
        'codigo_entrega',
        'fecha_cierre',
    ];

    protected $useTimestamps = false;

    // ── Buscar la ficha de gestión por id de cita ─────────────────────────
    public function buscarPorCita(int $idCita): array|null
    {
        return $this->where('cita_taller_id_cita', $idCita)->first();
    }
}