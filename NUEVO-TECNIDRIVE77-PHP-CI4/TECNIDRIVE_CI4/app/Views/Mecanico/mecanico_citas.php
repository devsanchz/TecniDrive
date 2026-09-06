 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_citas.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_mecanico') ?>

 <!-- CODIGO DE CITA:CT02-7K9X -->
  <main class="main-content">
    <header class="titulos">
      <h1 class="titulo">Agenda de citas solicitadas</h1>
      <h5>Revisa y controla el proceso inicial de las citas de tus clientes</h5>
    </header>

    <section class="dashboard-section">
      
      <div class="controles">
        <div class="buscador-wrapper">
          <input type="text" placeholder="Buscar por fechas">
          <i class="bi bi-search"></i>
        </div>

        <select class="filtro-select">
          <option value="todos">Todas</option>
          <option value="pendientes">Pendientes</option>
          <option value="rechazadas">Confirmadas</option>
          <option value="rechazadas">No asistidas</option>
          <option value="rechazadas">Canceladas</option>
        </select>

        <div class="verificar-wrapper">
          <button class="escaner" id="btnVerificar"><i class="bi bi-qr-code-scan"></i>Verificar cita</button>
          <form action="" id="formVerificar">
            <input type="text" placeholder="Codigo de cita">
            <button type="submit">Iniciar Atencion</button>
          </form>
        </div>
      </div>

      <div class="tabla-calificaciones">
        
        <div class="casilla">
          <div class="informacion-principal">
            <div class="encabezado">
              <div class="estado">Solicitud Pendiente</div>
              <div class="fecha-registro">
                <strong>Fecha de registro:</strong><span>12/01/2026</span>
              </div>
            </div>
          
            <div class="fecha-cita">
              <fieldset>
                <legend>Fecha programada</legend>
                <span>12/06/2026</span> a las <span>9:30am</span>
              </fieldset>
            </div>
            <div class="cliente">
              <fieldset>
                <legend>Cliente</legend>
                <i class="bi bi-person-check icon-usuario"></i>
                <div class="usuario-info">
                  <span class="nombre">Pedro Sanchez</span>
                  <small class="email">312 358 923</small>
                </div>
              </fieldset>
            </div>
            <details class="mi-detalle">
              <summary>
                Vehículo a reparar 
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="vehiculo">
                <div class="badge-vehiculo">
                  <i class="fa-solid fa-car"></i>
                  <p class="texto-placa">ACB 123</p>
                </div>
                <span>Toyota Corolla 2022</span>
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
                  <p class="texto-problema">"Mi vehiculo presenta ruidos extraños en el motor y bota aceite."</p>
                </div>
                <div class="bloque-detalle">
                  <strong>Servicios solicitados:</strong>
                  <ul class="lista-servicios">
                    <li><span>Sistema de frenos</span> <strong>Desde $30.000</strong></li>
                    <li><span>Transmisión</span> <strong>Desde $35.000</strong></li>
                  </ul>
                </div>
              </div>
            </details>
          </div>

          <div class="informacion-segundaria">

            <div class="gestion-inicial">
              <h2 class="subtitulo-taller">
                <i class="bi bi-toggles icono-naranja"></i> Gestión inicial
              </h2>
              <div class="acciones">
                <button class="btn1 aceptar"><i class="bi bi-check2-circle"></i>Aceptar reserva</button>
                <button class="btn1 rechazar" id="btnMostrar"><i class="bi bi-x-circle"></i>Rechazar</button>
                <form action="" id="formulario">
                  <input type="text" placeholder="Motivo de rechazo">
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
              <div class="estado en-espera" id="estado-CT02-7K9X">Reserva confirmada</div>
              <div class="fecha-registro">
                <strong>Fecha de registro:</strong><span>12/01/2026</span>
              </div>
            </div>
            <div class="fecha-cita">
              <fieldset>
                <legend>Fecha programada</legend>
                <span>15/06/2026</span> a las <span>11:00am</span>
              </fieldset>
            </div>
            <div class="cliente">
              <fieldset>
                <legend>Cliente</legend>
                <i class="bi bi-person-check icon-usuario"></i>
                <div class="usuario-info">
                  <span class="nombre">Maria López</span>
                  <small class="email">312 358 923</small>
                </div>
              </fieldset>
            </div>
            <details class="mi-detalle">
              <summary>
                Vehículo a reparar 
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="vehiculo">
                <div class="badge-vehiculo">
                  <i class="fa-solid fa-car"></i>
                  <p class="texto-placa">KMS 789</p>
                </div>
                <span>Mazda 3 2021</span>
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
                  <p class="texto-problema">"Revisión general de los 50,000 kilómetros y cambio de pastillas."</p>
                </div>
              </div>
            </details>
          </div>

          <div class="informacion-segundaria">
            <div class="gestion-inicial">
              <h2 class="subtitulo-taller">
                <i class="bi bi-toggles icono-naranja"></i> Gestión media <small>(Si el cliente no llega)</small>
              </h2>
              <div class="acciones">
                <button class="btn1 sin"><i class="bi bi-person-slash"></i>No asistida</button>
              </div>
            </div>

            <!-- Banner de estado "en atención" — oculto por defecto, se activa por JS -->
            <div class="banner-en-atencion" id="banner-CT02-7K9X" role="status" aria-live="polite">
              <i class="bi bi-check-circle-fill banner-icono" aria-hidden="true"></i>
              <span class="banner-texto">Cita 34 en proceso de atención enviada a control de citas</span>
            </div>

          </div>
        </div>


         <!-- CASILLA 3 — cancelada/cerrada -->
        <div class="casilla">
          <div class="informacion-principal">
             <div class="encabezado">
              <div class="estado cancelada">Reserva Cancelada</div>
              <div class="fecha-registro">
                <strong>Fecha de registro:</strong><span>12/01/2026</span>
              </div>
            </div>
            <div class="fecha-cita">
              <fieldset>
                <legend>Fecha programada</legend>
                <span>15/06/2026</span> a las <span>11:00am</span>
              </fieldset>
            </div>
            <div class="cliente">
              <fieldset>
                <legend>Cliente</legend>
                <i class="bi bi-person-check icon-usuario"></i>
                <div class="usuario-info">
                  <span class="nombre">Maria López</span>
                  <small class="email">312 358 923</small>
                </div>
              </fieldset>
            </div>
            <details class="mi-detalle">
              <summary>
                Vehículo a reparar 
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="vehiculo">
                <div class="badge-vehiculo">
                  <i class="fa-solid fa-car"></i>
                  <p class="texto-placa">KMS 789</p>
                </div>
                <span>Mazda 3 2021</span>
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
                  <p class="texto-problema">"Revisión general de los 50,000 kilómetros y cambio de pastillas."</p>
                </div>
              </div>
            </details>
          </div>

          <div class="informacion-segundaria">
            <div class="gestion-inicial">
              <h2 class="subtitulo-taller">
              <i class="bi bi-exclamation-triangle alvertencia"></i> Motivo de cancelacion 
              </h2>
              <div class="cierre">
              Cliente no llego
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
<script src="<?= base_url('assets/js/mecanico_citas.js') ?>"></script>
<?= $this->endSection() ?>