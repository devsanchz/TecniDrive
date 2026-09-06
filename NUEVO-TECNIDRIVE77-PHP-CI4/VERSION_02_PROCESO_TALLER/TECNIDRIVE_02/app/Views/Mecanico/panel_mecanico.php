<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel_mecanico.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_mecanico') ?>
<section class="profile-card" id="profileCard">

    <div class="profile-avatar mecanico"
         id="profileAvatar"
         style="border-color: <?= esc($avatarcolor) ?>">

        <i class="bi bi-person-fill-gear"
           id="profileIcon"
           style="color: <?= esc($avatarcolor) ?>"></i>

    </div>

    <div class="color-editor" id="colorEditor">
        <label for="avatarColor">
            Editar color <br>
            <input type="color" id="avatarColor" value="<?= esc($avatarcolor) ?>">
        </label>
    </div>

    <ul class="profile-info">

         <li>
            <span id="userName"><?= esc($nombre_completo) ?></span>
        </li>

        <li>
            <span id="userRole"><?= esc($rol_texto) ?></span>
        </li>

        <li class="email-container">
            <i class="bi bi-envelope-at-fill email-icon"></i>
            <span id="userEmail"><?= esc($email) ?></span>
            <input type="email" id="emailInput" value="<?= esc($email) ?>">
        </li>

    </ul>

    <ul class="profile-info">
        <li class="telefono-container">
            <i class="bi bi-telephone-plus-fill telefono-icon"></i>
            <span id="usertelefono"><?= esc($telefono) ?></span>
            <input type="tel" id="telefonoInput" value="<?= esc($telefono) ?>">
        </li>
    </ul>

    <p id="perfilError" class="error-msg" style="display:none"></p>

    <div class="profile-buttons">
        <button id="editButton">Editar perfil</button>
        <button id="saveButton">Listo</button>
    </div>

</section>

<?php echo $this->endSection() ?>

<?= $this->section('scripts') ?>
<!--
    Token CSRF expuesto para que el JS pueda enviarlo como header en
    peticiones fetch() con body JSON.
-->
<meta name="csrf-name" content="<?= csrf_token() ?>">
<meta name="csrf-hash" content="<?= csrf_hash() ?>">
<script>
    const PERFIL_URL = "<?= site_url('mecanico/perfil/actualizar') ?>";
</script>
<script src="<?= base_url('assets/js/panel_mecanico.js') ?>"></script>
<?= $this->endSection() ?>