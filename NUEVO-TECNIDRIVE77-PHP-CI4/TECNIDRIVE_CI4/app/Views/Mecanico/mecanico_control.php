 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_control.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_mecanico') ?>

<!-- CODIGO DE CITA:CT02-7K9X -->
  <main class="main-content">
    <header class="titulos">
      <h1 class="titulo">Historial y seguimiento de citas</h1>
      <h5>Controla la citas atendidas y haz el proceso de cierre</h5>
    </header>

    <section class="dashboard-section">
      
      <div class="controles">
        <div class="buscador-wrapper">
          <input type="text" placeholder="Buscar por placa de vehiculo o fecha">
          <i class="bi bi-search"></i>
        </div>


         <div class="verificar-wrapper">
          <button class="btn-lista">Lista de trabajadores</button>
          <div id="trabajadores">
            <ul id="lista-trabajadores">
                <li>Miguiel zanchez perez <button class="btn-x-eliminar" onclick="eliminarTrabajador(this)" title="Eliminar">×</button></li>
                <li>Marleno garabito ñino <button class="btn-x-eliminar" onclick="eliminarTrabajador(this)" title="Eliminar">×</button></li>
            </ul>
            <div id="input-agregar" style="display:none;">
              <input type="text" id="nombre-nuevo-trabajador" placeholder="Nombre del trabajador">
            </div>
            <div class="agrega">
              <button type="button" class="agregar" id="btn-agregar-trabajador">Agregar trabajador</button>
            </div>
          </div>
        </div>

        <select class="filtro-select">
          <option value="todos">Opciones de control</option>
            <option value="pendientes">Citas en atencion</option>
              <option value="pendientes">Citas en cierre</option>
          <option value="pendientes">Citas finalizadas</option>
          <option value="pendientes">Garantias activas</option>
        </select>

        <div class="verificar-wrapper">
          <button class="escaner" id="btnVerificar"><i class="bi bi-qr-code-scan"></i>Finalizar cita</button>
          <form action="" id="formVerificar">
            <input type="text" placeholder="Codigo de cita">
            <button type="submit">Finalizar</button>
          </form>
        </div>
      </div>

      <div class="tabla-calificaciones">
        
        <div class="casilla">
          <div class="informacion-principal">
            <div class="encabezado">
              <div class="estado">En atención</div>
              <div class="fecha-registro">
                <strong>Fecha de registro:</strong><span>12/01/2026</span>
              </div>
            </div>
          
            <div class="fecha-cita">
              <fieldset>
                <legend>Fecha de atencíon</legend>
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
               <i class="bi bi-check-circle icono-naranja"></i> Gestión de cierre
              </h2>
              <div class="acciones">
                <button class="btn1 rechazar" id="btnMostrar"><i class="bi bi-archive"></i>Proceso de cierre</button>
                <form action="" id="formulario">
                    <h3>Datos de Pre-cierre de cita</h3>
                  <input type="text" placeholder="Observaciones (Opcional)">
                 
                    <div class="garantia">
                      <label for="">Garantía del servicio (si no, dejar vacío)
                       <input type="text"  placeholder="Contexto de garantia">
                       vigencia de garantia
                       <input type="date">
                      </label>
                    </div>
                    <div  class="garantia">
                      <label for="">Precio total
                        <input type="text" placeholder="$ 0.000">
                      </label>
                    </div>
                  
                     <div  class="garantia elegir">
                      <label >Trabajadores en la reparación</label>
                      <label for="" class="opciones"><input type="checkbox"><span>Miguel</span> </label>
                        <label for="" class="opciones"><input type="checkbox"><span>Miguel</span> </label>
                    </div>
     



                  <button type="button" class="btn1 cancelar">Guardar datos</button>
                </form>

              </div>
            </div>
            
          </div>
        </div>


        <!-- CASILLA 2 — contiene el código de verificación -->
        <div class="casilla" >
          <div class="informacion-principal">
             <div class="encabezado">
              <div class="estado en-espera">    En cierre</div>
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

             <details class="mi-detalle">
              <summary>
                <span><i class="bi bi-archive-fill" style="margin-right: 6px;"></i>Detalles de cierre</span>
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="detalles-contenido">
               <fieldset>
                <legend>Obsercaciones</legend>
                El vehiculo presento tal y tal cosa pero lo logramos reparar ya no suena
               </fieldset>
              
              
                 <fieldset>
                <legend>Equipo de reparación</legend>
              <li>Miguel iraldo</li>
              <li>  Pedro pascal</li>
               </fieldset>
                <div class="precio-garantia">
                <fieldset>
                <legend>Garantia</legend>
                Sin garantias
               </fieldset>
               <fieldset>
                <legend>Precio total</legend>
               30.0000
               </fieldset>
               </div>
              </div>
            </details>
          </div>
            <div class="gestion-inicial">
              <h2 class="subtitulo-taller">
               Pasos de cierre 
              </h2>
              <small>(Avisar al cliente de la finalización de la cita y escanear el código que dé el cliente)</small>
            </div>
        </div>




        <!-- CASILLA 3 CASILLA DE FINALIZADA -->
        <div class="casilla" >
          <div class="informacion-principal">
             <div class="encabezado">
              <div class="estado finalizada">Finalizada</div>
              <div class="fecha-registro">
                <strong>Fecha de cierre:</strong><span>12/01/2026</span>
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
             <details class="mi-detalle">
              <summary>
                <span><i class="bi bi-archive-fill" style="margin-right: 6px;"></i>Detalles de cierre</span>
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
              </summary>
              <div class="detalles-contenido">
                <fieldset>
                <legend>Obsercaciones</legend>
                El vehiculo presento tal y tal cosa pero lo logramos reparar ya no suena
               </fieldset>
        
                 <fieldset>
                <legend>Equipo de reparación</legend>
              <li>Miguel iraldo</li>
              <li>  Pedro pascal</li>
               </fieldset>
                <div class="precio-garantia">
                <fieldset>
                <legend>Garantia</legend>
                Sin garantias
               </fieldset>
               <fieldset>
                <legend>Precio total</legend>
               30.0000
               </fieldset>
               </div>
              </div>
            </details>
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
<script src="<?= base_url('assets/js/mecanico_control.js') ?>"></script>
<?= $this->endSection() ?>