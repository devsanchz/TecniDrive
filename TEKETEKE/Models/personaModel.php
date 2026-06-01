<?php
namespace App\Model;
use CodeIgniter\Model;

class Persona extends Model{
    protected $table = 'personas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowFields = []


}

?>