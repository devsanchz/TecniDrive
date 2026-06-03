<?php

namespace app\Controllers;

try {
    $db = \Config\Database::connect();
} catch (\Throwable $th) {
    echo "error de conexion : 505".$th->Getmessage($e);    
}
    

?>