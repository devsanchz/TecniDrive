 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_taller.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_pro') ?>
    <main class="main-content">

        <!-- Títulos de la sección -->
        <header class="titulos">
            <h1 class="titulo">Talleres Mecánicos</h1>
            <h4>Encuentra el mejor taller para tu vehículo</h4>
        </header>

        <section class="dashboard-section">

            <!-- Tarjetas de estadísticas por puntuación -->
            <div class="numeros-container">
                <div class="tarjeta-cantidad">
                    <span class="num">Talleres</span><br>
                    <span>5 <i class="bi bi-star-fill star-activa"></i></span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num">Talleres</span><br>
                    <span>3 a 4 <i class="bi bi-star-fill star-activa"></i></span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num">Talleres</span><br>
                    <span>2 a 1 <i class="bi bi-star-fill star-activa"></i></span>
                </div>
            </div>

            <!-- Buscador y filtro de especialidad -->
            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar servicios, dirección o especialidad...">
                    <i class="bi bi-search"></i>
                </div>
                <select class="filtro-select">
                    
                    <option value="todos">php</option>
                    <option value="mecanica-general">Mecánica general</option>
                    <option value="electricidad">Electricidad automotriz</option>
                    <option value="latoneria">Latonería y pintura</option>
                    <option value="llantas">Llantas y alineación</option>
                    <option value="aceite">Cambio de aceite</option>
                    <option value="frenos">Frenos</option>
                    <option value="aire">Aire acondicionado</option>
                    <option value="motos">Especialistas en motos</option>
                    <option value="carros">Especialistas en carros</option>
                </select>
            </div>

            <!-- =============================================
                 LISTA DE TARJETAS DE TALLERES
                 Cada tarjeta tiene vista compacta y expandida
                 ============================================= -->
            <div class="lista-talleres">

                <!-- TARJETA DE TALLER -->
                <article class="card-taller compacta" id="cardTaller">

                    <!-- Encabezado con imagen (siempre visible, se puede clicar) -->
                    <header class="header-taller" id="headerTaller">

                        <!-- Botón para cerrar/colapsar la tarjeta (solo visible al expandir) -->
                        <button class="btn-cerrar" id="btnCerrar" aria-label="Cerrar panel">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <!-- Imagen principal del taller -->
                        <img src="php" alt="Instalaciones del Taller Maestro Mecánico" class="imagen-banner">

                        <!-- Nombre y especialidad sobre la imagen -->
                        <div class="overlay-info">
                            <h1>php</h1>
                            <span class="badge-especialidad">php</span>
                        </div>

                        <!-- Texto flotante que aparece al pasar el mouse -->
                        <span class="hover-hint">
                            <i class="bi bi-arrows-angle-expand"></i> Ver más detalles
                        </span>
                    </header>

                    <!-- Vista compacta: resumen rápido del taller -->
                    <div class="vista-compacta" id="vistaCompacta">
                        <p class="direccion-texto">
                            <i class="bi bi-geo-alt-fill"></i>
                            <strong>Ubicación:</strong>php
                        </p>
                        <ul class="servicios">
                            <li>php</li>
                            <li>php</li>
                            <li>php</li>
                            <li>php</li>
                        </ul>
                        <p class="texto-descripcion">php</p>
                    </div>

                    <!-- Contenido completo (oculto al inicio, aparece al expandir) -->
                    <div class="contenido-taller" id="contenidoCompleto">

                        <!-- Descripción -->
                        <section class="seccion-detalle">
                            <h2 class="subtitulo-taller">Descripción</h2>
                            <div class="linea-decorativa"></div>
                            <p class="texto-descripcion">php.</p>
                        </section>

                        <!-- Horarios y ubicación -->
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

                        <!-- Tabla de precios -->
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

                        <!-- Calificaciones de otros usuarios -->
                        <section class="seccion-detalle">
                            <h2 class="subtitulo-taller">
                                <i class="bi bi-star-half"></i> Calificaciones
                            </h2>
                            <div class="linea-decorativa"></div>

                            <!-- Comentario de ejemplo -->
                            <div class="bloque-comentario">
                                <div class="usuario-header">
                                    <i class="bi bi-person-check avatar-icono"></i>
                                    <div class="usuario-meta">
                                        <div class="nombre-estrellas-fila">
                                            <strong class="nombre-usuario">php</strong>
                                            <div class="estrellas-grupo">php
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

                            <!-- Sección para que el usuario deje su calificación -->
                            <div class="tu-calificacion">
                                <h5>Califica este Taller</h5>
                                <small>Comparte tu opinión con los demás</small>

                                <!-- Estrellas interactivas (el clic muestra el campo de reseña) -->
                                <div class="calificacion-estrellas">
                                    php
                                    <i class="bi bi-star-fill star" data-value="1"></i>
                                    <i class="bi bi-star-fill star" data-value="2"></i>
                                    <i class="bi bi-star-fill star" data-value="3"></i>
                                    <i class="bi bi-star-fill star" data-value="4"></i>
                                    <i class="bi bi-star-fill star" data-value="5"></i>
                                </div>

                                <!-- Campo de reseña: oculto hasta que el usuario elige una estrella -->
                                <div class="bloque-resena oculto-js">
                                    <input type="text" placeholder="Tu reseña (opcional)">
                                    <button class="btn-guardar-cali" type="button">Publicar</button>
                                </div>
                            </div>
                        </section>

                        <!-- Formulario para agendar una cita -->
                        <section class="seccion-detalle">
                            <h2 class="subtitulo-taller">
                                <i class="bi bi-calendar2-plus"></i> Agendar cita
                            </h2>
                            <div class="linea-decorativa"></div>

                            <div class="cita">

                                <!-- Selección del vehículo -->
                                <div class="seccion-bloque">
                                    <label class="los-top">Vehículo a reparar</label>
                                    <div class="vehiculos">
                                        <div class="casilla-vehiculo activo">
                                            <i class="bi bi-car-front-fill"></i>
                                            <strong>php</strong>
                                        </div>
                                        <div class="casilla-vehiculo">
                                            <i class="bi bi-bicycle"></i>
                                            <strong>php</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Problema y servicio requerido -->
                                <div class="fila-formulario">
                                    <div class="grupo-input">
                                        <label class="los-top">
                                            El problema del vehículo
                                            <span class="aclaracion">(Opcional)</span>
                                        </label>
                                        <input type="text" class="los-input" placeholder="Si no sabes el servicio, escribe el problema">
                                    </div>
                                    <div class="grupo-input">
                                        <label class="los-top">
                                            Servicios requeridos
                                            <span class="aclaracion">(Opcional si hay problema)</span>
                                        </label>
                                        <select class="los-input">php
                                            <option value="" disabled selected>Selecciona un servicio</option>
                                            <option value="suspension">Suspensión</option>
                                            <option value="frenos">Sistema de frenos</option>
                                            <option value="transmision">Transmisión</option>
                                            <option value="motor">Revisión de motor</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Fecha y hora de la cita -->
                                <div class="fila-formulario">
                                    <div class="grupo-input">
                                        <label class="los-top">Fecha de la cita</label>
                                        <input class="los-input" type="date">
                                    </div>
                                    <div class="grupo-input">php
                                        <label class="los-top">Horas disponibles</label>
                                        <select class="los-input">
                                            <option value="" disabled selected>Horas de atención</option>
                                            <option value="10:22">10:22 am</option>
                                            <option value="12:00">12:00 pm</option>
                                        </select>
                                    </div>
                                </div>

                                <button class="bt-cita" type="button">Solicitar Reserva</button>
                            </div>
                        </section>

                    </div><!-- /contenido-taller -->

                </article><!-- /card-taller -->

            </div><!-- /lista-talleres -->

            <!-- Pie de página con paginación -->
            <footer class="stats-footer">
                <div>
                    <strong>Mostrando 1-3 de 3 Talleres</strong>
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
<script src="<?= base_url('assets/js/pro_taller.js') ?>"></script>
<?= $this->endSection() ?>