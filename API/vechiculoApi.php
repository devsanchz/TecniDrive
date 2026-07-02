<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\VehiculoModel;

class VehiculoApi extends BaseController
{
    public function listar($idPropietario)
    {
        $vehiculoModel = new VehiculoModel();

        $vehiculos = $vehiculoModel
            ->getVehiculosPorPropietario($idPropietario);

        return $this->response->setJSON($vehiculos);
    }
}