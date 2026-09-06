<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'personas';
    protected $primaryKey       = 'id_persona';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'email',
        'password_hash',
        'codigo_recuperacion',
        'fecha_expiracion',
        'avatarcolor'
    ];

    protected $validationRules = [
        'primer_nombre'   => 'required|min_length[2]',
        'primer_apellido' => 'required|min_length[2]',
        'segundo_apellido'=> 'required|min_length[2]',
        'email'           => 'required|valid_email|is_unique[personas.email]',
        'password_hash'   => 'required|min_length[8]'
    ];
}
?>