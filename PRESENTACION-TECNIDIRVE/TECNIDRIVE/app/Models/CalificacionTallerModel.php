<?php

namespace App\Models;

use CodeIgniter\Model;

class CalificacionTallerModel extends Model
{
    protected $table      = 'calificacion_taller';
    protected $primaryKey = 'id_calificacion';

    protected $allowedFields = [
        'puntuacion',
        'comentario',
        'taller_id_taller',
        'propietarios_id_propietario',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false; // fecha_registro tiene DEFAULT en BD

    // ── Reglas de validación ──────────────────────────────────────────────
    protected $validationRules = [
        'puntuacion'                   => 'required|integer|greater_than[0]|less_than_equal_to[5]',
        'taller_id_taller'             => 'required|integer',
        'propietarios_id_propietario'  => 'required|integer',
    ];

    // ── Obtener todas las calificaciones de un taller con nombre del autor ─
    public function obtenerPorTaller(int $idTaller): array
    {
        return $this->select('calificacion_taller.*, p.primer_nombre, p.primer_apellido')
            ->join('propietarios pr', 'pr.id_propietario = calificacion_taller.propietarios_id_propietario')
            ->join('personas p',      'p.id_persona      = pr.id_propietario')
            ->where('taller_id_taller', $idTaller)
            ->orderBy('fecha_registro', 'DESC')
            ->findAll();
    }

    // ── Promedio de puntuación de un taller ───────────────────────────────
    public function promedio(int $idTaller): float
    {
        $result = $this->selectAvg('puntuacion', 'promedio')
            ->where('taller_id_taller', $idTaller)
            ->first();

        return round((float)($result['promedio'] ?? 0), 1);
    }

    // ── Verificar si este propietario ya calificó este taller ────────────
    public function yaCalifico(int $idTaller, int $idPropietario): bool
    {
        return $this->where('taller_id_taller',            $idTaller)
                    ->where('propietarios_id_propietario', $idPropietario)
                    ->countAllResults() > 0;
    }
}