 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_tallerr.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_admin') ?>

 <main class="main-content">
        <header class="titulos">
            <h1 class="titulo">Supervisar Talleres</h1>
            <h5>Revisa y gestionar los talleres registrados</h5>
        </header>

        <section class="dashboard-section">
            <div class="numeros-container">
                <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span> Total de Talleres registrados </span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span> Talleres Desactivados</span>
                </div>
                 <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span>Solicitudes de estado</span>
                </div>
                  
            </div>

            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar por taller, propietario o fecha">
                    <i class="bi bi-search"></i>
                </div>

                <select class="filtro-select">
                    <option value="todos">Todos</option>
                    <option value="pendientes">Recientes</option>
                    <option value="rechazadas">Activados</option>
                    <option value="rechazadas">Desactivados</option>
                    <option value="rechazadas">Solicitudes</option>
                </select>
            </div>

            <table class="tabla-calificaciones">
                <thead>
                    <tr class="cabeza-tabla">
                        <th>Dueño</th>
                        <th>Taller</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                        <th>Fecha de Registro</th>
                        <th>Solicitud de estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fila-cuerpo">
                        <td class="col-propietario">
                            <i class="bi bi-person-gear icon-usuario">php</i>
                            <div class="usuario-info">
                                <span class="nombre">php</span>
                                <small class="email">php</small>
                            </div>
                        </td>
                        <td class="col-taller">
                            <strong>php</strong><br>
                            <span class="subtexto-taller">php</span>
                        </td>
                        <td class="col-estado">
                            <span class="status-badge activo">php</span>
                        </td>
                        <td>
                        <div class="btn-group">
                                <button class="btn-accion btn-detalles">Ver</button>
                            </div>
                            </td>
                        <td class="col-fecha">php<br><small>php</small></td>
                        <td class="col-estado">
                            <span  class="status-badge pendiente0">Solicitudes (php)</span>
                        </td>
                        <td class="col-acciones">
                            <div class="btn-group">
                                <button class="btn-accion btn-aprobar">Activar</button>
                                <button class="btn-accion btn-rechazar">Desactivar</button>
                            </div>
                        </td>
                    </tr>

                    <tr class="fila-cuerpo">
                         <td class="col-propietario">
                            <i class="bi bi-person-gear icon-usuario">php</i>
                            <div class="usuario-info">
                                <span class="nombre">php</span>
                                <small class="email">php</small>
                            </div>
                        </td>
                        <td class="col-taller">
                            <strong>php</strong><br>
                            <span class="subtexto-taller">php</span>
                        </td>
                        <td class="col-estado">
                            <span class="status-badge desactivo">Desactivado</span>
                        </td>
                        <!-- INICIO -->
                          <td>
    <div class="btn-group">
        <button class="btn-accion btn-detalles">Ver</button>
    </div>
</td>

                        <td class="col-fecha">php<br><small>php</small></td>
                       <td class="col-estado">
                            <div class="estado-con-motivo">
                                <span class="status-badge pendiente">Solitudes (php)</span>
                                <i class="bi bi-eye icono-ojo"></i>
                                <div class="tooltip-motivo">
                                    php
                                </div>
                            </div>
                        </td>
                        <td class="col-acciones">
                            <div class="btn-group">
                                <button class="btn-accion btn-aprobar">Activar</button>
                                <button class="btn-accion btn-rechazar">Desactivar</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
             <footer class="stats-footer">
                <div>
                    <strong>Mostrando 1-1 de 2 Talleres</strong>
                </div>
                <div class="pagination">
                    <button class="page-btn">«</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">»</button>
                </div>
            </footer>
        </section>
    </main>


    <!-- ASILLA DEL TALLER LOCO PARA VER CMPLETO POR EL MOMENTO AQUI -->
     <div class="modal-overlay" id="modalTaller">
        <article class="card-taller">
            
            <header class="header-taller">
                <button class="btn-cerrar" aria-label="Cerrar panel">
                    <i class="bi bi-x-lg"></i>
                </button>

                <img src="php" alt="Instalaciones del Taller Maestro Mecánico" class="imagen-banner">
                <div class="overlay-info">
                    <h1>php</h1>
                    <span class="badge-especialidad">php</span>
                </div>
            </header>

            <div class="contenido-taller">
                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">Descripción</h2>
                    <div class="linea-decorativa"></div>
                    <p class="texto-descripcion">php</p>
                </section>

                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">Horarios y Lugar</h2>
                    <div class="linea-decorativa"></div>
                    <p class="direccion-texto">
                        <i class="bi bi-geo-alt-fill"></i> 
                        <strong>Ubicación del local:</strong>php
                    </p>
                    <ul class="lista-horarios">
                        <li>
                            <span class="dia-semana">php</span>
                            <span class="bloque-hora">php</span>
                        </li>
                        <li>
                            <span class="dia-semana">php</span>
                            <span class="bloque-hora">php</span>
                        </li>
                        <li>
                            <span class="dia-semana domingo">php</span>
                            <span class="bloque-hora cerrado">php</span>
                        </li>
                    </ul>
                </section>

                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">Precios de Servicios</h2>
                    <div class="linea-decorativa"></div>
                    <table class="tabla-precios">
                        <thead>
                            <tr>
                                <th>Servicio</th>
                                <th>Precio base</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="celda-servicio">php</td>
                                <td class="celda-precio">php</td>
                            </tr>
                            <tr>
                                <td class="celda-servicio">php</td>
                                <td class="celda-precio">php</td>
                            </tr>
                            <tr>
                                <td class="celda-servicio">php</td>
                                <td class="celda-precio">php</td>
                            </tr>
                            <tr>
                                <td class="celda-servicio">php</td>
                                <td class="celda-precio">php</td>
                            </tr>
                        </tbody>
                    </table>
                </section>

                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">
                        <i class="bi bi-star-half"></i> Calificaciones
                    </h2>
                    <div class="linea-decorativa"></div>
                    <div class="bloque-comentario">
                        <div class="usuario-header">
                            <i class="bi bi-person-check avatar-icono"></i> 
                            <div class="usuario-meta">
                                <div class="nombre-estrellas-fila">
                                    <strong class="nombre-usuario">php</strong> 
                                    <div class="estrellas-grupo">  
                                        php
                                        <i class="bi bi-star-fill star-activa"></i>
                                        <i class="bi bi-star-fill star-activa"></i>
                                        <i class="bi bi-star-fill star-activa"></i>
                                        <i class="bi bi-star-fill star-activa"></i>
                                        <i class="bi bi-star-fill star-activa"></i>
                                       
                                    </div>
                                </div>
                                <small class="fecha-comentario">php</small>
                            </div>
                        </div>
                        <p class="comentario-texto">
                          php
                        </p>
                    </div>
                </section>
            </div> 
        </article>
    </div>
<!-- fin -->





<?php echo $this->endSection()?>
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/admin_taller.js') ?>"></script>
<?= $this->endSection() ?>