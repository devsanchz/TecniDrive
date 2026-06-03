<?php

use Controllers\Database;

?>

<!DOCTYPE html>

<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - TecniDrive</title>
  <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>





    <!-- INICIAR SESION -->
  <div class="login-container">

    <h1>Login</h1>

    <form id="loginForm" action="<?= base_url('login')?>" method="POST">

      <?php csrf_field()?> <!--proteccion de la sesion proporcionado por codeigniter  4 para usar $session en lugar de $_SESSIon-->
    
      <label>Usuario</label>
    <input type="text" id="usuario" name="email" placeholder="usuario" required >
    <label>Contraseña</label>
    <input type="password" id="password" name="contrasena" placeholder="contraseña" required >
    <button type="submit">Ingresar</button>

  </form>

    <div class="links">
      <a href="#">¿Olvidaste tu contraseña?</a>
      <a href="#" onclick="mostrarSign()">No tienes cuenta? Registrate</a>
    </div>

  </div>





    <!-- REGISTRARSE-->
  <div class="login-container">

    <form id="signForm" method="POST">

      <h1>Sing in</h1>
      <label>Nombres</label>
    <input type="text"  placeholder="Primer nombre" required>
    <input type="text"  placeholder="Segundo nombre (opcional)">

      <label>Apellidos</label>
    <input type="text"  placeholder="Primer apellido" required>
    <input type="text" placeholder="Segundo apellido" required>

      <label>Correo</label>
    <input type="email"  placeholder="email" required>

      <label>Contraseña</label>
    <input type="password" placeholder="contraseña" required>

    <button type="submit">Registrarse</button>

    <div class="links">
        <a href="#" onclick="mostrarLogin()">Ya estas registrado? inicia sesión</a>

    </div>

    </form>
  </div>
</body>
<script>

//MOSTRAR Y OCULTAR FORMULARIOS
function mostrarSign() {
  document.querySelectorAll(".login-container")[0].style.display = "none";
  document.querySelectorAll(".login-container")[1].style.display = "block";
}

function mostrarLogin() {
  document.querySelectorAll(".login-container")[1].style.display = "none";
  document.querySelectorAll(".login-container")[0].style.display = "block";}
</script>
</html>
