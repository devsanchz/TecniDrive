<?php

namespace App\Models;

use CodeIgniter\Model;

class MarcaModel extends Model
{
    protected $table      = 'marcas';
    protected $primaryKey = 'id_marca';

    protected $allowedFields = [
        'nombre_marca',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    // ── Obtener el id de una marca; si no existe, la crea ─────────────────
    public function obtenerOCrear(string $nombreMarca): int
    {
        $nombreMarca = trim($nombreMarca);

        $marca = $this->where('nombre_marca', $nombreMarca)->first();

        if ($marca) {
            return (int) $marca['id_marca'];
        }

        $this->insert(['nombre_marca' => $nombreMarca]);

        return (int) $this->db->insertID();
    }
}