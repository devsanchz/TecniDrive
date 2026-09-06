<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_citas.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_mecanico') ?>

<main class="main-content">
    <header class="titulos">
        <h1 class="titulo">Agenda de citas solicitadas</h1>
        <h5>Revisa y controla el proceso inicial de las citas de tus clientes</h5>
    </header>

    <section class="dashboard-section">

        <!-- ── Alertas ───────────────────────────────────────────────── -->
        <?php if (session()->getFlashdata('exito_cita')): ?>
            <div class="alerta-exito"><?= session()->getFlashdata('exito_cita') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error_cita')): ?>
            <div class="alerta-error"><?= session()->getFlashdata('error_cita') ?></div>
        <?php endif; ?>

        <!-- ── Controles ─────────────────────────────────────────────── -->
        <div class="controles">
            <div class="buscador-wrapper">
                <input type="text" placeholder="Buscar por fechas">
                <i class="bi bi-search"></i>
            </div>

            <select class="filtro-select" id="filtroEstado">
                <option value="todos">Todas</option>
                <option value="pendiente">Pendientes</option>
                <option value="confirmada">Confirmadas</option>
                <option value="cancelada_propietario">Canceladas por cliente</option>
                <option value="cancelada_mecanico">Canceladas por mí</option>
            </select>

            <!-- Verificar código de confirmación -->
            <div class="verificar-wrapper">
                <button class="escaner" id="btnVerificar">
                    <i class="bi bi-qr-code-scan"></i> Verificar cita
                </button>
                <form method="post"
                      action="<?= site_url('mecanico/cita/verificar-codigo') ?>"
                      id="formVerificar">
                    <?= csrf_field() ?>
                    <input type="text" name="codigo" placeholder="Código de confirmación">
                    <button type="submit">Iniciar Atención</button>
                </form>
            </div>
        </div>


        <!-- ── Lista de citas ────────────────────────────────────────── -->
        <div class="tabla-calificaciones">

        <?php if (empty($citas)): ?>
            <p style="text-align:center; color:#888; padding:40px 0;">
                No tienes citas registradas aún.
            </p>

        <?php else: ?>
        <?php foreach ($citas as $c):

            $claseEstado = match($c['estado_cita']) {
                'pendiente'             => '',
                'confirmada'            => 'en-espera',
                'en_atencion'           => 'en-atencion',
                'cancelada_propietario',
                'cancelada_mecanico'    => 'cancelada',
                default                 => ''
            };

            $textoEstado = match($c['estado_cita']) {
                'pendiente'             => 'Solicitud Pendiente',
                'confirmada'            => 'Reserva Confirmada',
                'en_atencion'           => 'En Atención',
                'cancelada_propietario' => 'Cancelada por el cliente',
                'cancelada_mecanico'    => 'Cancelada por ti',
                default                 => $c['estado_cita']
            };

            $fechaCita = date('d/m/Y', strtotime($c['fecha_cita']));
            $horaCita  = date('H:i',   strtotime($c['fecha_cita']));
            $fechaReg  = date('d/m/Y', strtotime($c['fecha_registro']));

            $iconoVeh = str_contains(strtolower($c['texto_tipo_vehi']), 'moto')
                ? '<i class="fa-solid fa-motorcycle"></i>'
                : '<i class="fa-solid fa-car"></i>';
        ?>

            <div class="casilla"
                 data-estado="<?= $c['estado_cita'] ?>"
                 data-codigo="<?= esc($c['codigo_confirmacion'] ?? '') ?>"
                 data-num="<?= $c['id_cita'] ?>">

                <div class="informacion-principal">

                    <!-- Encabezado -->
                    <div class="encabezado">
                        <div class="estado <?= $claseEstado ?>"
                             id="estado-<?= $c['id_cita'] ?>">
                            <?= $textoEstado ?>
                        </div>
                        <div class="fecha-registro">
                            <strong>Fecha de registro:</strong>
                            <span><?= $fechaReg ?></span>
                        </div>
                    </div>

                    <!-- Fecha programada -->
                    <div class="fecha-cita">
                        <fieldset>
                            <legend>Fecha programada</legend>
                            <span><?= $fechaCita ?></span> a las <span><?= $horaCita ?></span>
                        </fieldset>
                    </div>

                    <!-- Cliente -->
                    <div class="cliente">
                        <fieldset>
                            <legend>Cliente</legend>
                            <i class="bi bi-person-check icon-usuario"></i>
                            <div class="usuario-info">
                                <span class="nombre">
                                    <?= esc($c['cliente_nombre'] . ' ' . $c['cliente_apellido']) ?>
                                </span>
                                <small class="email">
                                    <?= esc($c['telefono_propietario']) ?>
                                </small>
                            </div>
                        </fieldset>
                    </div>

                    <!-- Vehículo -->
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
                            <span>
                                <?= esc($c['nombre_marca'] . ' ' . $c['nombre_modelo'] . ' ' . $c['model_year']) ?>
                            </span>
                        </div>
                    </details>

                    <!-- Detalles de la cita -->
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
                                            <strong>$<?= number_format($serv['precio_servicio'], 0, ',', '.') ?></strong>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>

                        </div>
                    </details>

                </div><!-- /informacion-principal -->


                <!-- ── Panel lateral según estado ────────────────────── -->
                <div class="informacion-segundaria">

                    <?php if ($c['estado_cita'] === 'pendiente'): ?>
                    <!-- PENDIENTE: aceptar o rechazar -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-toggles icono-naranja"></i> Gestión inicial
                        </h2>
                        <div class="acciones">
                            <!-- Confirmar -->
                            <form method="post"
                                  action="<?= site_url('mecanico/cita/confirmar') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_cita" value="<?= $c['id_cita'] ?>">
                                <button type="submit" class="btn1 aceptar">
                                    <i class="bi bi-check2-circle"></i> Aceptar reserva
                                </button>
                            </form>

                            <!-- Cancelar -->
                            <button class="btn1 rechazar"
                                    onclick="toggleCancelar(<?= $c['id_cita'] ?>)">
                                <i class="bi bi-x-circle"></i> Rechazar
                            </button>
                            <form method="post"
                                  action="<?= site_url('mecanico/cita/cancelar') ?>"
                                  id="formulario-<?= $c['id_cita'] ?>"
                                  style="display:none;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_cita" value="<?= $c['id_cita'] ?>">
                                <input type="text" name="motivo" placeholder="Motivo de rechazo" required>
                                <button type="submit" class="btn1 cancelar">Enviar</button>
                            </form>
                        </div>
                    </div>

                    <?php elseif ($c['estado_cita'] === 'confirmada'): ?>
                    <!-- CONFIRMADA: esperar que el cliente llegue -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-toggles icono-naranja"></i> Gestión media
                            <small>(Si el cliente no llega)</small>
                        </h2>
                        <div class="acciones">
                            <form method="post"
                                  action="<?= site_url('mecanico/cita/cancelar') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id_cita"  value="<?= $c['id_cita'] ?>">
                                <input type="hidden" name="motivo"   value="Cliente no asistió">
                                <button type="submit" class="btn1 sin">
                                    <i class="bi bi-person-slash"></i> No asistida
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Banner en atención (se activa por JS al verificar código) -->
                    <div class="banner-en-atencion" id="banner-<?= $c['id_cita'] ?>" role="status">
                        <i class="bi bi-check-circle-fill banner-icono"></i>
                        <span class="banner-texto">
                            Cita <?= $c['id_cita'] ?> en proceso de atención
                        </span>
                    </div>

                    <?php elseif ($c['estado_cita'] === 'en_atencion'): ?>
                    <!-- EN ATENCIÓN -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-check-circle-fill alvertencia"></i> En atención
                        </h2>
                        <small>Vehículo recibido — pasa a control de mantenimiento</small>
                    </div>

                    <?php elseif (in_array($c['estado_cita'], ['cancelada_propietario', 'cancelada_mecanico'])): ?>
                    <!-- CANCELADA -->
                    <div class="gestion-inicial">
                        <h2 class="subtitulo-taller">
                            <i class="bi bi-exclamation-triangle alvertencia"></i> Motivo de cancelación
                        </h2>
                        <div class="cierre">
                            <?= esc($c['motivo_cancelacion'] ?? '—') ?>
                        </div>
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
// ── Toggle formulario de cancelación ─────────────────────────────────────
function toggleCancelar(idCita) {
    const form = document.getElementById('formulario-' + idCita);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// ── Toggle panel verificar código ────────────────────────────────────────
const btnVerificar  = document.getElementById('btnVerificar');
const formVerificar = document.getElementById('formVerificar');

btnVerificar.addEventListener('click', (e) => {
    e.stopPropagation();
    formVerificar.classList.toggle('mostrar');
});

document.addEventListener('click', (e) => {
    if (!btnVerificar.contains(e.target) && !formVerificar.contains(e.target)) {
        formVerificar.classList.remove('mostrar');
    }
});

// ── Filtro por estado ─────────────────────────────────────────────────────
document.getElementById('filtroEstado').addEventListener('change', function () {
    const valor    = this.value;
    const casillas = document.querySelectorAll('.casilla');

    casillas.forEach(casilla => {
        const estado = casilla.dataset.estado;
        casilla.style.display = (valor === 'todos' || estado === valor) ? '' : 'none';
    });
});
</script>
<script src="<?= base_url('assets/js/mecanico_citas.js') ?>"></script>
<?= $this->endSection() ?>