<?php echo $this->extend('Estructura/diseño'); ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_tallerrr.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_admin') ?>

<main class="main-content">
    <header class="titulos">
        <h1 class="titulo">Supervisar Talleres</h1>
        <h5>Revisa y gestiona los talleres registrados</h5>
    </header>

    <section class="dashboard-section">

        <!-- ── ALERTAS ADMIN ───────────────────────────────────────────────────── -->
        <?php if (session()->getFlashdata('exito_admin')): ?>
            <div class="alerta-exito" role="alert"><?= esc(session()->getFlashdata('exito_admin')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error_admin')): ?>
            <div class="alerta-error" role="alert"><?= esc(session()->getFlashdata('error_admin')) ?></div>
        <?php endif; ?>

        <!-- ── CONTADORES ──────────────────────────────────────────────────────── -->
        <div class="numeros-container">
            <div class="tarjeta-cantidad">
                <span class="num"><?= esc($total) ?></span><br>
                <span>Total de Talleres registrados</span>
            </div>
            <div class="tarjeta-cantidad">
                <span class="num"><?= esc($desactivados) ?></span><br>
                <span>Talleres Desactivados</span>
            </div>
        </div>

        <!-- ── BUSCADOR Y FILTRO ───────────────────────────────────────────────── -->
        <div class="controles">
            <div class="buscador-wrapper">
                <input type="text" id="buscadorTaller"
                       placeholder="Buscar por taller, nombre del dueño...">
                <i class="bi bi-search"></i>
            </div>
            <select class="filtro-select" id="filtroEstado">
                <option value="todos">Todos</option>
                <option value="activo">Activados</option>
                <option value="desactivo">Desactivados</option>
            </select>
        </div>

        <!-- ── TABLA DE TALLERES ───────────────────────────────────────────────── -->
        <table class="tabla-calificaciones">
            <thead>
                <tr class="cabeza-tabla">
                    <th>Dueño</th>
                    <th>Taller</th>
                    <th>Estado</th>
                    <th>Detalles</th>
                    <th>Fecha de Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla">

                <?php if (empty($talleres)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px; color:#888;">
                            No hay talleres registrados aún.
                        </td>
                    </tr>

                <?php else: ?>
                    <?php foreach ($talleres as $taller):
                        $especialidad = $taller['especialidades'][0]['nombre_especialidad'] ?? 'Sin especialidad';
                        $estaActivo   = (bool) $taller['estado_taller'];
                        $fechaReg     = date('d/m/Y', strtotime($taller['fecha_registro']));
                        $horaReg      = date('H:i',   strtotime($taller['fecha_registro']));
                        $colorAvatar  = esc($taller['avatarcolor'] ?? '#000000');
                        $idTaller     = (int) $taller['id_taller'];
                    ?>
                    <tr class="fila-cuerpo"
                        data-nombre="<?= esc(strtolower($taller['primer_nombre'] . ' ' . $taller['primer_apellido'])) ?>"
                        data-taller="<?= esc(strtolower($taller['nombre_taller'])) ?>"
                        data-estado="<?= $estaActivo ? 'activo' : 'desactivo' ?>">

                        <!-- Dueño -->
                        <td class="col-propietario">
                            <i class="bi bi-person-gear icon-usuario"
                               style="color: <?= $colorAvatar ?>;"></i>
                            <div class="usuario-info">
                                <span class="nombre">
                                    <?= esc($taller['primer_nombre'] . ' ' . $taller['primer_apellido']) ?>
                                </span>
                                <small class="email"><?= esc($taller['email']) ?></small>
                            </div>
                        </td>

                        <!-- Taller -->
                        <td class="col-taller">
                            <strong><?= esc($taller['nombre_taller']) ?></strong><br>
                            <span class="subtexto-taller"><?= esc($especialidad) ?></span>
                        </td>

                        <!-- Estado -->
                        <td class="col-estado">
                            <?php if ($estaActivo): ?>
                                <span class="status-badge activo">Activo</span>
                            <?php else: ?>
                                <div class="estado-con-motivo">
                                    <span class="status-badge desactivo">Desactivado</span>
                                    <?php if (! empty($taller['motivo_estado'])): ?>
                                        <i class="bi bi-eye icono-ojo"></i>
                                        <div class="tooltip-motivo">
                                            <?= esc($taller['motivo_estado']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <!-- ── Botón "Ver" → abre el modal de ESTE taller ── -->
                        <td>
                            <div class="btn-group">
                                <button class="btn-accion btn-detalles"
                                        onclick="document.getElementById('modalTaller-<?= $idTaller ?>').classList.add('active')">
                                    Ver
                                </button>
                            </div>
                        </td>

                        <!-- Fecha -->
                        <td class="col-fecha">
                            <?= esc($fechaReg) ?><br>
                            <small><?= esc($horaReg) ?></small>
                        </td>

                        <!-- Acciones -->
                        <td class="col-acciones">
                            <?php if ($estaActivo): ?>
                                <!-- Taller activo → solo desactivar -->
                                <div class="accion-wrapper" style="position:relative;">
                                    <button type="button"
                                            class="btn-accion btn-rechazar btn-abrir-motivo"
                                            data-target="motivo-<?= $idTaller ?>">
                                        Desactivar
                                    </button>
                                    <!-- Form flotante de motivo -->
                                    <form method="POST"
                                          action="<?= site_url('administrador/taller/desactivar') ?>"
                                          class="form-flotante-motivo oculto-motivo"
                                          id="motivo-<?= $idTaller ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id_taller" value="<?= $idTaller ?>">
                                        <input type="text"
                                               name="motivo"
                                               class="input-registro"
                                               placeholder="Motivo de desactivación"
                                               maxlength="100"
                                               required>
                                        <button type="submit" class="btn-enviar-motivo">Enviar</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <!-- Taller inactivo → solo activar -->
                                <form method="POST"
                                      action="<?= site_url('administrador/taller/activar') ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_taller" value="<?= $idTaller ?>">
                                    <button type="submit" class="btn-accion btn-aprobar">
                                        Activar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>

        <footer class="stats-footer">
            <div>
                <strong>
                    Mostrando <?= esc($total) ?> taller<?= $total !== 1 ? 'es' : '' ?>
                </strong>
            </div>
            <div class="pagination">
                <button class="page-btn">«</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">»</button>
            </div>
        </footer>

    </section>
</main>


<!-- ══════════════════════════════════════════════════════════════════════════
     MODALES — uno por cada taller, ocultos por defecto
     Se muestran al hacer clic en "Ver" gracias al onclick de la fila
     Se cierran con la X o haciendo clic en el fondo oscuro (admin_taller.js)
══════════════════════════════════════════════════════════════════════════════ -->
<?php if (! empty($talleres)): ?>
    <?php foreach ($talleres as $taller):
        $idTaller    = (int) $taller['id_taller'];
        $especialidad = $taller['especialidades'][0]['nombre_especialidad'] ?? 'Sin especialidad';
        $rutaFoto    = ! empty($taller['foto_taller'])
                        ? base_url('uploads/talleres/' . $taller['foto_taller'])
                        : base_url('assets/img/taller-placeholder.jpg');
    ?>

    <div class="modal-overlay" id="modalTaller-<?= $idTaller ?>">
        <article class="card-taller">

            <!-- Botón cerrar -->
            <button class="btn-cerrar"
                    aria-label="Cerrar"
                    onclick="document.getElementById('modalTaller-<?= $idTaller ?>').classList.remove('active')">
                <i class="bi bi-x-lg"></i>
            </button>

            <!-- ── CABECERA: foto + nombre + especialidad ─────────────────── -->
            <header class="header-taller">
                <img src="<?= esc($rutaFoto) ?>"
                     alt="Foto de <?= esc($taller['nombre_taller']) ?>"
                     class="foto-taller">
                <div class="overlay-info">
                    <h2 class="nombre-taller"><?= esc($taller['nombre_taller']) ?></h2>
                    <span class="badge-especialidad"><?= esc($especialidad) ?></span>
                </div>
            </header>

            <div class="contenido-taller">

                <!-- ── DESCRIPCIÓN ──────────────────────────────────────── -->
                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">Descripción</h2>
                    <div class="linea-decorativa"></div>
                    <p class="texto-descripcion">
                        <?= esc($taller['descripcion_taller']) ?>
                    </p>
                </section>

                <!-- ── HORARIOS Y DIRECCIÓN ──────────────────────────────── -->
                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">Horarios y Lugar</h2>
                    <div class="linea-decorativa"></div>

                    <p class="direccion-texto">
                        <i class="bi bi-geo-alt-fill"></i>
                        <strong>Ubicación:</strong>
                        <?= esc($taller['direccion_taller']) ?>
                    </p>

                    <ul class="lista-horarios">
                        <?php
                        $bloques = explode('|', $taller['horario_taller'] ?? '');
                        foreach ($bloques as $bloque):
                            $partes = explode(':', trim($bloque), 2);
                            $dia    = trim($partes[0] ?? $bloque);
                            $hora   = trim($partes[1] ?? '');
                        ?>
                            <li>
                                <span class="dia-semana"><?= esc($dia) ?></span>
                                <span class="bloque-hora"><?= esc($hora) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>

                <!-- ── SERVICIOS Y PRECIOS ───────────────────────────────── -->
                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">Precios de Servicios</h2>
                    <div class="linea-decorativa"></div>

                    <?php if (empty($taller['servicios'])): ?>
                        <p style="color:#888; font-size:0.9rem;">
                            Sin servicios registrados.
                        </p>
                    <?php else: ?>
                        <table class="tabla-precios">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Precio base</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($taller['servicios'] as $servicio): ?>
                                    <tr>
                                        <td class="celda-servicio">
                                            <?= esc($servicio['nombre_servicio']) ?>
                                        </td>
                                        <td class="celda-precio">
                                            $<?= number_format((float)$servicio['precio_servicio'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>

                <!-- ── CALIFICACIONES ────────────────────────────────────── -->
                <section class="seccion-detalle">

                    <!-- Cabecera con promedio -->
                    <h2 class="subtitulo-taller">
                        <i class="bi bi-star-half"></i> Calificaciones
                        <?php if ($taller['promedio'] > 0): ?>
                            <span class="badge-promedio">
                                <?= esc($taller['promedio']) ?>
                                <i class="bi bi-star-fill" style="color:#f1c40f; font-size:13px;"></i>
                                &nbsp;<small class="conteo-calis">
                                    (<?= count($taller['calificaciones']) ?>)
                                </small>
                            </span>
                        <?php endif; ?>
                    </h2>
                    <div class="linea-decorativa"></div>

                    <!-- Lista de calificaciones -->
                    <div class="lista-calificaciones">
                        <?php if (empty($taller['calificaciones'])): ?>
                            <p class="sin-calis">Aún no hay calificaciones para este taller.</p>

                        <?php else: ?>
                            <?php foreach ($taller['calificaciones'] as $cali):
                                $colorCali = esc($cali['avatarcolor'] ?? '#1e3c72');
                            ?>
                            <div class="bloque-comentario">

                                <div class="usuario-header">
                                    <!-- Avatar con color del propietario -->
                                    <span class="avatar-icono" style="color: <?= $colorCali ?>;">
                                        <i class="bi bi-person-check"></i>
                                    </span>

                                    <div class="usuario-meta">
                                        <div class="nombre-estrellas-fila">
                                            <span class="nombre-usuario">
                                                <?= esc($cali['primer_nombre'] . ' ' . $cali['primer_apellido']) ?>
                                            </span>
                                            <!-- Estrellas estáticas según puntuación guardada -->
                                            <span class="estrellas-grupo">
                                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                                    <i class="bi bi-star-fill"
                                                       style="font-size:13px; color:<?= $s <= (int)$cali['puntuacion'] ? '#f1c40f' : '#ddd' ?>;"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </div>
                                        <span class="fecha-comentario">
                                            <?= date('d/m/Y', strtotime($cali['fecha_registro'])) ?>
                                        </span>
                                    </div>
                                </div>

                                <?php if (! empty($cali['comentario'])): ?>
                                    <p class="comentario-texto"><?= esc($cali['comentario']) ?></p>
                                <?php endif; ?>

                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </section>

            </div><!-- /contenido-taller -->
        </article>
    </div><!-- /modal-overlay -->

    <?php endforeach; ?>
<?php endif; ?>

<?php echo $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/admin_taller.js') ?>"></script>
<?= $this->endSection() ?>