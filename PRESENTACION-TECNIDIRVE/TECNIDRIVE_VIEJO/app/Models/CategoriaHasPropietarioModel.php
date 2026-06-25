<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaHasPropietarioModel extends Model
{
    protected $table      = 'categoria_has_propietario';
    protected $primaryKey = 'categoria_licencia_id_categoria';

    protected $allowedFields = [
        'categoria_licencia_id_categoria',
        'propietarios_id_propietario',
        'vigencia_lice',
        'estado_lice',
    ];

    protected $useTimestamps = false;

    // ── Categorías de un propietario con su nombre ────────────────────────
    public function obtenerPorPropietario(int $idPropietario): array
    {
        return $this->db->table('categoria_has_propietario chp')
            ->select('cl.tipo_categoria, chp.vigencia_lice, chp.estado_lice')
            ->join('categoria_licencia cl', 'cl.id_categoria = chp.categoria_licencia_id_categoria')
            ->where('chp.propietarios_id_propietario', $idPropietario)
            ->get()
            ->getResultArray();
    }
}