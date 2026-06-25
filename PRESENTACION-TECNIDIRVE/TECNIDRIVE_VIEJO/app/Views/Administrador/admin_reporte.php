 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_reportee.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_admin') ?>
<main class="main-content">
        <header class="titulos">
            <h1 class="titulo">Reportes de calidad y el sistema</h1>
            <h4>Revisa y filtra la informacion para resivir recortes del sistema</h4>
        </header>
   <section class="dashboard-section">
<div class="casilla">
    <h1>Filtros para generar reportes PDF</h1>
    <div class="seccion-casillas">


         <details class="mi-detalle">
              <summary>
               <i class="bi bi-person-fill"></i> Usuarios registrados
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
             
              <div class="filtros">
                <div class="f1">
                <label for=""><input type="checkbox">Propietarios</label>
                <label for=""><input type="checkbox">Mecanicos</label>
                </div>
                <div class="f2">
                    <h3>Fecha de registro</h3>
                      <div class="fechas">
                        <p>Repetir recordatorio</p>
                        <label><input type="radio"  checked>Esta semana</label>
                        <label><input type="radio" > Este mes</label>
                        <label><input type="radio">Hace 6 meses</label>
                        <label><input type="radio">Hace un año</label>
                    </div>
                </div>
              </div>
            </details>



             <details class="mi-detalle">
              <summary>
                  <i class="fa-solid fa-car"></i>
                Vehículos registrados
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
             <div class="filtros">
                <div class="f1">
                <label for=""><input type="checkbox">Automoviles</label>
                <label for=""><input type="checkbox">Motocicletas</label>
                </div>
                <div class="f2">
                    <h3>Fecha de registro</h3>
                      <div class="fechas">
                        <label><input type="radio"  checked>Esta semana</label>
                        <label><input type="radio" > Este mes</label>
                        <label><input type="radio">Hace 6 meses</label>
                        <label><input type="radio">Hace un año</label>
                    </div>
                </div>
              </div>
            </details>


     <details class="mi-detalle">
              <summary>
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                Talleres registrados 
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
               <div class="filtros">
                <div class="f1">
                <label for=""><input type="checkbox">talleres</label>
                </div>
                <button>Registrados</button>
                      <div class="fechas">
                          <h3>Fecha de registro</h3>
                             <label><input type="radio">Desactivados</label>
                        <label><input type="radio"  checked>Esta semana</label>
                        <label><input type="radio" > Este mes</label>
                        <label><input type="radio">Hace 6 meses</label>
                        <label><input type="radio">Hace un año</label>
                    </div>
<button>Ranking </button>
  <div class="fechas">
                          <h3>Filtros de ranking</h3>
                        <label><input type="radio"  checked>Mejores puntuaciones</label>
                        <label><input type="radio"  checked>Peores puntuaciones</label>
                        <label><input type="radio" >Con mas citas</label>
                        <label><input type="radio">Con menos citas</label>
                    </div>


              </div>

             
            </details>

            
    </div>
    <button class="reporte">Generar reporte</button>
</div>
   </section>

    </main>

<?php echo $this->endSection()?>
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/admin_reportes.js') ?>"></script>
<?= $this->endSection() ?>