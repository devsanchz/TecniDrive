<?php

namespace app\Controllers;
use Codeigniter\RESTful\resourceControllers;
class APIcontroller extends resourceControllers {
    public function personas(){
        $datos =[ 
            [
            "id" => 1,
            "primer_nombre" => "andres"
            ],
            [
            "id" => 2,
            "primer_nombre" => "maria"
            ]
        ];
        return $this->respond ($datos);
    }
}
/**
 * el proposito de este codigo es basicamente tomar datos (de prueba por ahora)
 * que se envian a codeigniter desde la interfaz android para que sea manegado por el framework asi es 
 * mas seguro pero mas lento
 */
?>
