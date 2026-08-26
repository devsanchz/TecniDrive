<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $ano = $_POST['ano'];
    $placa = $_POST['placa'];

    echo "<h2> Vehículo Registrado </h2>";
    echo "Marca: " . $marca . "<br>";
    echo "Modelo: " . $modelo . "<br>";
    echo "Año: " . $ano . "<br>";
    echo "Placa: " . $placa . "<br>";
} 
else {
    echo "Acceso no válido";
}
?>