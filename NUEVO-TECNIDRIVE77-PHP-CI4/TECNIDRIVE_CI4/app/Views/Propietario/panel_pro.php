 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/panel_proo.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_pro') ?>

 <section class="profile-card" id="profileCard">
      <!-- LUGAR DE NOTIFICACIONES -->
       <a class="notis" href="<?= site_url('propietario/notificacion') ?>" ><div class="notificaciones">
       <i class="bi bi-bell-fill campana"></i>
        <small  class="noti">php</small>
       </div>
        </a> 

        <!-- AVATER PERFIL -->
        <div class="profile-avatar" id="profileAvatar">
            <i class="bi bi-person-check" id="profileIcon"></i>
        </div>

        <!-- EDITAR COLOR -->
        <div class="color-editor" id="colorEditor">
           <label for="avatarColor">
                Editar color <br>
            <input type="color" id="avatarColor" value="#3f4bd0">
         </label>
        </div>

        <!-- INFORMACION PERFIL -->
        <ul class="profile-info">

            <!-- NOMBRE -->
            <li>
                <span id="userName">
                 php
                </span>
            </li>

            <!-- ROL -->
            <li>
                <span id="userRole">
                 php
                </span>
            </li>

            <!-- CORREO -->
            <li class="email-container">
                <i class="bi bi-envelope-at-fill email-icon"></i>
                <span id="userEmail">
                  php
                </span>

                <!-- INPUT PARA EDITAR -->
                <input type="email"
                       id="emailInput"
                       value="php">
            </li>
        </ul>
       
         <!-- TELEFONO -->
            <li class="telefono-container">
                <i class="bi bi-telephone-plus-fill telefono-icon"></i>
                <span id="usertelefono">
                  php
                </span>

                <!-- INPUT PARA EDITAR telefno -->
                <input type="tel"
                       id="telefonoInput"
                       value="php">
            </li>
        </ul>

        <!-- BOTON EDITAR PERFIL -->
        <div class="profile-buttons">

            <button id="editButton">
            Editar perfil
            </button>

        <!-- BOTON LISTO -->
            <button id="saveButton">
                Listo
            </button>

        </div>
    </section>



<?php echo $this->endSection()?>
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/panel_pro.js') ?>"></script>
<?= $this->endSection() ?>