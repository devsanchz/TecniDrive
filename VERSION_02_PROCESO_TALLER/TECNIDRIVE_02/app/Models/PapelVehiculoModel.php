<?php

namespace App\Models;

use CodeIgniter\Model;

class PapelVehiculoModel extends Model
{
    protected $table      = 'papeles_vehiculo';
    protected $primaryKey = 'id_papel';

    protected $allowedFields = [
        'fecha_vencimiento',
        'estado_papel',
        'tipo_documento_id_documento',
        'vehiculo_placa',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    // ── Obtener documentos de un vehículo ─────────────────────────────────
    public function obtenerPorVehiculo(string $placa): array
    {
        return $this->where('vehiculo_placa', $placa)->findAll();
    }

    // ── Obtener documentos de varios vehículos, agrupados por placa ──────
    public function obtenerPorPlacas(array $placas): array
    {
        if (empty($placas)) {
            return [];
        }

        $filas = $this->whereIn('vehiculo_placa', $placas)->findAll();

        $agrupado = [];
        foreach ($filas as $fila) {
            $agrupado[$fila['vehiculo_placa']][$fila['tipo_documento_id_documento']] = $fila;
        }

        return $agrupado;
    }

    // ── Guardar o actualizar un documento (SOAT=1, Tecnomecánica=2) ───────
    public function guardarDocumento(string $placa, int $idDocumento, string $fechaVencimiento): bool
    {
        $existente = $this->where('vehiculo_placa', $placa)
                          ->where('tipo_documento_id_documento', $idDocumento)
                          ->first();

        // Vigente si la fecha de vencimiento es hoy o futura
        $estado = ($fechaVencimiento >= date('Y-m-d')) ? 1 : 0;

        if ($existente) {
            return $this->update($existente['id_papel'], [
                'fecha_vencimiento' => $fechaVencimiento,
                'estado_papel'      => $estado,
            ]);
        }

        return (bool) $this->insert([
            'fecha_vencimiento'           => $fechaVencimiento,
            'estado_papel'                => $estado,
            'tipo_documento_id_documento' => $idDocumento,
            'vehiculo_placa'              => $placa,
        ]);
    }
}