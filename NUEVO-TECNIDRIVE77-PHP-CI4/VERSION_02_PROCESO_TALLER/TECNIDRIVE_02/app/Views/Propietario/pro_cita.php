<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_citas.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_pro') ?>

<main class="main-content">
    <header class="titulos">
        <h1 class="titulo">Tus citas mecánicas</h1>
        <h5>Revisa y sigue el estado de tus solicitudes de cita para la atención de tu vehículo</h5>
    </header>

    <section class="dashboard-section">

        <!-- ── Alertas ───────────────────────────────────────────────── -->
        <?php if (session()->getFlashdata('exito_cita')): ?>
            <div class="alerta-exito"><?= session()->getFlashdata('exito_cita') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error_cita')): ?>
            <div class="alerta-error"><?= session()->getFlashdata('error_cita') ?></div>
        <?php endif; ?>

        <div class="controles">
            <div class="buscador-wrapper">
                <p class="cabezas">Citas de tu vehículo</p>
            </div>

            <div class="estados">
                <p class="cabezas">Estados de tus citas</p>
                <select class="filtro-select" id="filtroEstado">
                    <option value="todos">Todas</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="confirmada">Confirmadas</option>
                    <option value="en_atencion">En atención</option>
                    <option value="cancelada_propietario">Canceladas por mí</option>
                    <option value="cancelada_mecanico">Canceladas por taller</option>
                </select>
            </div>
        </div>


        <div class="tabla-calificaciones">

        <?php if (empty($citas)): ?>
            <p style="text-align:center; color:#888; padding:40px 0;">
                No tienes citas registradas aún.
            </p>

        <?php else: ?>
        <?php foreach ($citas as $c):

            // ── Sub-estado real cuando la cita está en_atencion ───────────
            // (estado_cita se queda fijo en 'en_atencion'; el avance real
            //  del trabajo se sigue con estado_mantenimiento de la ficha)
            $subEstadoTrabajo = $c['estado_mantenimiento'] ?? null;

            // ── Clase CSS según estado ────────────────────────────────────
            $claseEstado = match($c['estado_cita']) {
                'pendiente'             => 'pendiente',
                'confirmada'            => 'en-espera',
                'en_atencion'           => match($subEstadoTrabajo) {
                    'en_cierre'  => 'en-cierre',
                    'finalizada' => 'finalizada',
                    default      => 'en-atencion',
                },
                'cancelada_propietario',
                'cancelada_mecanico'    => 'cancelada',
                default                 => ''
            };

            // ── Texto legible del estado ──────────────────────────────────
            $textoEstado = match($c['estado_cita']) {
                'pendiente'             => 'Pendiente',
                'confirmada'            => 'Confirmada',
                'en_atencion'           => match($subEstadoTrabajo) {
                    'en_cierre'  => 'En cierre',
                    'finalizada' => 'Finalizada',
                    default      => 'En atención',
                },
                'cancelada_propietario' => 'Cancelada por ti',
                'cancelada_mecanico'    => 'Cancelada por el taller',
                default                 => $c['estado_cita']
            };

            // ── Separar fecha y hora ──────────────────────────────────────
            $fechaCita = date('d/m/Y', strtotime($c['fecha_cita']));
            $horaCita  = date('H:i',   strtotime($c['fecha_cita']));
            $fechaReg  = date('d/m/Y', strtotime($c['fecha_registro']));

            // ── Ícono según tipo de vehículo ──────────────────────────────
            $iconoVeh = str_contains(strtolower($c['texto_tipo_vehi']), 'moto')
                ? '<i class="fa-solid fa-motorcycle"></i>'
                : '<i class="fa-solid fa-car"></i>';
        ?>

            <div class="casilla" data-estado="<?= $c['estado_cita'] ?>">
                <div class="informacion-principal">

                    <!-- ── Encabezado: estado y fecha de registro ── -->
                    <div class="encabezado">
                        <div class="estado <?= $claseEstado ?>">
                            <?= $textoEstado ?>
                        </div>
                        <div class="fecha-registro">
                            <strong>Fecha de registro:</strong>
                            <span><?= $fechaReg ?></span>
                        </div>
                    </div>

                    <!-- ── Fecha programada ── -->
                    <div class="fecha-cita">
                        <fieldset>
                            <legend>Fecha programada</legend>
                            <span><?= $fechaCita ?></span> a las <span><?= $horaCita ?></span>
                        </fieldset>
                    </div>

                    <!-- ── Datos de contacto y taller ── -->
                   <!-- ── Datos de contacto y taller ── -->
<div class="cliente">
    <fieldset>
        <legend>Datos de contacto</legend>
        <i class="bi bi-person-gear icon-usuario"></i>
        <div class="usuario-info">
            <!-- CORRECCIÓN: datos del mecánico, no del propietario -->
            <span class="nombre">
                <?= esc($c['mecanico_nombre'] . ' ' . $c['mecanico_apellido']) ?>
            </span>
            <small class="email">
                <i class="bi bi-telephone"></i>
                <?= esc($c['telefono_mecanico']) ?>
            </small>
        </div>
    </fieldset>
    <fieldset>
        <legend>Taller solicitado</legend>
        <div class="usuario-info">
            <span class="taller"><?= esc($c['nombre_taller']) ?></span>
        </div>
    </fieldset>
