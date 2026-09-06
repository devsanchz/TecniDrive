 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_citas.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_pro') ?>
 <!-- CODIGO DE CITA:CT02-7K9X -->
  <main class="main-content">
    <header class="titulos">
      <h1 class="titulo">Tus citas mecanicas</h1>
      <h5>Revisa y sigue el estado de tus solicitudes de cita para la atención de tu vehículo</h5>
    </header>

    <section class="dashboard-section">
      
      <div class="controles">
        <div class="buscador-wrapper">
          <p  class="cabezas">citas de tu vehículo</p>
          <div class="conteo">
           <div class="casilla-vehiculo activo">
             <i class="bi bi-car-front-fill"></i>
              <strong>php</strong>
              </div>
              <div class="datos">
                <span>Reparaciones completas: <strong>php</strong></span>
              </div>
              </div>
        </div>

        <div class="lista-taller">
          php
          <p class="cabezas">Talleres solicitados</p>
          <select name="" id="">
            <option value="">Mecanica general</option>
            <option value="">Los todo terreno</option>
            <option value="">carrodix</option>

          </select>
        </div>

        <div class="estados">
          <p  class="cabezas">Estados de tus citas</p>
  <select class="filtro-select">
          <option value="todos">Todas</option>
          <option value="pendientes">Pendientes</option>
          <option value="rechazadas">Confirmadas</option>
            <option value="rechazadas">En atención</option>
              <option value="rechazadas">Finalizadas</option>
          <option value="rechazadas">Canceladas</option>
        </select>
        </div>
      
      </div>

      <div class="tabla-calificaciones">
        
        <div class="casilla">
          <div class="informacion-principal">
            <div class="encabezado">
              <div class="estado">php</div>
              <div class="fecha-registro">
                <strong>Fecha de registro:</strong><span>php</span>
              </div>
            </div>
          
            <div class="fecha-cita">
              <fieldset>
                <legend>Fecha programada</legend>
                <span>php</span> a las <span>php</span>
              </fieldset>
            </div>
            <div class="cliente">
              <fieldset>
                <legend>Datos de contacto</legend>
                <i class="bi bi-person-gear icon-usuario"></i>
                <div class="usuario-info">
                  <span class="nombre">php</span>
                  <small class="email">php</small>
                </div>
              </fieldset>
               <fieldset>
                <legend>Taller solicitado</legend>
                <div class="usuario-info">
                  <span class="taller">php</span>
                  <small class="especial">php</small>
                </div>
              </fieldset>
            </div>
             <fieldset>
                <legend>Uicación del local</legend>
                 <i class="bi bi-geo-alt-fill"></i> <span>php</span>
              </fieldset>
            <details class="mi-detalle">
              <summary>
                Vehículo a reparar 
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="vehiculo">
                <div class="badge-vehiculo">
                  <i class="fa-solid fa-car"></i>
                  <p class="texto-placa">php</p>
                </div>
                <span>php</span>
              </div>
            </details>
            <details class="mi-detalle">
              <summary>
                <span><i class="bi bi-tools" style="margin-right: 6px;"></i>Detalles de la cita</span>
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="detalles-contenido">
                <div class="bloque-detalle">
                  options <strong>Problema reportado:</strong>
                  <p class="texto-problema">"php"</p>
                </div>
                <div class="bloque-detalle">
                  <strong>Servicios solicitados:</strong>
                  <ul class="lista-servicios">
                    <li><span>php</span> <strong>php</strong></li>
                    <li><span>php</span> <strong>php</strong></li>
                  </ul>
                </div>
              </div>
            </details>
          </div>

          <div class="informacion-segundaria">

            <div class="gestion-inicial">
              <h2 class="subtitulo-taller">
                <i class="bi bi-toggles icono-naranja"></i> Control inicial
              </h2>
              <div class="acciones">
              <small>Puedes cancelarla antes que confirmen la reserva</small>
                <button class="btn1 rechazar" id="btnMostrar"><i class="bi bi-x-circle"></i>cancelar</button>
                <form action="" id="formulario">
                  <input type="text" placeholder="Motivo">
                  <button type="button" class="btn1 cancelar">Enviar</button>
                </form>
              </div>
            </div>
            
          </div>
        </div>



        <!-- CASILLA 2 — contiene el código de verificación -->
        <div class="casilla" id="casilla-CT02-7K9X" data-codigo="CT02-7K9X" data-num="34">
          <div class="informacion-principal">
             <div class="encabezado">
              <div class="estado en-espera" id="estado-CT02-7K9X">php</div>
              <div class="fecha-registro">
                <strong>Confirmada el:</strong><span>php</span>
              </div>
            </div>
            <div class="fecha-cita">
              <fieldset>
                <legend>Fecha programada</legend>
                <span>php</span> a las <span>php</span>
              </fieldset>
            </div>
              <div class="cliente">
              <fieldset>
                <legend>Datos de contacto</legend>
                <i class="bi bi-person-gear icon-usuario"></i>
                <div class="usuario-info">
                  <span class="nombre">php</span>
                  <small class="email">php</small>
                </div>
              </fieldset>
               <fieldset>
                <legend>Taller solicitado</legend>
                <div class="usuario-info">
                  <span class="taller">php</span>
                  <small class="especial">php</small>
                </div>
              </fieldset>
            </div>
             <fieldset>
                <legend>Uicación del local</legend>
                 <i class="bi bi-geo-alt-fill"></i> <span>php</span>
              </fieldset>
            <details class="mi-detalle">
              <summary>
                Vehículo a reparar 
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="vehiculo">
                <div class="badge-vehiculo">
                  <i class="fa-solid fa-car"></i>
                  <p class="texto-placa">php</p>
                </div>
                <span>php</span>
              </div>
            </details>
             <details class="mi-detalle">
              <summary>
                <span><i class="bi bi-tools" style="margin-right: 6px;"></i>Detalles de la cita</span>
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="detalles-contenido">
                <div class="bloque-detalle">
                  <strong>Problema reportado:</strong>
                  <p class="texto-problema">php>
                </div>
              </div>
            </details>
          </div>

          <div class="informacion-segundaria">
            <div class="gestion-inicial">
              <h2 class="subtitulo-taller">
                <i class="bi bi-toggles icono-naranja"></i> Control medio </h2>
                <small>(Presenta este código al llegar al taller)</small>
              <div class="accion-code">
                php
               <p>CT02-7K9X</p> 
              </div>
            </div>
          </div>
        </div>


         <!-- CASILLA 3 — PROCESO DE CIERRE LERO LERO -->
        <div class="casilla">
          <div class="informacion-principal">
             <div class="encabezado">
              <div class="estado cancelada">En cierre</div>
              <div class="fecha-registro">
                <strong>Fecha de cierre:</strong><span>php</span>
              </div>
            </div>
            <div class="fecha-cita">
              <fieldset>
                <legend>Fecha programada</legend>
                <span>php</span> a las <span>php</span>
              </fieldset>
            </div>
              <div class="cliente">
              <fieldset>
                <legend>Datos de contacto</legend>
                <i class="bi bi-person-gear icon-usuario"></i>
                <div class="usuario-info">
                  <span class="nombre">php</span>
                  <small class="email">php</small>
                </div>
              </fieldset>
               <fieldset>
                <legend>Taller solicitado</legend>
                <div class="usuario-info">
                  <span class="taller">php</span>
                  <small class="especial">php</small>
                </div>
              </fieldset>
            </div>
             <fieldset>
                <legend>Uicación del local</legend>
                 <i class="bi bi-geo-alt-fill"></i> <span>php</span>
              </fieldset>
            <details class="mi-detalle">
              <summary>
                Vehículo a reparar 
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="vehiculo">
                <div class="badge-vehiculo">
                  <i class="fa-solid fa-car"></i>
                  <p class="texto-placa">php</p>
                </div>
                <span>php</span>
              </div>
            </details>
             <details class="mi-detalle">
              <summary>
                <span><i class="bi bi-tools" style="margin-right: 6px;"></i>Detalles de la cita</span>
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="detalles-contenido">
                <div class="bloque-detalle">
                  <strong>Problema reportado:</strong>
                  <p class="texto-problema">php</p>
                </div>
              </div>
            </details>
              <details class="mi-detalle">
              <summary>
                <span><i class="bi bi-archive-fill" style="margin-right: 6px;"></i>Detalles de cierre</span>
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="detalles-contenido">
                <fieldset>
                <legend>Obsercaciones</legend>
               php
               </fieldset>
        
                 <fieldset>
                <legend>Equipo de reparación</legend>
              <li>php</li>
              <li>  php</li>
               </fieldset>
                <div class="precio-garantia">
                <fieldset>
                <legend>Garantia</legend>
                php
               </fieldset>
               <fieldset>
                <legend>Precio total</legend>
              php
               </fieldset>
               </div>
              </div>
            </details>
          </div>

          <div class="informacion-segundaria">
             <div class="gestion-inicial">
              <h2 class="subtitulo-taller">
               <i class="bi bi-check-circle-fill alvertencia"></i> Control de cierre </h2>
                <small>(Presenta este código al llegar al taller para finalizar proceso)</small>
              <div class="accion-codef">
               <p class="final">CT02-7K9X</p> 
              </div>
            </div>
          </div>
        </div>

      </div>

      <footer class="stats-footer">
        <div>
          <strong>Mostrando 1-2 de 2 Reservas</strong>
        </div>
        <div class="pagination">
          <button class="page-btn">«</button>
          <button class="page-btn active">1</button>
          <button class="page-btn">»</button>
        </div>
      </footer>
    </section>
  </main>



<?php echo $this->endSection()?>
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/pro_citas.js') ?>"></script>
<?= $this->endSection() ?>