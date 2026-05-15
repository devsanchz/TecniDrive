<?php

$user = "root";
$host = "localhost";
$dbname = "tecnidrive_03";


try{
    $sql = "mysql: host:$host; dbname=$dbname; user=$user; charset=utf8";

}
catch(exception $e){
    echo"error en la conexion".$e -> GetMessage();
}
?>
