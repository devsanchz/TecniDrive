<?php
$db = \config\Database::connect();
$builder = $db->table('personas');

//PRACTICA: SELECT * FROM personas WHERE id = 1

$query = $builder->getWhere(['id' => 1]);
$res = $query->getResultArray(); // array de arrays

namespace GestionRoles;

class Management{
    public function role ($_POST['mail'], $_POST['pss']){
        $default =  
    }
}
?>