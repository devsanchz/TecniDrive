<?php

namespace app\Models;
use Codeigniter\Model;

class UserModel extends Model{
    Protected $Table = 'personas';
    protected $Primarykey = 'id_persona';
    Protected $AllowFields = 
    [
    'primer_nombre',
    'segundo_nombre',
    'primer_apellido',
    'segundo_apellido',
    'email',
    'contrasena',
    'fecha_registro'];

    protected $ReturnType = 'array';
    
}

?>