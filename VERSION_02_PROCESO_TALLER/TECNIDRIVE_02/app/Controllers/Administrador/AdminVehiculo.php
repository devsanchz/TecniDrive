<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\PapelVehiculoModel;

class AdminVehiculo extends BaseController
{
    // =========================================================================
    // GET administrador/vehiculo
    //
    // Sesión y rol (administrador = id_rol 1) ya verificados por AuthFilter
    // ('auth:1' en el grupo 'administrador' de Routes.php).
    // =========================================================================

    public function vehiculo(): string
    {
        $db = \Config\Database::connect();

        // Traer todos los vehículos con:
        //   - marca y modelo  → joins con modelos y marcas
        //   - propietario     → join con propietarios → personas (primer_nombre, primer_apellido, email)
        $vehiculos = $db->table('vehiculo v')
            ->select('
                v.placa,
                v.model_year,
                v.fecha_registro,
                v.estado_vehi,
                v.motivo_estado,
                v.propietarios_id_propietario,
                v.tipos_vehiculo_id_tipo_vehi,
                v.servicio_vehiculo_id_tipo_servicio,
                mo.nombre_modelo,
                ma.nombre_marca,
                p.primer_nombre,
                p.primer_apellido,
                p.email,
                p.avatarcolor
            ')
            ->join('modelos mo',      'mo.id_modelo  = v.modelos_id_modelo')
            ->join('marcas ma',       'ma.id_marca   = mo.marcas_id_marca')
            ->join('propietarios pr', 'pr.id_propietario = v.propietarios_id_propietario')
            ->join('personas p',      'p.id_persona  = pr.id_propietario')
            ->orderBy('v.placa', 'ASC')
            ->get()
            ->getResultArray();

        // Documentos agrupados por placa: [placa => [1 => soat, 2 => tecno]]
        $placas     = array_column($vehiculos, 'placa');
        $papelModel = new PapelVehiculoModel();
        $documentos = $papelModel->obtenerPorPlacas($placas);

        // Contadores para las tarjetas superiores
        $total         = count($vehiculos);
        $papelCompleto = 0;

        foreach ($vehiculos as $v) {
            $docs  = $documentos[$v['placa']] ?? [];
            $soat  = $docs[1] ?? null;
            $tecno = $docs[2] ?? null;

            // "Papeles completos" = ambos existen y están vigentes
            if ($soat && $tecno
                && $soat['estado_papel']
                && $tecno['estado_papel']) {
                $papelCompleto++;
            }
        }

        return view('Administrador/admin_vehiculo', [
            'titulo_pagina'  => 'ADMIN-VEHICULOS',
            'vehiculos'      => $vehiculos,
            'documentos'     => $documentos,
            'total'          => $total,
            'papel_completo' => $papelCompleto,
        ]);
    }
}