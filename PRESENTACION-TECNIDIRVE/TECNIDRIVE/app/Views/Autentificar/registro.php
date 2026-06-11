<?php echo $this->extend('Estructura/diseño'); ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/registro.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>

<a class="Volver" title="Volver a principal" href="<?= site_url('/') ?>">
    <i class="bi bi-chevron-left"></i>
</a>

<div class="tarjeta-autenticacion" id="registerCard">
    <h1>Crea tu cuenta</h1>

    <?php
    /*
     * Mostrar error general (ej: sesión expirada, email duplicado llegando
     * desde el controlador sin pasar por la validación de campos).
     */
    $errorGeneral = session()->getFlashdata('error_general');
    $errores      = session()->getFlashdata('errores') ?? $errores ?? [];
    $old          = session()->getFlashdata('old')     ?? $old     ?? [];
    ?>

    <?php if ($errorGeneral): ?>
        <!-- Error general: no pertenece a un campo específico -->
        <div class="alerta-error" role="alert">
            <?= esc($errorGeneral) ?>
        </div>
    <?php endif; ?>

    <!--
        CAMBIOS RESPECTO AL ORIGINAL:
        1. method="POST"  → el formulario envía datos al servidor
        2. action POST    → apunta al método procesarRegistro()
        3. <?= csrf_field() ?> → token CSRF obligatorio en CI4
        4. name="..."     → cada input tiene nombre para que CI4 lo capture
        5. value="..."    → repoblar campos tras error de validación
        6. <span class="error-campo"> → mensajes de error inline por campo
    -->
    <form id="signForm" method="POST" action="<?= site_url('autentificar/registro') ?>">

        <?= csrf_field() ?>

        <!-- ── NOMBRES ─────────────────────────────────────────────────── -->
        <label>Nombres</label>
        <div class="fila-doble">

            <div class="campo-bloque">
                <input
                    type="text"
                    name="primer_nombre"
                    placeholder="Primer nombre"
                    value="<?= esc($old['primer_nombre'] ?? '') ?>"
                    required
                >
                <!-- Mensaje de error específico para este campo -->
                <?php if (isset($errores['primer_nombre'])): ?>
                    <span class="error-campo" role="alert">
                        <?= esc($errores['primer_nombre']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="campo-bloque">
                <small class="etiqueta-opcional">Opcional</small>
                <input
                    type="text"
                    name="segundo_nombre"
                    placeholder="Segundo nombre"
                    value="<?= esc($old['segundo_nombre'] ?? '') ?>"
                >
                <?php if (isset($errores['segundo_nombre'])): ?>
                    <span class="error-campo" role="alert">
                        <?= esc($errores['segundo_nombre']) ?>
                    </span>
                <?php endif; ?>
            </div>

        </div>

        <!-- ── APELLIDOS ───────────────────────────────────────────────── -->
        <label>Apellidos</label>
        <div class="fila-doble">

            <div class="campo-bloque">
                <input
                    type="text"
                    name="primer_apellido"
                    placeholder="Primer apellido"
                    value="<?= esc($old['primer_apellido'] ?? '') ?>"
                    required
                >
                <?php if (isset($errores['primer_apellido'])): ?>
                    <span class="error-campo" role="alert">
                        <?= esc($errores['primer_apellido']) ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="campo-bloque">
                <input
                    type="text"
                    name="segundo_apellido"
                    placeholder="Segundo apellido"
                    value="<?= esc($old['segundo_apellido'] ?? '') ?>"
                    required
                >
                <?php if (isset($errores['segundo_apellido'])): ?>
                    <span class="error-campo" role="alert">
                        <?= esc($errores['segundo_apellido']) ?>
                    </span>
                <?php endif; ?>
            </div>

        </div>
        <!-- ── TELÉFONO ───────────────────────────────────────────────────── -->
<!-- CAMBIO: se agrega name="telefono" para capturar el valor en POST  -->
<label>Teléfono</label>
<div class="grupo-input">
    <input
        type="tel"
        id="telefonoReg"
        name="telefono"
        placeholder="300 123 4567"
        value="<?= esc($old['telefono'] ?? '') ?>"
        required
    >
    <i class="bi bi-telephone"></i>
</div>
<?php if (isset($errores['telefono'])): ?>
    <span class="error-campo" role="alert">
        <?= esc($errores['telefono']) ?>
    </span>
<?php endif; ?>

        <!-- ── EMAIL ───────────────────────────────────────────────────── -->
        <label>E-mail</label>
        <div class="grupo-input">
            <input
                type="email"
                id="usuarioReg"
                name="email"
                placeholder="ejemplo@gmail.com"
                value="<?= esc($old['email'] ?? '') ?>"
                required
            >
            <i class="bi bi-envelope"></i>
        </div>
        <?php if (isset($errores['email'])): ?>
            <span class="error-campo" role="alert">
                <?= esc($errores['email']) ?>
            </span>
        <?php endif; ?>

        <!-- ── CONTRASEÑA ──────────────────────────────────────────────── -->
        <label>Contraseña</label>
        <input
            type="password"
            id="passwordReg"
            name="password"
            placeholder="●●●●●●●●"
            required
        >
        <?php if (isset($errores['password'])): ?>
            <span class="error-campo" role="alert">
                <?= esc($errores['password']) ?>
            </span>
        <?php endif; ?>

        <button type="submit">Continúa</button>

        <div class="links">
            <a href="<?= site_url('autentificar/ingreso') ?>">
                ¿Ya tienes una cuenta? Iniciar sesión
            </a>
        </div>

    </form>
</div>

<?php echo $this->endSection() ?>