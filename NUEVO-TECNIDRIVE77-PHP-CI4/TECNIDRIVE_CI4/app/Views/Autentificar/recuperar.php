<?= $this->extend('Estructura/diseño') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/restablecer.css') ?>">
<style>
    .oculto {
        display: none !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?> 

<div class="tarjeta-autenticacion" id="pasoEnviarCodigo">
    <h1>Recuperación de la cuenta</h1>
    <form id="formEnviarCodigo">
        <div class="grupo">
            Para recuperar tu cuenta, te enviaremos un código de verificación a tu correo electrónico
        </div>
        <button type="submit">Enviar</button>
    </form>
    <div class="links">
        <a href="<?= site_url('autentificar/ingreso') ?>">Iniciar sesión</a>
    </div>
</div>

<div class="tarjeta-autenticacion oculto" id="pasoVerificarCodigo">
    <h1>Ingresar código</h1>
    <form id="formVerificarCodigo">
        <div class=" si">
            <input type="text" placeholder="Código de verificación" required>
        </div>
        <button type="submit">Verificar código</button>
    </form>
</div>
<?php echo $this->endSection()?> 
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/recuperar.js') ?>"></script>
<?= $this->endSection() ?>


 