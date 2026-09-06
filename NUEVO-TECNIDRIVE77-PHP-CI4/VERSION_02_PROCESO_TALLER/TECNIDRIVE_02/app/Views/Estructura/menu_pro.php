<link rel="stylesheet" href="<?= base_url('assets/css/menu.css') ?>">

    <div class="sidebar">
  <ul>
    <li><a><span class="icon"><i class="bi bi-list"></i></span><span class="text">Panel de usuario</span></a></li>
    <li><a href="<?= site_url('propietario/panel') ?>"  ><span class="icon"><i class="bi bi-file-person-fill"></i></span><span class="text">Mi perfil</span></a></li>
    <li><a  href="<?= site_url('propietario/vehiculo') ?>"   ><span class="icon"><i class="bi bi-car-front-fill"></i></span><span class="text">Vehículos</span></a></li>
    <li><a href="<?= site_url('propietario/taller') ?>"  ><span class="icon"><i class="bi bi-tools"></i></span><span class="text">Talleres</span></a></li>
      <li><a href="<?= site_url('propietario/cita') ?>"   ><span class="icon"><i class="bi bi-calendar2-plus"></i></span><span class="text">Cita Agendada</span></a></li>
    <li><a href="<?= site_url('propietario/salir') ?>"   ><span class="icon"><i class="bi bi-box-arrow-left"></i></span><span class="text">Cerrar sesión</span></a></li>
  </ul>
</div>