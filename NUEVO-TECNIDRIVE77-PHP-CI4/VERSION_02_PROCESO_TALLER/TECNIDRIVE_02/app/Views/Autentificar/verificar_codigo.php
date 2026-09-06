<?= $this->extend('Estructura/diseño') ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/registro.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('contenido') ?>

<div class="tarjeta-autenticacion">
    <h1>Ingresar código</h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alerta-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <p style="font-size:0.9rem; color:#666; margin-bottom:1rem;">
        Revisa tu correo. El código es válido por 5 minutos.
    </p>

    <form method="post" action="<?= site_url('autentificar/verificar-codigo') ?>">
        <?= csrf_field() ?>
        <!-- Email oculto para enviarlo al controlador -->
        <input type="hidden" name="email" value="<?= esc(session()->getFlashdata('email_recuperacion')) ?>">
        <div class="grupo">
            <input type="text" name="codigo" placeholder="Código de 6 dígitos" maxlength="6" required>
        </div>
        <button type="submit">Verificar código</button>
    </form>
</div>

<?= $this->endSection() ?>