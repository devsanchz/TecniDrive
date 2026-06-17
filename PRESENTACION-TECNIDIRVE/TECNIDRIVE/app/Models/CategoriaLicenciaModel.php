<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaLicenciaModel extends Model
{
    protected $table      = 'categoria_licencia';
    protected $primaryKey = 'id_categoria';

    protected $allowedFields = ['id_categoria', 'tipo_categoria'];
    protected $useTimestamps = false;

    // ── Todas las categorías disponibles para el <select> ─────────────────
    public function obtenerTodas(): array
    {
        return $this->findAll();
    }
}