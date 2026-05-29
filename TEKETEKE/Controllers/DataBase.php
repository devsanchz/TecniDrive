<?php
try {
    $db = \Cofig\Database::connect();
} catch (\Throwable $th) {
    echo "error de conexion : 505".$th->Getmessage($e);    
}
    

?>