<link rel="stylesheet" href="<?= base_url('assets/css/menu.css') ?>">
<div class="sidebar">
  <ul>
    <li><a ><span class="icon"><i class="bi bi-list"></i></span><span class="text">Panel de admin</span></a></li>
    <li><a href="<?= site_url('administrador/panel') ?>"><span class="icon"><i class="bi bi-file-person-fill"></i></span><span class="text">Mi perfil</span></a></li>
    <li><a href="<?= site_url('administrador/vehiculo') ?>"><span class="icon"><i class="bi bi-car-front-fill"></i></span><span class="text">Lista de vehículos</span></a></li>
    <li><a href="<?= site_url('administrador/taller') ?>"><span class="icon"><i class="bi bi-tools"></i></span><span class="text">Gestión de talleres</span></a></li>
    <li><a href="<?= site_url('administrador/calificacion') ?>"><span class="icon"><i class="bi bi-star-half"></i></span><span class="text">Moderar reseñas</span></a></li>
    <li><a href="<?= site_url('administrador/reporte') ?>"><span class="icon"><i class="bi bi-bar-chart-line"></i></span><span class="text">Reportes</span></a></li>
    <li><a href="<?= site_url('administrador/salir') ?>"><span class="icon"><i class="bi bi-box-arrow-left"></i></span><span class="text">Cerrar sesión</span></a></li>
  </ul>
</div>