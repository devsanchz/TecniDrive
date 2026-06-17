<?php

namespace App\Models;

use CodeIgniter\Model;

class ModeloModel extends Model
{
    protected $table      = 'modelos';
    protected $primaryKey = 'id_modelo';

    protected $allowedFields = [
        'nombre_modelo',
        'marcas_id_marca',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    // ── Obtener el id de un modelo (para una marca); si no existe, lo crea ─
    public function obtenerOCrear(string $nombreModelo, int $idMarca): int
    {
        $nombreModelo = trim($nombreModelo);

        $modelo = $this->where('nombre_modelo', $nombreModelo)
                       ->where('marcas_id_marca', $idMarca)
                       ->first();

        if ($modelo) {
            return (int) $modelo['id_modelo'];
        }

        $this->insert([
            'nombre_modelo'   => $nombreModelo,
            'marcas_id_marca' => $idMarca,
        ]);

        return (int) $this->db->insertID();
    }
}