<?php

namespace App\Models;

use CodeIgniter\Model;

class CitaTallerModel extends Model
{
    protected $table      = 'cita_taller';
    protected $primaryKey = 'id_cita';

    protected $allowedFields = [
        'fecha_cita',
        'problema_contexto',
        'estado_cita',
        'taller_id_taller',
        'vehiculo_placa',
        // Campos de control de flujo de cita
        'codigo_confirmacion',
        'fecha_inicio_atencion',
        'cancelado_por',
        'motivo_cancelacion',
    ];

    protected $useTimestamps = false;

    // ── Citas de un propietario (join con taller) ─────────────────────────
    public function obtenerPorPropietario(int $idPropietario): array
    {
        return $this->db->table('cita_taller ct')
            ->select('ct.*, t.nombre_taller')
            ->join('taller t',   't.id_taller = ct.taller_id_taller')
            ->join('vehiculo v', 'v.placa = ct.vehiculo_placa')
            ->where('v.propietarios_id_propietario', $idPropietario)
            ->orderBy('ct.fecha_cita', 'DESC')
            ->get()
            ->getResultArray();
    }


    // ── Citas completas del propietario con todos los datos necesarios ────────
public function obtenerCompletasPorPropietario(int $idPropietario): array
{
    return $this->db->table('cita_taller ct')
        ->select('
            ct.*,
            t.nombre_taller,
            t.direccion_taller,
            v.placa,
            v.model_year,
            ma.nombre_marca,
            mo.nombre_modelo,
            tv.texto_tipo_vehi,
            p.primer_nombre   AS mecanico_nombre,
            p.primer_apellido AS mecanico_apellido,
            m.telefono_mecanico,
            gm.id_seguimiento,
            gm.observaciones_tecnico,
            gm.precio_total,
            gm.garantia_vigencia,
            gm.texto_garantia,
            gm.estado_mantenimiento,
            gm.codigo_entrega,
            gm.fecha_cierre
        ')
        ->join('taller t',          't.id_taller      = ct.taller_id_taller')
        ->join('mecanicos m',       'm.id_mecanico    = t.mecanicos_id_mecanico')
        ->join('personas p',        'p.id_persona     = m.id_mecanico')
        ->join('vehiculo v',        'v.placa           = ct.vehiculo_placa')
        ->join('modelos mo',        'mo.id_modelo      = v.modelos_id_modelo')
        ->join('marcas ma',         'ma.id_marca       = mo.marcas_id_marca')
        ->join('tipos_vehiculo tv', 'tv.id_tipo_vehi   = v.tipos_vehiculo_id_tipo_vehi')
        ->join('gestion_mantenimiento gm', 'gm.cita_taller_id_cita = ct.id_cita', 'left')
        ->where('v.propietarios_id_propietario', $idPropietario)
        ->orderBy('ct.fecha_registro', 'DESC')
        ->get()
        ->getResultArray();
}

// ── Técnicos asignados a una ficha de gestión (para mostrar al propietario) ──
public function obtenerTecnicosPorSeguimiento(int $idSeguimiento): array
{
    return $this->db->table('mantenimiento_has_tecnico mht')
        ->select('te.nombre_tecnico')
        ->join('tecnicos te', 'te.id_tecnico = mht.tecnicos_id_tecnico')
        ->where('mht.gestion_mantenimiento_id_seguimiento', $idSeguimiento)
        ->get()
        ->getResultArray();
}


// ── Servicios de una cita ─────────────────────────────────────────────────
public function obtenerServicios(int $idCita): array
{
    return $this->db->table('cita_has_servicio chs')
        ->select('s.nombre_servicio, ths.precio_servicio')
        ->join('taller_has_servicios ths',
               'ths.taller_id_taller = chs.taller_id_taller
                AND ths.servicios_id_servicio = chs.servicios_id_servicio')
        ->join('servicios s', 's.id_servicio = chs.servicios_id_servicio')
        ->where('chs.cita_taller_id_cita', $idCita)
        ->get()
        ->getResultArray();
}

// ── Citas en_atencion, en_cierre y finalizadas del taller (para panel de control) ──
public function obtenerEnAtencion(int $idMecanico): array
{
    return $this->db->table('cita_taller ct')
        ->select('
            ct.*,
            t.id_taller,
            v.placa,
            v.model_year,
            ma.nombre_marca,
            mo.nombre_modelo,
            tv.texto_tipo_vehi,
            p.primer_nombre   AS cliente_nombre,
            p.primer_apellido AS cliente_apellido,
            prop.telefono_propietario,
            gm.id_seguimiento,
            gm.observaciones_tecnico,
            gm.precio_total,
            gm.garantia_vigencia,
            gm.texto_garantia,
            gm.estado_mantenimiento,
            gm.codigo_entrega,
            gm.fecha_cierre
        ')
        ->join('taller t',                 't.id_taller        = ct.taller_id_taller')
        ->join('vehiculo v',               'v.placa             = ct.vehiculo_placa')
        ->join('propietarios prop',        'prop.id_propietario = v.propietarios_id_propietario')
        ->join('personas p',               'p.id_persona        = prop.id_propietario')
        ->join('modelos mo',               'mo.id_modelo        = v.modelos_id_modelo')
        ->join('marcas ma',                'ma.id_marca         = mo.marcas_id_marca')
        ->join('tipos_vehiculo tv',        'tv.id_tipo_vehi     = v.tipos_vehiculo_id_tipo_vehi')
        ->join('gestion_mantenimiento gm', 'gm.cita_taller_id_cita = ct.id_cita')
        ->where('t.mecanicos_id_mecanico', $idMecanico)
        ->where('ct.estado_cita', 'en_atencion')
        // ── Citas que se muestran en el panel: en_atencion, en_cierre o finalizada ──
        // estado_cita se mantiene fijo en 'en_atencion' por diseño (ver pro_cita.php);
        // el sub-estado real vive en gestion_mantenimiento.estado_mantenimiento.
        // Se admite NULL por si la ficha aún no trae ese campo seteado explícitamente.
        ->groupStart()
            ->where('gm.estado_mantenimiento', 'en_atencion')
            ->orWhere('gm.estado_mantenimiento', 'en_cierre')
            ->orWhere('gm.estado_mantenimiento', 'finalizada')
            ->orWhere('gm.estado_mantenimiento', null)
        ->groupEnd()
        ->orderBy('ct.fecha_inicio_atencion', 'DESC')
        ->get()
        ->getResultArray();
}

// ── Citas del taller de un mecánico ──────────────────────────────────────
public function obtenerPorMecanico(int $idMecanico): array
{
    return $this->db->table('cita_taller ct')
        ->select('
            ct.*,
            t.nombre_taller,
            t.id_taller,
            v.placa,
            v.model_year,
            ma.nombre_marca,
            mo.nombre_modelo,
            tv.texto_tipo_vehi,
            p.primer_nombre   AS cliente_nombre,
            p.primer_apellido AS cliente_apellido,
            prop.telefono_propietario
        ')
        ->join('taller t',          't.id_taller       = ct.taller_id_taller')
        ->join('vehiculo v',        'v.placa            = ct.vehiculo_placa')
        ->join('propietarios prop', 'prop.id_propietario = v.propietarios_id_propietario')
        ->join('personas p',        'p.id_persona       = prop.id_propietario')
        ->join('modelos mo',        'mo.id_modelo       = v.modelos_id_modelo')
        ->join('marcas ma',         'ma.id_marca        = mo.marcas_id_marca')
        ->join('tipos_vehiculo tv', 'tv.id_tipo_vehi    = v.tipos_vehiculo_id_tipo_vehi')
        ->where('t.mecanicos_id_mecanico', $idMecanico)
    // En CitaTallerModel::obtenerCompletasPorPropietario()
// Busca la línea que filtra estados y agrega 'finalizada':
->whereIn('ct.estado_cita', ['pendiente', 'confirmada', 'en_atencion', 'finalizada', 'cancelada_propietario', 'cancelada_mecanico'])
        ->orderBy('ct.fecha_registro', 'DESC')
        ->get()
        ->getResultArray();
}
// Trae citas en_atencion, en_cierre Y finalizadas del mecánico
public function obtenerEnAtencionYFinalizadas(int $idMecanico): array
{
    return $this->db->table('cita_taller ct')
        ->select('ct.*, 
                  p.nombre AS cliente_nombre, p.apellido AS cliente_apellido,
                  p.telefono AS telefono_propietario,
                  v.placa, v.model_year,
                  ma.nombre_marca, mo.nombre_modelo,
                  tv.texto_tipo_vehi,
                  gm.id_seguimiento, gm.estado_mantenimiento,
                  gm.observaciones_tecnico, gm.precio_total,
                  gm.texto_garantia, gm.garantia_vigencia,
                  gm.codigo_entrega, gm.fecha_entrega')
        ->join('taller t',          't.id_taller = ct.taller_id_taller')
        ->join('vehiculo v',         'v.placa = ct.vehiculo_placa')
        ->join('propietarios pr',    'pr.id_propietario = v.propietarios_id_propietario')
        ->join('persona p',          'p.id_persona = pr.personas_id_persona')
        ->join('marca ma',           'ma.id_marca = v.marca_id_marca')
        ->join('modelo mo',          'mo.id_modelo = v.modelo_id_modelo')
        ->join('tipo_vehiculo tv',   'tv.id_tipo_vehi = v.tipo_vehiculo_id_tipo_vehi')
        ->join('gestion_mantenimiento gm', 'gm.cita_taller_id_cita = ct.id_cita', 'left')
        ->where('t.mecanico_id_mecanico', $idMecanico)
        ->whereIn('ct.estado_cita', ['en_atencion', 'finalizada'])
        ->orderBy('ct.fecha_registro', 'DESC')
        ->get()
        ->getResultArray();
}
}