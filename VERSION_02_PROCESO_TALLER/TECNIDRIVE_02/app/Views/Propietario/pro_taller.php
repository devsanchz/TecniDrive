<?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_talleres.css') ?>">
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
                <div class="tarjeta-cantidad" data-rango="cinco">
                    <span class="num"><?= $conteos['cinco'] ?></span><br>
                    <span>Talleres 5 <i class="bi bi-star-fill star-activa"></i></span>
                </div>
                <div class="tarjeta-cantidad" data-rango="medio">
                    <span class="num"><?= $conteos['medio'] ?></span><br>
                    <span>Talleres 3 a 4 <i class="bi bi-star-fill star-activa"></i></span>
                </div>
                <div class="tarjeta-cantidad" data-rango="bajo">
                    <span class="num"><?= $conteos['bajo'] ?></span><br>
                    <span>Talleres 2 a 1 <i class="bi bi-star-fill star-activa"></i></span>
                </div>
            </div>

            <!-- Buscador y filtro de especialidad -->
            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar servicios, dirección o especialidad...">
                    <i class="bi bi-search"></i>
                </div>


                <!-- PARTE DE VER TUS CALIFIACIONES DE LOS TALLERES -->
                <button class="misreseñas">
                           Mis puntuaciones de talleres
</button>

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

                /*
                 * $t['mi_calificacion'] ya viene calculado desde el controlador
                 * (CalificacionTallerModel::obtenerMiCalificacion), SIN filtrar
                 * por estado — a diferencia de $t['calificaciones'], que solo
                 * trae las 'aprobada' (por eso buscar aquí con un foreach nunca
                 * encontraba una fila 'rechazada' o 'pendiente').
                 *
                 * $miCalificacion puede venir en estado 'aprobada', 'pendiente'
                 * o 'rechazada'. Solo 'aprobada'/'pendiente' cuentan como
                 * "calificación vigente" (igual que yaCalifico() en el modelo);
                 * una 'rechazada' se trata como si no hubiera calificado, salvo
                 * para mostrarle el aviso de por qué se rechazó.
                 */
                $miCalificacion = $t['mi_calificacion'] ?? null;
                $miCalificacionVigente  = $miCalificacion && $miCalificacion['estado'] !== 'rechazada';
                $miCalificacionRechazada = $miCalificacion && $miCalificacion['estado'] === 'rechazada';
            ?>

                <article class="card-taller compacta"
                         data-especialidad="<?= esc($especialidad) ?>"
                         data-nombre="<?= esc(strtolower($t['nombre_taller'])) ?>"
                         data-direccion="<?= esc(strtolower($t['direccion_taller'])) ?>"
                         data-rango="<?= esc($t['rango'] ?? '') ?>"
                         data-calificado="<?= $miCalificacionVigente ? '1' : '0' ?>">

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

                        <?php
                        // $miCalificacion, $miCalificacionVigente y $miCalificacionRechazada
                        // ya se calcularon al inicio del foreach, justo arriba de la apertura
                        // de <article>, para poder usarlos también en los atributos data-*.
                        ?>

                        <section class="seccion-detalle"
                                 data-taller-id="<?= $t['id_taller'] ?>">

                            <!-- Cabecera con promedio -->
                            <h2 class="subtitulo-taller arriba">
                                <i class="bi bi-star-half"></i> Calificaciones

                                <?php if ($t['promedio'] > 0): ?>
                                    <span class="badge-promedio">
                                        <?= $t['promedio'] ?>
                                        <i class="bi bi-star-fill" style="color:#f1c40f;font-size:13px;"></i>
                                        &nbsp;<small style="font-weight:400;font-size:13px;color:#888;">
                                            (<?= count($t['calificaciones']) ?>)
                                        </small>
                                    </span>
                                <?php endif; ?>

                                <?php if ($miCalificacionVigente): ?>
                                <!-- Botones solo visibles si este propietario tiene una calificación VIGENTE (no rechazada) -->
                                <div class="btn-group">
                                    <button class="btn-accion actualizar"
                                            data-taller-id="<?= $t['id_taller'] ?>"
                                            data-puntuacion="<?= (int)$miCalificacion['puntuacion'] ?>"
                                            data-comentario="<?= esc($miCalificacion['comentario'] ?? '') ?>">
                                        Actualizar
                                    </button>
                                    <button class="btn-accion eliminar"
                                            data-taller-id="<?= $t['id_taller'] ?>">
                                        Eliminar
                                    </button>
                                </div>
                                <?php endif; ?>
                            </h2>
                            <div class="linea-decorativa"></div>

                            <!-- Calificaciones existentes -->
                            <div class="lista-calificaciones">
                                <?php if (empty($t['calificaciones'])): ?>
                                    <p class="sin-calis">Aún no hay calificaciones. ¡Sé el primero!</p>
                                <?php else: ?>
                                    <?php foreach ($t['calificaciones'] as $cali): ?>
                                    <div class="bloque-comentario"
                                         data-propietario-id="<?= (int)$cali['propietarios_id_propietario'] ?>">
                                        <div class="usuario-header">
                                            <?php $colorCali = esc($cali['avatarcolor'] ?? '#000000'); ?>
                                            <span class="avatar-icono" style="color:<?= $colorCali ?>;">
                                                <i class="bi bi-person-check"></i>
                                            </span>
                                            <div class="usuario-meta">
                                                <div class="nombre-estrellas-fila">
                                                    <span class="nombre-usuario">
                                                        <?= esc($cali['primer_nombre'] . ' ' . $cali['primer_apellido']) ?>
                                                    </span>
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

                            <!-- Formulario de calificación -->
                            <div class="tu-calificacion">
                                <?php if ($miCalificacionRechazada): ?>
                                    <!--
                                        Calificación previa RECHAZADA por el administrador.
                                        Se muestra el aviso y un formulario NUEVO (no de edición)
                                        para que el propietario pueda volver a calificar: el
                                        controlador reutiliza esta misma fila en vez de duplicarla.
                                    -->
                                    <p class="cali-rechazada-msg"
                                       style="background:#f8d7da;color:#842029;padding:8px 12px;border-radius:5px;font-size:13px;">
                                        Tu calificación anterior fue rechazada por no ser apropiada.
                                        Puedes intentarlo de nuevo.
                                    </p>
                                    <h5>Califica este Taller</h5>
                                    <small>Comparte tu opinión con los demás</small>
                                    <div class="calificacion-estrellas"
                                         data-taller-id="<?= $t['id_taller'] ?>">
                                        <i class="bi bi-star-fill star" data-value="1"></i>
                                        <i class="bi bi-star-fill star" data-value="2"></i>
                                        <i class="bi bi-star-fill star" data-value="3"></i>
                                        <i class="bi bi-star-fill star" data-value="4"></i>
                                        <i class="bi bi-star-fill star" data-value="5"></i>
                                    </div>
                                    <div class="bloque-resena oculto-js">
                                        <input type="hidden" class="input-puntuacion" value="0">
                                        <input type="text" class="input-comentario"
                                               placeholder="Tu reseña (opcional)">
                                        <button class="btn-guardar-cali" type="button">Publicar</button>
                                        <p class="cali-error" style="display:none;color:#dc3545;font-size:13px;"></p>
                                    </div>

                                <?php elseif ($miCalificacionVigente): ?>
                                    <!-- Modo edición (oculto por defecto, JS lo muestra al pulsar Actualizar) -->
                                    <div class="form-edicion oculto-js">
                                        <h5>Edita tu calificación</h5>
                                        <div class="calificacion-estrellas"
                                             data-taller-id="<?= $t['id_taller'] ?>">
                                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                                <i class="bi bi-star-fill star"
                                                   data-value="<?= $s ?>"
                                                   style="color:<?= $s <= $miCalificacion['puntuacion'] ? '#f1c40f' : '' ?>;"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="bloque-resena">
                                            <input type="hidden" class="input-puntuacion"
                                                   value="<?= (int)$miCalificacion['puntuacion'] ?>">
                                            <input type="text" class="input-comentario"
                                                   placeholder="Tu reseña (opcional)"
                                                   value="<?= esc($miCalificacion['comentario'] ?? '') ?>">
                                            <button class="btn-guardar-cali" type="button">Publicar</button>
                                            <p class="cali-error" style="display:none;color:#dc3545;font-size:13px;"></p>
                                        </div>
                                    </div>

                                <?php else: ?>
                                    <!-- Nunca ha calificado: formulario nuevo -->
                                    <h5>Califica este Taller</h5>
                                    <small>Comparte tu opinión con los demás</small>
                                    <div class="calificacion-estrellas"
                                         data-taller-id="<?= $t['id_taller'] ?>">
                                        <i class="bi bi-star-fill star" data-value="1"></i>
                                        <i class="bi bi-star-fill star" data-value="2"></i>
                                        <i class="bi bi-star-fill star" data-value="3"></i>
                                        <i class="bi bi-star-fill star" data-value="4"></i>
                                        <i class="bi bi-star-fill star" data-value="5"></i>
                                    </div>
                                    <div class="bloque-resena oculto-js">
                                        <input type="hidden" class="input-puntuacion" value="0">
                                        <input type="text" class="input-comentario"
                                               placeholder="Tu reseña (opcional)">
                                        <button class="btn-guardar-cali" type="button">Publicar</button>
                                        <p class="cali-error" style="display:none;color:#dc3545;font-size:13px;"></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </section>


                   <!-- PARTE DE AGENDAR CITA -->
