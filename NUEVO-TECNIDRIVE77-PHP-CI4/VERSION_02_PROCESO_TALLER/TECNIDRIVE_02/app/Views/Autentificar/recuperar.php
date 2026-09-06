<?= $this->extend('Estructura/diseño') ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/registro.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('contenido') ?>

<div class="tarjeta-autenticacion">
    <h1>Recuperación de la cuenta</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alerta-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('autentificar/enviar-codigo') ?>">
        <?= csrf_field() ?>
        <div class="grupo">
            Para recuperar tu cuenta, ingresa tu correo electrónico registrado.
        </div>
        <div class="grupo">
            <input type="email" name="email" placeholder="Tu correo electrónico" required>
        </div>
        <button type="submit">Enviar código</button>
    </form>

    <div class="links">
        <a href="<?= site_url('autentificar/ingreso') ?>">Iniciar sesión</a>
    </div>
</div>

<?= $this->endSection() ?>