</div>
                     

                    <!-- ── Ubicación ── -->
                    <fieldset>
                        <legend>Ubicación del local</legend>
                        <i class="bi bi-geo-alt-fill"></i>
                        <span><?= esc($c['direccion_taller']) ?></span>
                    </fieldset>

                    <!-- ── Vehículo ── -->
                    <details class="mi-detalle">
                        <summary>
                            Vehículo a reparar
                            <i class="bi bi-caret-down-fill icono-abajo"></i>
                            <i class="bi bi-caret-up-fill icono-arriba"></i>
                        </summary>
                        <div class="vehiculo">
                            <div class="badge-vehiculo">
                                <?= $iconoVeh ?>
                                <p class="texto-placa"><?= esc($c['placa']) ?></p>
                            </div>
                            <span><?= esc($c['nombre_marca'] . ' ' . $c['nombre_modelo'] . ' ' . $c['model_year']) ?></span>
                        </div>
                    </details>

                    <!-- ── Detalles de la cita ── -->
                    <details class="mi-detalle">
                        <summary>
                            <span><i class="bi bi-tools" style="margin-right:6px;"></i>Detalles de la cita</span>
                            <i class="bi bi-caret-down-fill icono-abajo"></i>
                            <i class="bi bi-caret-up-fill icono-arriba"></i>
                        </summary>
                        <div class="detalles-contenido">

                            <?php if (!empty($c['problema_contexto'])): ?>
                            <div class="bloque-detalle">
                                <strong>Problema reportado:</strong>
                                <p class="texto-problema">"<?= esc($c['problema_contexto']) ?>"</p>
                            </div>
                            <?php endif; ?>

                           <?php if (!empty($c['servicios'])): ?>
<div class="bloque-detalle">
    <strong>Servicios solicitados:</strong>
    <ul class="lista-servicios">
        <?php foreach ($c['servicios'] as $serv): ?>
            <li>
                <span><?= esc($serv['nombre_servicio']) ?></span>
                <!-- CORRECCIÓN: precio incluido -->
                <strong>$<?= number_format($serv['precio_servicio'], 0, ',', '.') ?></strong>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

                        </div>
                    </details>

                    <?php if ($c['estado_cita'] === 'en_atencion' && $subEstadoTrabajo === 'en_cierre'): ?>
                    <!-- ── Detalles de cierre (mismo formato que en el panel del mecánico) ── -->
                    <details class="mi-detalle">
                        <summary>
                            <span><i class="bi bi-archive-fill" style="margin-right:6px;"></i>Detalles de cierre</span>
                            <i class="bi bi-caret-down-fill icono-abajo"></i>
                            <i class="bi bi-caret-up-fill icono-arriba"></i>
                        </summary>
                        <div class="detalles-contenido">
                            <fieldset>
                                <legend>Observaciones</legend>
                                <?= !empty($c['observaciones_tecnico']) ? esc($c['observaciones_tecnico']) : 'Sin observaciones registradas' ?>
                            </fieldset>

                            <fieldset>
                                <legend>Equipo de reparación</legend>
                                <?php if (!empty($c['tecnicos'])): ?>
                                    <ul class="lista-servicios">
                                        <?php foreach ($c['tecnicos'] as $tec): ?>
                                            <li><span><?= esc($tec['nombre_tecnico']) ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    Sin técnicos asignados
                                <?php endif; ?>
                            </fieldset>

                            <div class="precio-garantia">
                                <fieldset>
                                    <legend>Garantía</legend>
                                    <?php if (!empty($c['texto_garantia'])): ?>
                                        <?= esc($c['texto_garantia']) ?>
                                        <?php if (!empty($c['garantia_vigencia'])): ?>
                                            <br><small>Vigente hasta: <?= date('d/m/Y', strtotime($c['garantia_vigencia'])) ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Sin garantía
                                    <?php endif; ?>
                                </fieldset>
                                <fieldset>
                                    <legend>Precio total</legend>
                                    <?= !empty($c['precio_total']) ? '$' . number_format((float) $c['precio_total'], 0, ',', '.') : '—' ?>
                                </fieldset>
                            </div>
                        </div>
                    </details>
                    <?php endif; ?>

                </div><!-- /informacion-principal -->


                <!-- ── Panel lateral según estado ── -->
                <div class="informacion-segundaria">

                    <?php if ($c['estado_cita'] === 'pendiente'): ?>
                    <!-- PENDIENTE: puede cancelar -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-toggles icono-naranja"></i> Control inicial
                        </h2>
                        <div class="acciones">
                            <small>Puedes cancelarla antes de que confirmen la reserva</small>
                            <button class="btn1 rechazar" id="btnMostrar-<?= $c['id_cita'] ?>"
                                    onclick="toggleCancelar(<?= $c['id_cita'] ?>)">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </button>
                           <form method="post"
      action="<?= site_url('propietario/cita/cancelar') ?>"
      id="formulario-<?= $c['id_cita'] ?>"
      style="display:none;">
    <?= csrf_field() ?>
    <input type="hidden" name="id_cita" value="<?= $c['id_cita'] ?>">
    <!-- name="motivo" es lo que lee el controlador -->
    <input type="text" name="motivo" placeholder="Motivo" required>
    <button type="submit" class="btn1 cancelar">Enviar</button>
