<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> TecniDrive </title>
    <link rel="stylesheet" href="./TecniDrive/styles.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2> TecniDrive </h2>
            <ul>
                <li> Inicio </li>
                <li> Mis Vehículos </li>
                <li> Citas </li>
                <li> Perfil </li>
            </ul>
        </aside>
        <main class="main-content">
            <h2> Registro de Vehículos </h2>
        
            <form action="reg_veh.php" method="POST" class="formulario">
                <input type="text" name="marca" placeholder="Marca" required>
                <input type="text" name="modelo" placeholder="Modelo" required>
                <input type="text" name="ano" placeholder="Año" required>
                <input type="text" name="placa" placeholder="Placa" required>
                <button type="submit"> Registrar </button>
            </form>
        </main>
    </div>
</body>
</html>