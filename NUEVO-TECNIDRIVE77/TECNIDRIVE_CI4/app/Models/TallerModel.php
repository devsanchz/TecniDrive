<?php

namespace App\Models;

use CodeIgniter\Model;

class TallerModel extends Model
{
    protected $table      = 'taller';
    protected $primaryKey = 'id_taller';

    protected $allowedFields = [
        'foto_taller',
        'nombre_taller',
        'descripcion_taller',
        'direccion_taller',
        'horario_taller',
        'estado_taller',
        'motivo_estado',
        'bloqueado_admin',
        'mecanicos_id_mecanico',
    ];

    protected $useTimestamps = false;

    // Buscar el taller de un mecánico específico
    // Devuelve null también si $idMecanico es 0 o negativo (sesión rota)
    public function buscarPorMecanico(int $idMecanico): array|null
    {
        if ($idMecanico <= 0) {
            return null;
        }
        return $this->where('mecanicos_id_mecanico', $idMecanico)->first();
    }

    // ── Talleres para el reporte PDF de administrador ──────────────────────
    // $estado: true (activos) | false (desactivados) | null (todos)
    // $fechaCorte: null cuando el modo es "ranking" (no se filtra por fecha)
    //
    // NOTA: estado_taller es BOOLEAN en la base de datos, por eso el filtro
    // recibe true/false y no texto.
    public function obtenerParaReporte(?bool $estado, ?string $fechaCorte): array
    {
        $builder = $this->select('id_taller, nombre_taller, direccion_taller, estado_taller, fecha_registro');

        if ($estado !== null) {
            $builder->where('estado_taller', $estado);
        }

        if ($fechaCorte !== null) {
            $builder->where('fecha_registro >=', $fechaCorte);
        }

        return $builder->orderBy('fecha_registro', 'DESC')->findAll();
    }
}