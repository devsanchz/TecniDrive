<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'personas';
    protected $primaryKey = 'id_persona';
    protected $returnType = 'array';

    protected $allowedFields = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'contrasena',
    ];

    // fecha_registro tiene DEFAULT CURRENT_TIMESTAMP en BD, CI4 no la toca
    protected $useTimestamps = false;

    // ------------------------------------------------------------------
    // LOGIN: busca la persona por email Y trae su rol con JOIN
    //
    // La consulta une 3 tablas:
    //   personas → roles_has_persona → roles
    //
    // Resultado: array con todos los campos de personas + texto_rol
    // Devuelve null si el email no existe
    // ------------------------------------------------------------------
    public function buscarPorEmailConRol(string $email): ?array
    {
        return $this->db->table('personas p')
            ->select('
                p.id_persona,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.email,
                p.contrasena,
                r.texto_rol
            ')
            ->join('roles_has_persona rhp', 'rhp.personas_id_persona = p.id_persona', 'left')
            ->join('roles r',              'r.id_rol = rhp.roles_id_rol',             'left')
            ->where('p.email', $email)
            ->get()
            ->getRowArray();   // devuelve un array o null
    }

    // ------------------------------------------------------------------
    // REGISTRO: hashea la contraseña y crea la persona
    // Devuelve el id_persona insertado o false si falla
    // ------------------------------------------------------------------
    public function registrar(array $datos): bool|int
    {
        $datos
        ['contrasena'] = password_hash($datos['contrasena'], PASSWORD_BCRYPT),
        ['primer_nombre'],
        ['segundo_nombre'],
        ['primer_apellido'],
        ['segundo_apellido'],
        ['email']
        ;
        return $this->insert($datos);
    }
}