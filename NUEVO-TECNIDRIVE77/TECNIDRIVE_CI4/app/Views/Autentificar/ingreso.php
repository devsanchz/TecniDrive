 <?php echo $this->extend('Estructura/diseño');?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/ingreso.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 

   <div class="tarjeta-autenticacion" id="loginCard">
    <h1>Iniciar sesión</h1>

    <form id="loginForm">
      <label>E-mail</label>
      <div class="grupo-input">
        <!-- INPUT DE CORREO -->
        <input type="email" id="usuarioLogin" name="email" placeholder="ejemplo@gmail.com" required>
        <i class="bi bi-envelope"></i>
      </div>

      <label>Contraseña</label>
      <div class="grupo-input">
          <!-- de contraseña -->
        <input type="password" id="passwordLogin" name="pass" placeholder="●●●●●●●●" required minlength="8">
      </div>

      <button type="submit">Ingresar</button>
    </form>

    <div class="links">
      <a href="<?= site_url('autentificar/recuperar')?>">¿Olvidaste tu contraseña?</a>
      <a href="<?= site_url('autentificar/registro') ?>">¿No tienes cuenta? Crear cuenta</a>
    </div>
  </div>
 <?php echo $this->endSection()?>