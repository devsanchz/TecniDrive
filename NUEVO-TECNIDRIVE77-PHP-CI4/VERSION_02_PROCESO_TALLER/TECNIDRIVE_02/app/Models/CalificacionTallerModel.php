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
        'estado',
        'visto',
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

    // ── Obtener todas las calificaciones APROBADAS de un taller con nombre del autor ─
    public function obtenerPorTaller(int $idTaller): array
    {
        return $this->select('calificacion_taller.*, p.primer_nombre, p.primer_apellido, p.avatarcolor')
            ->join('propietarios pr', 'pr.id_propietario = calificacion_taller.propietarios_id_propietario')
            ->join('personas p',      'p.id_persona      = pr.id_propietario')
            ->where('taller_id_taller', $idTaller)
            ->where('estado', 'aprobada')
            ->orderBy('fecha_registro', 'DESC')
            ->findAll();
    }

    // ── Promedio de puntuación de un taller (solo aprobadas) ───────────────
    public function promedio(int $idTaller): float
    {
        $result = $this->selectAvg('puntuacion', 'promedio')
            ->where('taller_id_taller', $idTaller)
            ->where('estado', 'aprobada')
            ->first();

        return round((float)($result['promedio'] ?? 0), 1);
    }

    // ── Verificar si este propietario ya calificó este taller (con una
    //    calificación vigente: pendiente o aprobada). Las rechazadas NO
    //    cuentan, porque el propietario debe poder volver a calificar
    //    tras un rechazo en vez de quedar bloqueado para siempre. ────────
    public function yaCalifico(int $idTaller, int $idPropietario): bool
    {
        return $this->where('taller_id_taller',            $idTaller)
                    ->where('propietarios_id_propietario', $idPropietario)
                    ->where('estado !=', 'rechazada')
                    ->countAllResults() > 0;
    }

    // ── Obtener la calificación propia de un propietario en un taller (cualquier
    //    estado). Se ordena por id_calificacion DESC para garantizar que, si por
    //    cualquier motivo existiera más de una fila para el mismo par
    //    propietario/taller (p. ej. datos residuales de pruebas anteriores),
    //    siempre se tome la MÁS RECIENTE como referencia para la vista. ──────
    public function obtenerMiCalificacion(int $idTaller, int $idPropietario): ?array
    {
        return $this->where('taller_id_taller', $idTaller)
                    ->where('propietarios_id_propietario', $idPropietario)
                    ->orderBy('id_calificacion', 'DESC')
                    ->first();
    }

    // ── Obtener específicamente una calificación RECHAZADA previa, si existe.
    //    Se usa al volver a calificar tras un rechazo: en vez de insertar una
    //    fila nueva, se reutiliza esta misma para no duplicar registros del
    //    mismo propietario/taller. ─────────────────────────────────────────
    public function obtenerRechazadaPrevia(int $idTaller, int $idPropietario): ?array
    {
        return $this->where('taller_id_taller', $idTaller)
                    ->where('propietarios_id_propietario', $idPropietario)
                    ->where('estado', 'rechazada')
                    ->first();
    }

    // ── Total de calificaciones APROBADAS de un taller ────────────────────
    public function totalAprobadas(int $idTaller): int
    {
        return $this->where('taller_id_taller', $idTaller)
                    ->where('estado', 'aprobada')
                    ->countAllResults();
    }

    // ── Aprobar / Rechazar (para el admin) ─────────────────────────────────
    public function aprobar(int $idCalificacion): bool
    {
        return $this->skipValidation(true)->update($idCalificacion, ['estado' => 'aprobada']);
    }

    public function rechazar(int $idCalificacion): bool
    {
        return $this->skipValidation(true)->update($idCalificacion, ['estado' => 'rechazada']);
    }

    // ── Marcar una calificación como vista por el mecánico ────────────────
    public function marcarVisto(int $idCalificacion): bool
    {
        return $this->skipValidation(true)->update($idCalificacion, ['visto' => 1]);
    }

    // ── Obtener todas las calificaciones (para el admin) ─────────────────
    public function obtenerTodas(): array
    {
        return $this->select('
                calificacion_taller.*,
                p.primer_nombre,
                p.primer_apellido,
                p.avatarcolor,
                t.nombre_taller
            ')
            ->join('propietarios pr', 'pr.id_propietario = calificacion_taller.propietarios_id_propietario')
            ->join('personas p',      'p.id_persona      = pr.id_propietario')
            ->join('taller t',        't.id_taller       = calificacion_taller.taller_id_taller')
            ->orderBy('fecha_registro', 'DESC')
            ->findAll();
    }

    public function obtenerPorPropietario(int $idPropietario): array
    {
        return $this->select('
                calificacion_taller.*,
                t.nombre_taller,
                t.tipo_servicio
            ')
            ->join('taller t', 't.id_taller = calificacion_taller.taller_id_taller')
            ->where('propietarios_id_propietario', $idPropietario)
            ->orderBy('fecha_registro', 'DESC')
            ->findAll();
    }
}