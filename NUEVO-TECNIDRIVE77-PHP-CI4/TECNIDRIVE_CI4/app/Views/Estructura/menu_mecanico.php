<link rel="stylesheet" href="<?= base_url('assets/css/menu.css') ?>">

<div class="sidebar">
  <ul>
    <li><a ><span class="icon"><i class="bi bi-list"></i></span><span class="text">Panel de usuario</span></a></li>
    <li><a href="<?= site_url('mecanico/panel') ?>" ><span class="icon"><i class="bi bi-file-person-fill"></i></span><span class="text">Mi perfil</span></a></li>
    <li><a href="<?= site_url('mecanico/taller') ?>" ><span class="icon"><i class="bi bi-tools"></i></span><span class="text">Tu taller</span></a></li>
      <li><a href="<?= site_url('mecanico/cita') ?>" ><span class="icon"><i class="bi bi-calendar2-check"></i></span><span class="text">Agenda de citas</span></a></li>
       <li><a href="<?= site_url('mecanico/control') ?>" ><span class="icon"><i class="bi bi-clock-history"></i></span><span class="text">Control de citas</span></a></li>
      <li><a href="<?= site_url('mecanico/calificacion') ?>" ><span class="icon"><i class="bi bi-star-half"></i></span><span class="text">Calificaciones</span></a></li>
    <li><a href="<?= site_url('mecanico/logout') ?>" ><span class="icon"><i class="bi bi-box-arrow-left"></i></span><span class="text">Cerrar sesión</span></a></li>
  </ul>
</div>