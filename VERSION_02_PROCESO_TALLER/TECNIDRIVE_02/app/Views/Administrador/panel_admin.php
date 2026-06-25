<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel_admin.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_admin') ?>



<section class="profile-card" id="profileCard">

    <!--
        AVATAR
        - La clase CSS ("administrador") puede controlar forma/fondo.
        - El color del ícono se aplica con style inline desde PHP para que
          esté correcto desde el primer render, sin esperar al JS.
        - data-color es leído por JS para inicializar el <input type="color">.
    -->
    <div class="profile-avatar administrador"
         id="profileAvatar"
         style="border-color: <?= esc($avatarcolor) ?>">

        <i class="bi bi-person-fill-lock"
           id="profileIcon"
           style="color: <?= esc($avatarcolor) ?>"></i>

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
        </li>

    </ul>

</section>

<?php echo $this->endSection() ?>