</form>
                        </div>
                    </div>

                    <?php elseif ($c['estado_cita'] === 'confirmada'): ?>
                    <!-- CONFIRMADA: mostrar código de confirmación -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-toggles icono-naranja"></i> Control medio
                        </h2>
                        <small>(Presenta este código al llegar al taller)</small>
                        <div class="accion-code">
                            <p><?= esc($c['codigo_confirmacion'] ?? '—') ?></p>
                        </div>
                    </div>

                    <?php elseif ($c['estado_cita'] === 'en_atencion' && $subEstadoTrabajo === 'en_atencion'): ?>
                    <!-- EN ATENCIÓN: vehículo recibido, en proceso de reparación -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-check-circle-fill alvertencia"></i> Vehículo en taller
                        </h2>
                        <small>Tu vehículo está siendo atendido. El código de entrega
                               te llegará cuando el mecánico finalice el trabajo.</small>
                    </div>

                    <?php elseif ($c['estado_cita'] === 'en_atencion' && $subEstadoTrabajo === 'en_cierre'): ?>
                    <!-- EN CIERRE: el detalle ya se muestra arriba, en "Detalles de cierre" -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-archive icono-naranja"></i> En proceso de cierre
                        </h2>
                        <small>El mecánico ya registró los datos de tu reparación.
                               Presenta este código al recoger tu vehículo.</small>
                        <div class="accion-code">
                            <p><?= esc($c['codigo_entrega'] ?? '—') ?></p>
                        </div>
                    </div>


                    <?php elseif ($c['estado_cita'] === 'en_atencion' && $subEstadoTrabajo === 'finalizada'): ?>
                    <!-- FINALIZADA: mismo diseño que "Detalles de cierre" del mecánico -->
                    <div class="gestion-inicial">
                       
                       

                        <details class="mi-detalle">
                            <summary>
                                <span><i class="bi bi-archive-fill" style="margin-right:6px;"></i>Detalles de cierre</span>
                                <i class="bi bi-caret-down-fill icono-abajo"></i>
                                <i class="bi bi-caret-up-fill icono-arriba"></i>
                            </summary>
                            <div class="detalles-contenido">
                                <fieldset>
                                    <legend>Observaciones</legend>
                                    <?= !empty($c['observaciones_tecnico']) ? esc($c['observaciones_tecnico']) : 'Sin observaciones registradas' ?>
                                </fieldset>

                                <fieldset>
                                    <legend>Técnicos a cargo</legend>
                                    <?php if (!empty($c['tecnicos'])): ?>
                                        <ul class="lista-servicios">
                                            <?php foreach ($c['tecnicos'] as $tec): ?>
                                                <li><span><?= esc($tec['nombre_tecnico']) ?></span></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        Sin técnicos asignados
                                    <?php endif; ?>
                                </fieldset>

                                <div class="precio-garantia">
                                    <fieldset>
                                        <legend>Garantía</legend>
                                        <?php if (!empty($c['texto_garantia'])): ?>
                                            <?= esc($c['texto_garantia']) ?>
                                            <?php if (!empty($c['garantia_vigencia'])): ?>
                                                <br><small>Vigente hasta: <?= date('d/m/Y', strtotime($c['garantia_vigencia'])) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            Sin garantía
                                        <?php endif; ?>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Precio total</legend>
                                        <?= !empty($c['precio_total']) ? '$' . number_format((float) $c['precio_total'], 0, ',', '.') : '—' ?>
                                    </fieldset>
                                </div>
                            </div>
                        </details>
                    </div>

                    <?php elseif (in_array($c['estado_cita'], ['cancelada_propietario', 'cancelada_mecanico'])): ?>
                    <!-- CANCELADA -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-x-circle" style="color:#dc3545;"></i> Cita cancelada
                        </h2>
                        <?php if (!empty($c['motivo_cancelacion'])): ?>
                            <small>Motivo: <?= esc($c['motivo_cancelacion']) ?></small>
                        <?php endif; ?>
                    </div>

                    <?php endif; ?>

                </div><!-- /informacion-segundaria -->

            </div><!-- /casilla -->

        <?php endforeach; ?>
        <?php endif; ?>

        </div><!-- /tabla-calificaciones -->

        <footer class="stats-footer">
            <div>
                <strong>Mostrando <?= count($citas) ?> cita<?= count($citas) !== 1 ? 's' : '' ?></strong>
            </div>
        </footer>

    </section>
</main>

<?php echo $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
// Toggle del formulario de cancelación por cita
function toggleCancelar(idCita) {
    const form = document.getElementById('formulario-' + idCita);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
<script src="<?= base_url('assets/js/pro_citas.js') ?>"></script>
<?= $this->endSection() ?>