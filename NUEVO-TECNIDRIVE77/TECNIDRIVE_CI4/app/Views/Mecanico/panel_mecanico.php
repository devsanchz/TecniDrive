 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel_mecanico.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_mecanico') ?>

<section class="profile-card" id="profileCard">
      
        <div class="profile-avatar" id="profileAvatar">
             <i class="bi bi-person-gear"></i>
        </div>

        <div class="color-editor" id="colorEditor">
           <label for="avatarColor">
                Editar color <br>
            <input type="color" id="avatarColor" value="#3f4bd0">
         </label>
        </div>

        <ul class="profile-info">
            <li>
                <span id="userName">
                    php
                </span>
            </li>

            <li>
                <span id="userRole">
                 php
                </span>
            </li>

            <li class="email-container">
                <i class="bi bi-envelope-at-fill email-icon"></i>
                <span id="userEmail">
                    php
                </span>

                <input type="email" id="emailInput" value="php">
            </li>
        </ul>
       
         <ul class="profile-info">
            <li class="telefono-container">
                <i class="bi bi-telephone-plus-fill telefono-icon"></i>
                <span id="usertelefono">
                 php
                </span>

                <input type="tel" id="telefonoInput" value="3224509192">
            </li>
        </ul>

        <div class="profile-buttons">
            <button id="editButton">Editar perfil</button>
            <button id="saveButton">Listo</button>
        </div>
    </section>


<?php echo $this->endSection()?>


 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/panel_mecanico.js') ?>"></script>
<?= $this->endSection() ?>