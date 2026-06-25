<?php

namespace App\Models;

use CodeIgniter\Model;

class VehiculoModel extends Model
{
    protected $table      = 'vehiculo';
    protected $primaryKey = 'placa';

    protected $allowedFields = [
        'placa',
        'model_year',
        'estado_vehi',
        'motivo_estado',
        'propietarios_id_propietario',
        'tipos_vehiculo_id_tipo_vehi',
        'modelos_id_modelo',
        'servicio_vehiculo_id_tipo_servicio',
    ];

    protected $useTimestamps = false;

    // ── Vehículos de un propietario (con marca y modelo) ────────────────────
    public function obtenerPorPropietario(int $idPropietario): array
    {
        $vehiculos = $this->select('vehiculo.*, mo.nombre_modelo, ma.nombre_marca')
            ->join('modelos mo', 'mo.id_modelo = vehiculo.modelos_id_modelo')
            ->join('marcas ma',  'ma.id_marca  = mo.marcas_id_marca')
            ->where('propietarios_id_propietario', $idPropietario)
            ->findAll();

        // ── Marcar cuáles tienen cita activa ──────────────────────────────────
        foreach ($vehiculos as &$v) {
            $v['tiene_cita_activa'] = $this->tienesCitaActiva($v['placa']);
        }
        unset($v);

        return $vehiculos;
    }

    // ── Activar vehículo (limpia el motivo de la desactivación anterior) ────
    public function activar(string $placa): bool
    {
        return $this->update($placa, [
            'estado_vehi'   => 1,
            'motivo_estado' => null,
        ]);
    }

    // ── Desactivar vehículo con motivo ──────────────────────────────────────
    public function desactivar(string $placa, string $motivo): bool
    {
        return $this->update($placa, [
            'estado_vehi'   => 0,
            'motivo_estado' => $motivo,
        ]);
    }

    // ── Verificar si un vehículo tiene cita activa ────────────────────────────
    public function tienesCitaActiva(string $placa): bool
    {
        return $this->db->table('cita_taller')
            ->whereIn('estado_cita', ['pendiente', 'confirmada', 'en_atencion'])
            ->where('vehiculo_placa', $placa)
            ->countAllResults() > 0;
    }

    // ── NUEVO: Vehículos para el reporte PDF de administrador ──────────────
    // $idsTipo: IDs de tipos_vehiculo (1 = Carro, 2 = Moto) ya resueltos por el controlador.
    // $fechaCorte: fecha mínima de registro.
    //
    // NOTA: la relación con la persona propietaria es directa, porque
    // propietarios.id_propietario ES la misma PK que personas.id_persona
    // (herencia 1 a 1, igual que taller.mecanicos_id_mecanico → mecanicos.id_mecanico).
    public function obtenerParaReporte(array $idsTipo, string $fechaCorte): array
    {
        return $this->select('vehiculo.placa,
                               vehiculo.tipos_vehiculo_id_tipo_vehi,
                               vehiculo.fecha_registro,
                               personas.primer_nombre,
                               personas.primer_apellido')
            ->join('personas', 'personas.id_persona = vehiculo.propietarios_id_propietario')
            ->whereIn('vehiculo.tipos_vehiculo_id_tipo_vehi', $idsTipo)
            ->where('vehiculo.fecha_registro >=', $fechaCorte)
            ->orderBy('vehiculo.fecha_registro', 'DESC')
            ->findAll();
    }
}