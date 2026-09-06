 <?php echo $this->extend('Estructura/diseño');?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/registro.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 

  <a class="Volver" title="Volver a principal" title="Volver"  href="<?= site_url('/') ?>" >
<i class="bi bi-chevron-left"></i>
</a>

  <div class="tarjeta-autenticacion" id="registerCard">
    <h1>Crea tu cuenta</h1>

    <form id="signForm"  action="<?= site_url('autentificar/rol') ?>">
      
      <label>Nombres</label>
      <div class="fila-doble">
        <div class="campo-bloque">
          <input type="text" placeholder="Primer nombre" required>
        </div>
        <div class="campo-bloque">
          <small class="etiqueta-opcional">Opcional</small>
          <input type="text" placeholder="Segundo nombre">
        </div>
      </div>

      <label>Apellidos</label>
      <div class="fila-doble">
        <div class="campo-bloque">
          <input type="text" placeholder="Primer apellido" required>
        </div>
        <div class="campo-bloque">
          <input type="text" placeholder="Segundo apellido" required>
        </div>
      </div>

      <label>Teléfono</label>
      <div class="grupo-input">
        <input type="tel" id="telefonoReg" placeholder="300 123 4567" required>
        <i class="bi bi-telephone"></i>
      </div>

      <label>E-mail</label>
      <div class="grupo-input">
        <input type="email" id="usuarioReg" placeholder="ejemplo@gmail.com" required>
        <i class="bi bi-envelope"></i>
      </div>

      <label>Contraseña</label>
        <input type="password" id="passwordReg" placeholder="●●●●●●●●" required>
    
<button type="submit">Continúa</button>

 <div class="links">
      <a href="<?= site_url('autentificar/ingreso') ?>">¿Ya tienes una cuenta? Iniciar sesión</a>
    </div>
  </div>

    </form>
 <?php echo $this->endSection()?>