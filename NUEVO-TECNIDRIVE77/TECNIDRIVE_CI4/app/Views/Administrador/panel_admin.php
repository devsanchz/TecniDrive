<?php echo $this->extend('Estructura/diseño');?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel_admin.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_admin') ?>

<section class="profile-card" id="profileCard">

    <div class="profile-avatar" id="profileAvatar">
        <i class="bi bi-person-fill-lock" id="profileIcon"></i>
    </div>

    <div class="color-editor" id="colorEditor">
       <label for="avatarColor">
            Editar color <br>
            <input type="color" id="avatarColor" value="#3f4bd0">
       </label>
    </div>

    <ul class="profile-info">
        <li>
            <span id="userName">php</span>
        </li>

        <li>
            <span id="userRole">php</span>
        </li>

        <li class="email-container">
            <i class="bi bi-envelope-at-fill email-icon"></i>
            <span id="userEmail">php</span>

            <input type="email" id="emailInput" value="php">
        </li>

       
    </ul>

    <div class="profile-buttons">
        <button id="editButton">Editar perfil</button>
        <button id="saveButton">Listo</button>
    </div>

</section>
<?php echo $this->endSection()?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/panel_admin.js') ?>"></script>
<?= $this->endSection() ?>