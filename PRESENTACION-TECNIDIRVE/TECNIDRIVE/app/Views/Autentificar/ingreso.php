<?php echo $this->extend('Estructura/diseño'); ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/ingreso.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>

<div class="tarjeta-autenticacion" id="loginCard">
    <h1>Iniciar sesión</h1>

    <?php
    // Recuperar mensajes enviados como flashdata desde el controlador
    $errores  = session()->getFlashdata('errores') ?? $errores ?? [];
    $exitoMsg = session()->getFlashdata('exito')   ?? $exito   ?? null;
    $oldEmail = ($old['email'] ?? '');
    ?>

    <!-- Mensaje de éxito (viene del registro o del cierre de sesión) -->
    <?php if ($exitoMsg): ?>
        <div class="alerta-exito" role="alert">
            <?= esc($exitoMsg) ?>
        </div>
    <?php endif; ?>

    <!-- Error general de credenciales incorrectas -->
    <?php if (isset($errores['login'])): ?>
        <div class="alerta-error" role="alert">
            <?= esc($errores['login']) ?>
        </div>
    <?php endif; ?>

    <!--
        CAMBIOS RESPECTO AL ORIGINAL:
        1. method="POST" y action agregados al form (faltaban)
        2. <?= csrf_field() ?> para protección CSRF
        3. name="password" corregido (era name="pass", no coincidía con getPost)
        4. value en email para repoblar si hay error
        5. Bloques de error inline por campo
    -->
    <form id="loginForm" method="POST" action="<?= site_url('autentificar/ingreso') ?>">

        <?= csrf_field() ?>

        <label>E-mail</label>
        <div class="grupo-input">
            <input
                type="email"
                id="usuarioLogin"
                name="email"
                placeholder="ejemplo@gmail.com"
                value="<?= esc($oldEmail) ?>"
                required
            >
            <i class="bi bi-envelope"></i>
        </div>
        <?php if (isset($errores['email'])): ?>
            <span class="error-campo" role="alert"><?= esc($errores['email']) ?></span>
        <?php endif; ?>

        <label>Contraseña</label>
        <div class="grupo-input">
            <input
                type="password"
                id="passwordLogin"
                name="password"
                placeholder="●●●●●●●●"
                required
                minlength="8"
            >
        </div>
        <?php if (isset($errores['password'])): ?>
            <span class="error-campo" role="alert"><?= esc($errores['password']) ?></span>
        <?php endif; ?>

        <button type="submit">Ingresar</button>

    </form>

    <div class="links">
        <a href="<?= site_url('autentificar/recuperar') ?>">¿Olvidaste tu contraseña?</a>
        <a href="<?= site_url('autentificar/registro') ?>">¿No tienes cuenta? Crear cuenta</a>
    </div>
</div>

<?php echo $this->endSection() ?>