<section class="seccion-detalle seccion-cita">
    <h2 class="subtitulo-taller">
        <i class="bi bi-calendar2-plus"></i> Agendar cita
    </h2>
    <div class="linea-decorativa"></div>

    <?php if (session()->getFlashdata('exito_cita')): ?>
        <div class="alerta-exito"><?= session()->getFlashdata('exito_cita') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error_cita')): ?>
        <div class="alerta-error"><?= session()->getFlashdata('error_cita') ?></div>
    <?php endif; ?>

    <!-- FORM TRADICIONAL: todo se envía por POST al controlador -->
    <form method="post" action="<?= site_url('propietario/taller/agendar-cita') ?>">
        <?= csrf_field() ?>

        <!-- Campo oculto: id del taller -->
        <input type="hidden" name="id_taller" value="<?= $t['id_taller'] ?>">

    <div class="cita">

        <!-- ── 1. ELEGIR VEHÍCULO ─────────────────────────────── -->
        <div class="grupo-input">
            <label class="los-top">
                Vehículo a reparar
                <span class="aclaracion">(Requerido)</span>
            </label>

            <?php if (empty($mis_vehiculos)): ?>
                <p class="aviso-sin-vehiculo">
                    <i class="bi bi-exclamation-circle"></i>
                    No tienes vehículos registrados.
                    <a href="<?= site_url('propietario/vehiculo') ?>">Registrar uno</a>
                </p>
            <?php else: ?>
                <div class="lista-vehiculos-cita">
                   <?php foreach ($mis_vehiculos as $idx => $veh):
    $idInput   = 'veh-' . $t['id_taller'] . '-' . $idx;
    $esActivo  = (bool) $veh['estado_vehi'];
    $ocupado   = (bool) ($veh['tiene_cita_activa'] ?? false); // ← NUEVO
    $bloqueado = !$esActivo || $ocupado;                      // ← NUEVO
