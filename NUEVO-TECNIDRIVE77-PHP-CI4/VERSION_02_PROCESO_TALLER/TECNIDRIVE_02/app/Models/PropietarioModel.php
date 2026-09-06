<?php

namespace App\Models;

use CodeIgniter\Model;

class PropietarioModel extends Model
{
    protected $table      = 'propietarios';
    protected $primaryKey = 'id_propietario';

    protected $allowedFields = [
        'id_propietario',
        'telefono_propietario',
        'numero_licencia',
    ];

    protected $useTimestamps = false;

    //verificar si el propietario ya tiene licencia registrada 
    public function tieneLicencia(int $idPropietario): bool
    {
        $row = $this->find($idPropietario);
        return $row && !empty($row['numero_licencia']);
    }
}