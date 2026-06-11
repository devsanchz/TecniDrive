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
                <select class="filtro-select" id="filtroEspecialidad">
                    <option value="todos">Todas las especialidades</option>
                    <?php
                    // Recopilar especialidades únicas de todos los talleres cargados
                    $especialidadesUnicas = [];
                    foreach ($talleres as $t) {
                        foreach ($t['especialidades'] as $esp) {
                            $nombre = $esp['nombre_especialidad'];
                            if (! in_array($nombre, $especialidadesUnicas)) {
                                $especialidadesUnicas[] = $nombre;
                            }
                        }
                    }
                    sort($especialidadesUnicas);
                    foreach ($especialidadesUnicas as $esp):
                    ?>
                        <option value="<?= esc($esp) ?>"><?= esc($esp) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- =============================================
                 LISTA DE TARJETAS DE TALLERES
                 Cada tarjeta se genera por PHP para cada taller activo
                 ============================================= -->
            <div class="lista-talleres" id="listaTalleres">

            <?php if (empty($talleres)): ?>
                <p style="text-align:center; color:#888; padding:40px 0;">
                    No hay talleres activos registrados por el momento.
                </p>

            <?php else: ?>
            <?php foreach ($talleres as $t):
                $especialidad = $t['especialidades'][0]['nombre_especialidad'] ?? 'Sin especialidad';
                $foto = $t['foto_taller']
                    ? base_url('uploads/talleres/' . $t['foto_taller'])
                    : base_url('assets/img/taller-placeholder.jpg');
                $horarios = array_filter(array_map('trim', explode('|', $t['horario_taller'])));
            ?>

                <article class="card-taller compacta"
                         data-especialidad="<?= esc($especialidad) ?>"
                         data-nombre="<?= esc(strtolower($t['nombre_taller'])) ?>"
                         data-direccion="<?= esc(strtolower($t['direccion_taller'])) ?>">

                    <header class="header-taller">
                        <button class="btn-cerrar" aria-label="Cerrar panel">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <img src="<?= esc($foto) ?>"
                             alt="Foto de <?= esc($t['nombre_taller']) ?>"
                             class="imagen-banner">
                        <div class="overlay-info">
                            <h1><?= esc($t['nombre_taller']) ?></h1>
                            <span class="badge-especialidad"><?= esc($especialidad) ?></span>
                        </div>
                        <span class="hover-hint">
                            <i class="bi bi-arrows-angle-expand"></i> Ver más detalles
                        </span>
                    </header>

                    <!-- Vista compacta -->
                    <div class="vista-compacta">
                        <p class="direccion-texto">
                            <i class="bi bi-geo-alt-fill"></i>
                            <strong>Ubicación:</strong> <?= esc($t['direccion_taller']) ?>
                        </p>
                        <ul class="servicios">
                            <?php foreach (array_slice($t['servicios'], 0, 4) as $serv): ?>
                                <li><?= esc($serv['nombre_servicio']) ?></li>
                            <?php endforeach; ?>
                            <?php if (count($t['servicios']) > 4): ?>
                                <li>+<?= count($t['servicios']) - 4 ?> más...</li>
                            <?php endif; ?>
                        </ul>
                        <p class="texto-descripcion"><?= esc($t['descripcion_taller']) ?></p>
                    </div>

                    <!-- Contenido completo -->
                    <div class="contenido-taller">

                        <section class="seccion-detalle">
                            <h2 class="subtitulo-taller">Descripción</h2>
                            <div class="linea-decorativa"></div>
                            <p class="texto-descripcion"><?= esc($t['descripcion_taller']) ?></p>
                        </section>

                        <section class="seccion-detalle">
                            <h2 class="subtitulo-taller">Horarios y Lugar</h2>
                            <div class="linea-decorativa"></div>
                            <p class="direccion-texto">
                                <i class="bi bi-geo-alt-fill"></i>
                                <strong>Ubicación del local:</strong> <?= esc($t['direccion_taller']) ?>
                            </p>
                            <ul class="lista-horarios">
                                <?php foreach ($horarios as $bloque):
                                    $partes = explode(': ', $bloque, 2);
                                    $dia    = trim($partes[0] ?? $bloque);
                                    $hora   = trim($partes[1] ?? '');
                                    $esCerrado = stripos($hora, 'cerrado') !== false;
                                ?>
                                <li>
                                    <span class="dia-semana"><?= esc($dia) ?></span>
                                    <span class="bloque-hora <?= $esCerrado ? 'cerrado' : '' ?>">
                                        <?= esc($hora) ?>
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>

                        <section class="seccion-detalle">
                            <h2 class="subtitulo-taller">Precios de Servicios</h2>
                            <div class="linea-decorativa"></div>
                            <table class="tabla-precios">
                                <thead>
                                    <tr><th>Servicio</th><th>Precio base</th></tr>
                                </thead>
                                <tbody>
                                    <?php if ($t['servicios']): ?>
                                        <?php foreach ($t['servicios'] as $serv): ?>
                                        <tr>
                                            <td class="celda-servicio"><?= esc($serv['nombre_servicio']) ?></td>
                                            <td class="celda-precio">$<?= number_format($serv['precio_servicio'], 0, ',', '.') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="2" style="text-align:center;color:#888;">Sin precios registrados</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </section>

                        <section class="seccion-detalle"
                                 data-taller-id="<?= $t['id_taller'] ?>">

                            <!-- Cabecera con promedio -->
                            <h2 class="subtitulo-taller">
                                <i class="bi bi-star-half"></i> Calificaciones
                                <?php if ($t['promedio'] > 0): ?>
                                    <span class="badge-promedio">
                                        <?= $t['promedio'] ?> <i class="bi bi-star-fill" style="color:#f1c40f;font-size:13px;"></i>
                                        &nbsp;<small style="font-weight:400;font-size:13px;color:#888;">(<?= count($t['calificaciones']) ?>)</small>
                                    </span>
                                <?php endif; ?>
                            </h2>
                            <div class="linea-decorativa"></div>

                            <!-- Calificaciones existentes -->
                            <div class="lista-calificaciones">
                                <?php if (empty($t['calificaciones'])): ?>
                                    <p class="sin-calis">Aún no hay calificaciones. ¡Sé el primero!</p>
                                <?php else: ?>
                                    <?php foreach ($t['calificaciones'] as $cali): ?>
                                    <div class="bloque-comentario">
                                        <div class="usuario-header">
                                            <span class="avatar-icono">
                                                <i class="bi bi-person-circle"></i>
                                            </span>
                                            <div class="usuario-meta">
                                                <div class="nombre-estrellas-fila">
                                                    <span class="nombre-usuario">
                                                        <?= esc($cali['primer_nombre'] . ' ' . $cali['primer_apellido']) ?>
                                                    </span>
                                                    <!-- Estrellas estáticas de la puntuación guardada -->
                                                    <span class="estrellas-grupo">
                                                        <?php for ($s = 1; $s <= 5; $s++): ?>
                                                            <i class="bi bi-star-fill"
                                                               style="font-size:13px;color:<?= $s <= $cali['puntuacion'] ? '#f1c40f' : '#ddd' ?>;"></i>
                                                        <?php endfor; ?>
                                                    </span>
                                                </div>
                                                <span class="fecha-comentario">
                                                    <?= date('d/m/Y', strtotime($cali['fecha_registro'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php if ($cali['comentario']): ?>
                                            <p class="comentario-texto"><?= esc($cali['comentario']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Formulario de calificación propia -->
                            <div class="tu-calificacion">
                                <?php if ($t['ya_califico']): ?>
                                    <!-- Ya calificó: mostrar mensaje, ocultar formulario -->
                                    <p class="ya-califico-msg">
                                        <i class="bi bi-check-circle-fill" style="color:#28a745;"></i>
                                        Ya calificaste este taller.
                                    </p>
                                <?php else: ?>
                                    <h5>Califica este Taller</h5>
                                    <small>Comparte tu opinión con los demás</small>

                                    <!--
                                        data-taller-id: leído por JS para saber a qué taller
                                        pertenece el fetch. Se duplica aquí para tenerlo
                                        accesible desde el contexto del formulario.
                                    -->
                                    <div class="calificacion-estrellas"
                                         data-taller-id="<?= $t['id_taller'] ?>">
                                        <i class="bi bi-star-fill star" data-value="1"></i>
                                        <i class="bi bi-star-fill star" data-value="2"></i>
                                        <i class="bi bi-star-fill star" data-value="3"></i>
                                        <i class="bi bi-star-fill star" data-value="4"></i>
                                        <i class="bi bi-star-fill star" data-value="5"></i>
                                    </div>

                                    <div class="bloque-resena oculto-js">
                                        <!-- input oculto que guarda la puntuación elegida -->
                                        <input type="hidden" class="input-puntuacion" value="0">
                                        <input type="text"
                                               class="input-comentario"
                                               placeholder="Tu reseña (opcional)">
                                        <button class="btn-guardar-cali" type="button">Publicar</button>
                                        <p class="cali-error" style="display:none;color:#dc3545;font-size:13px;"></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </section>

                        <section class="seccion-detalle">
                            <h2 class="subtitulo-taller"><i class="bi bi-calendar2-plus"></i> Agendar cita</h2>
                            <div class="linea-decorativa"></div>
                            <div class="cita">
                                <div class="fila-formulario">
                                    <div class="grupo-input">
                                        <label class="los-top">El problema del vehículo <span class="aclaracion">(Opcional)</span></label>
                                        <input type="text" class="los-input" placeholder="Si no sabes el servicio, escribe el problema">
                                    </div>
                                    <div class="grupo-input">
                                        <label class="los-top">Servicios requeridos <span class="aclaracion">(Opcional si hay problema)</span></label>
                                        <select class="los-input">
                                            <option value="" disabled selected>Selecciona un servicio</option>
                                            <?php foreach ($t['servicios'] as $serv): ?>
                                                <option value="<?= $serv['servicios_id_servicio'] ?>"><?= esc($serv['nombre_servicio']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="fila-formulario">
                                    <div class="grupo-input">
                                        <label class="los-top">Fecha de la cita</label>
                                        <input class="los-input" type="date">
                                    </div>
                                    <div class="grupo-input">
                                        <label class="los-top">Hora</label>
                                        <input class="los-input" type="time">
                                    </div>
                                </div>
                                <button class="bt-cita" type="button">Solicitar Reserva</button>
                            </div>
                        </section>

                    </div><!-- /contenido-taller -->
                </article><!-- /card-taller -->

            <?php endforeach; ?>
            <?php endif; ?>

            </div><!-- /lista-talleres -->

            <!--
                Fondo oscuro del modal — JS le añade la clase "activo"
                cuando se abre una tarjeta y la quita al cerrarla.
                Al hacer clic sobre él se cierra la tarjeta abierta.
            -->
            <div class="modal-backdrop" id="modalBackdrop"></div>

            <!-- Pie de página con paginación -->
            <footer class="stats-footer">
                <div>
                    <strong>Mostrando <?= $total ?> Taller<?= $total !== 1 ? 'es' : '' ?></strong>
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
<script>
    const CALIFICAR_URL = "<?= site_url('propietario/taller/calificar') ?>";
</script>
<script src="<?= base_url('assets/js/pro_taller.js') ?>"></script>
<?= $this->endSection() ?>