?>
    <label class="tarjeta-vehiculo-cita <?= $bloqueado ? 'inactivo' : '' ?>"
           for="<?= $idInput ?>">
        <input type="radio"
               id="<?= $idInput ?>"
               name="placa"
               value="<?= esc($veh['placa']) ?>"
               <?= $bloqueado ? 'disabled' : '' ?>
               required>
        <div class="vehiculo-icono">
            <?php if ((int)$veh['tipos_vehiculo_id_tipo_vehi'] === 2): ?>
                <i class="fa-solid fa-motorcycle"></i>
            <?php else: ?>
                <i class="fa-solid fa-car"></i>
            <?php endif; ?>
        </div>
        <div class="vehiculo-datos">
            <span class="placa-tag"><?= esc($veh['placa']) ?></span>
            <?php if ($ocupado): ?>
                <span class="badge-inactivo">Cita activa</span>   <!-- ← NUEVO -->
            <?php elseif (!$esActivo): ?>
                <span class="badge-inactivo">Inactivo</span>
            <?php endif; ?>
        </div>
    </label>
<?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ── 2. SERVICIOS (checkboxes) ──────────────────────── -->
        <div class="grupo-input">
            <label class="los-top">
                Servicios requeridos
                <span class="aclaracion">(Opcional si ya describiste el problema)</span>
            </label>

            <?php if (empty($t['servicios'])): ?>
                <p style="color:#888;font-size:13px;">Este taller no tiene servicios registrados.</p>
            <?php else: ?>
                <div class="lista-servicios-cita">
                    <?php foreach ($t['servicios'] as $serv): ?>
                        <label class="tarjeta-servicio-cita"
                               for="srv-<?= $t['id_taller'] ?>-<?= $serv['servicios_id_servicio'] ?>">
                            <!-- name="servicios[]" envía array al controlador -->
                            <input type="checkbox"
                                   id="srv-<?= $t['id_taller'] ?>-<?= $serv['servicios_id_servicio'] ?>"
                                   name="servicios[]"
                                   value="<?= $serv['servicios_id_servicio'] ?>">
                            <span class="nombre-servicio"><?= esc($serv['nombre_servicio']) ?></span>
                            <span class="precio-servicio">
                                $<?= number_format($serv['precio_servicio'], 0, ',', '.') ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ── 3. PROBLEMA (opcional) ─────────────────────────── -->
        <div class="grupo-input">
            <label class="los-top">
                Describe el problema
                <span class="aclaracion">(Opcional)</span>
            </label>
            <input type="text"
                   name="problema"
                   class="los-input"
                   placeholder="Si no sabes el servicio exacto, describe el síntoma">
        </div>

        <!-- ── 4. FECHA Y HORA ────────────────────────────────── -->
        <div class="fila-formulario">
            <div class="grupo-input">
                <label class="los-top">Fecha de la cita</label>
                <input type="date"
                       name="fecha_cita"
                       class="los-input"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                       required>
            </div>
            <div class="grupo-input">
                <label class="los-top">Hora</label>
                <input type="time"
                       name="hora_cita"
                       class="los-input"
                       required>
            </div>
        </div>

        <button type="submit" class="bt-cita btn-solicitar-cita">
            Solicitar Reserva
        </button>

    </div>
    </form>
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
<!--
    Token CSRF expuesto para los fetch() JSON de calificar/actualizar/eliminar.
    El formulario de "Agendar cita" no lo necesita porque ya usa csrf_field()
    directamente (formulario tradicional, el navegador lo envía solo).
-->
<meta name="csrf-name" content="<?= csrf_token() ?>">
<meta name="csrf-hash" content="<?= csrf_hash() ?>">
<script>
    const CALIFICAR_URL  = "<?= site_url('propietario/taller/calificar') ?>";
    const ACTUALIZAR_URL = "<?= site_url('propietario/taller/actualizar') ?>";
    const ELIMINAR_URL   = "<?= site_url('propietario/taller/eliminar') ?>";
    const PROPIETARIO_ID = <?= (int) session()->get('usuario_id') ?>;
</script>
<script src="<?= base_url('assets/js/pro_taller02.js') ?>"></script>
<?= $this->endSection() ?>