<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel_mecanico.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_mecanico') ?>



<section class="profile-card" id="profileCard">

    <!--
        AVATAR
        - border-color e color del ícono vienen de PHP (avatarcolor desde BD).
        - Así el color es correcto desde el primer render, incluso tras recargar.
    -->
    <div class="profile-avatar mecanico"
         id="profileAvatar"
         style="border-color: <?= esc($avatarcolor) ?>">

        <i class="bi bi-person-fill-gear"
           id="profileIcon"
           style="color: <?= esc($avatarcolor) ?>"></i>

    </div>

    <!-- EDITAR COLOR -->
    <div class="color-editor" id="colorEditor">
        <label for="avatarColor">
            Editar color <br>
            <input type="color" id="avatarColor" value="<?= esc($avatarcolor) ?>">
        </label>
    </div>

    <!-- INFORMACIÓN PERFIL -->
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

    <!-- TELÉFONO -->
    <ul class="profile-info">
        <li class="telefono-container">
            <i class="bi bi-telephone-plus-fill telefono-icon"></i>
            <span id="usertelefono"><?= esc($telefono) ?></span>
            <input type="tel" id="telefonoInput" value="<?= esc($telefono) ?>">
        </li>
    </ul>

    <!-- Mensaje de error devuelto por el servidor (oculto por defecto) -->
    <p id="perfilError" class="error-msg" style="display:none"></p>

    <!-- BOTONES -->
    <div class="profile-buttons">
        <button id="editButton">Editar perfil</button>
        <button id="saveButton">Listo</button>
    </div>

</section>

<?php echo $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const PERFIL_URL = "<?= site_url('mecanico/perfil/actualizar') ?>";
</script>
<script src="<?= base_url('assets/js/panel_mecanico.js') ?>"></script>
<?= $this->endSection() ?>