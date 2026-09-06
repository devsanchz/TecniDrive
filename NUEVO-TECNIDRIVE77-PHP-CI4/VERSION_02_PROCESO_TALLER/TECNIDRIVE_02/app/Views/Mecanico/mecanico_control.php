<?php echo $this->extend('Estructura/diseño');?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_control.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido')?>
<?= $this->include('Estructura/menu_mecanico') ?>

<main class="main-content">
    <header class="titulos">
        <h1 class="titulo">Historial y seguimiento de citas</h1>
        <h5>Controla las citas atendidas y haz el proceso de cierre</h5>
    </header>

    <section class="dashboard-section">

        <!-- ── Alertas ───────────────────────────────────────────────── -->
        <?php if ($exito ?? null): ?>
            <div class="alerta-exito"><?= esc($exito) ?></div>
        <?php endif; ?>
        <?php if ($error ?? null): ?>
            <div class="alerta-error"><?= esc($error) ?></div>
        <?php endif; ?>

        <div class="controles">
            <div class="buscador-wrapper">
                <input type="text" placeholder="Buscar por placa de vehículo o fecha">
                <i class="bi bi-search"></i>
            </div>

            <!-- INGRESAR TÉCNICOS -->
            <div class="tarjeta-agregar-vehiculo">
                <div class="header-licencia">
                    <p class="titulo-vehiculo">Lista de trabajadores</p>
                    <button class="btn-desplegar btn-desplegar-form" type="button" id="btnListaTrabajadores">
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                </div>

                <div id="trabajadores">
                    <ul id="lista-trabajadores">
                        <?php if (empty($tecnicos)): ?>
                            <li class="sin-tecnicos">Aún no has agregado técnicos.</li>
                        <?php else: ?>
                            <?php foreach ($tecnicos as $t): ?>
                                <li>
                                    <span><?= esc($t['nombre_tecnico']) ?></span>
                                    <form method="post"
                                          action="<?= site_url('mecanico/control/eliminar-tecnico') ?>"
                                          style="display:inline;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id_tecnico" value="<?= $t['id_tecnico'] ?>">
                                        <button type="submit" class="btn-x-eliminar" title="Eliminar">×</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>

                    <form method="post" action="<?= site_url('mecanico/control/agregar-tecnico') ?>" id="form-agregar-trabajador">
                        <?= csrf_field() ?>
                        <h2>Trabajadores</h2>
                        <input type="text" name="nombre_tecnico" id="nombre-nuevo-trabajador" placeholder="Nombre y apellido">

                        <button type="submit" class="btn-guardar">Agregar trabajador</button>
                    </form>
                </div>
            </div>
            <!-- FINAL DE TECNICOS -->

            <!-- Verificar código de entrega -->
            <div class="verificar-wrapper">
                <button class="escaner" type="button" id="btnVerificar">
                    <i class="bi bi-qr-code-scan"></i> Finalizar cita
                </button>
                <form method="post"
                      action="<?= site_url('mecanico/control/verificar-entrega') ?>"
                      id="formVerificar">
                    <?= csrf_field() ?>
                    <input type="text" name="codigo" placeholder="Código de cita">
                    <button type="submit">Finalizar</button>
                </form>
            </div>

            <select class="filtro-select">
                <option value="todos">Opciones de control</option>
                <option value="en_atencion">Citas en atención</option>
                <option value="en_cierre">Citas en cierre</option>
                <option value="finalizada">Citas finalizadas</option>
            </select>
        </div>

        <div class="tabla-calificaciones">

        <?php if (empty($citas)): ?>
            <p style="text-align:center; color:#888; padding:40px 0;">
                No hay citas en atención en este momento.
            </p>

        <?php else: ?>
        <?php foreach ($citas as $c):
            $fechaAtencion = date('d/m/Y', strtotime($c['fecha_inicio_atencion']));
            $fechaCita     = date('d/m/Y', strtotime($c['fecha_cita']));
            $horaCita      = date('H:i',   strtotime($c['fecha_cita']));
            $fechaReg      = date('d/m/Y', strtotime($c['fecha_registro']));
            $iconoVeh      = str_contains(strtolower($c['texto_tipo_vehi']), 'moto')
                ? '<i class="fa-solid fa-motorcycle"></i>'
                : '<i class="fa-solid fa-car"></i>';
            $enCierre      = ($c['estado_mantenimiento'] ?? null) === 'en_cierre';
        ?>

        <?php if ($enCierre): ?>

        <!-- CASILLA EN CIERRE — solo lectura, ya no tiene formulario de pre-cierre -->
        <div class="casilla" data-estado="en_cierre">
            <div class="informacion-principal">

                <div class="encabezado">
                    <div class="estado en-espera">En cierre</div>
                    <div class="fecha-registro">
                        <strong>Fecha de registro:</strong>
                        <span><?= $fechaReg ?></span>
                    </div>
                </div>

                <div class="fecha-cita">
                    <fieldset>
                        <legend>Fecha programada</legend>
                        <span><?= $fechaCita ?></span> a las <span><?= $horaCita ?></span>
                    </fieldset>
                </div>

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

                <?php if (!empty($c['problema_contexto'])): ?>
                <details class="mi-detalle">
                    <summary>
                        <span><i class="bi bi-tools" style="margin-right:6px;"></i>Detalles de la cita</span>
                        <i class="bi bi-caret-down-fill icono-abajo"></i>
                        <i class="bi bi-caret-up-fill icono-arriba"></i>
                    </summary>
                    <div class="detalles-contenido">
                        <div class="bloque-detalle">
                            <strong>Problema reportado:</strong>
                            <p class="texto-problema">"<?= esc($c['problema_contexto']) ?>"</p>
                        </div>
                    </div>
                </details>
                <?php endif; ?>

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
                            <?php if (!empty($c['tecnicos_asignados'])): ?>
                                <ul class="lista-servicios">
                                    <?php foreach ($c['tecnicos_asignados'] as $tec): ?>
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

            </div><!-- /informacion-principal -->

            <div class="gestion-inicial">
                <h2 class="subtitulo-taller">
                    <i class="bi bi-archive icono-naranja"></i> Pasos de cierre
                </h2>
                <small>(Avisar al cliente de la finalización de la cita y escanear el código que dé el cliente)</small>
            </div>
        </div><!-- /casilla en_cierre -->
        <?php elseif (($c['estado_mantenimiento'] ?? null) === 'finalizada'): ?>

<!-- CASILLA FINALIZADA — solo lectura, igual que en_cierre pero con estado "Finalizada" -->
<div class="casilla" data-estado="finalizada">
    <div class="informacion-principal">

        <div class="encabezado">
            <div class="estado finalizada">Finalizada</div>
            <div class="fecha-registro">
                <strong>Fecha de registro:</strong>
                <span><?= $fechaReg ?></span>
            </div>
        </div>

        <div class="fecha-cita">
            <fieldset>
                <legend>Fecha programada</legend>
                <span><?= $fechaCita ?></span> a las <span><?= $horaCita ?></span>
            </fieldset>
        </div>

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

        <?php if (!empty($c['problema_contexto'])): ?>
        <details class="mi-detalle">
            <summary>
                <span><i class="bi bi-tools" style="margin-right:6px;"></i>Detalles de la cita</span>
                <i class="bi bi-caret-down-fill icono-abajo"></i>
                <i class="bi bi-caret-up-fill icono-arriba"></i>
            </summary>
            <div class="detalles-contenido">
                <div class="bloque-detalle">
                    <strong>Problema reportado:</strong>
                    <p class="texto-problema">"<?= esc($c['problema_contexto']) ?>"</p>
                </div>
            </div>
        </details>
        <?php endif; ?>

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
                    <?php if (!empty($c['tecnicos_asignados'])): ?>
                        <ul class="lista-servicios">
                            <?php foreach ($c['tecnicos_asignados'] as $tec): ?>
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

    </div><!-- /informacion-principal -->

    <div class="informacion-segundaria">
        <div class="gestion-inicial">
            <h2 class="subtitulo-taller">
                <i class="bi bi-check-circle-fill" style="color:#28a745;"></i> Cita finalizada
            </h2>
            <small>El vehículo fue entregado al cliente.</small>
        </div>
    </div>
</div><!-- /casilla finalizada -->

        <?php else: ?>

        <!-- CASILLA EN ATENCIÓN -->
        <div class="casilla" data-estado="en_atencion">
            <div class="informacion-principal">

                <div class="encabezado">
                    <div class="estado en-atencion">En atención</div>
                    <div class="fecha-registro">
                        <strong>Fecha de registro:</strong>
                        <span><?= $fechaReg ?></span>
                    </div>
                </div>

                <div class="fecha-cita">
                    <fieldset>
                        <legend>Fecha de atención</legend>
                        <span><?= $fechaAtencion ?></span>
                    </fieldset>
                </div>

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

                <?php if (!empty($c['problema_contexto'])): ?>
                <details class="mi-detalle">
                    <summary>
                        <span><i class="bi bi-tools" style="margin-right:6px;"></i>Detalles de la cita</span>
                        <i class="bi bi-caret-down-fill icono-abajo"></i>
                        <i class="bi bi-caret-up-fill icono-arriba"></i>
                    </summary>
                    <div class="detalles-contenido">
                        <div class="bloque-detalle">
                            <strong>Problema reportado:</strong>
                            <p class="texto-problema">"<?= esc($c['problema_contexto']) ?>"</p>
                        </div>
                    </div>
                </details>
                <?php endif; ?>

            </div><!-- /informacion-principal -->

            <!-- Panel lateral: formulario de pre-cierre -->
            <div class="informacion-segundaria">
                <div class="gestion-inicial">
                    <h2 class="subtitulo-taller">
                        <i class="bi bi-check-circle icono-naranja"></i> Gestión de cierre
                    </h2>
                    <div class="acciones">
                        <button class="btn1 rechazar" type="button" id="btnMostrar-<?= $c['id_cita'] ?>"
                                onclick="toggleCierre(<?= $c['id_cita'] ?>)">
                            <i class="bi bi-archive"></i> Proceso de cierre
                        </button>

                        <form method="post"
                              action="<?= site_url('mecanico/control/cerrar') ?>"
                              id="formulario-<?= $c['id_cita'] ?>"
                              style="display:none;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_seguimiento" value="<?= $c['id_seguimiento'] ?>">
                            <input type="hidden" name="id_cita"        value="<?= $c['id_cita'] ?>">

                            <h3>Datos de pre-cierre de cita</h3>
                          <label >Observaciones del proceso de reparacion(Opcional)
                               <input type="text" name="observaciones"
                                   placeholder="Observaciones (opcional)"
                                   value="<?= esc($c['observaciones_tecnico'] ?? '') ?>">
                                </label>
                            

                            <div class="garantia">
                                <label>Garantía del servicio (si no aplica, dejar vacío)
                                    <input type="text" name="texto_garantia"
                                           placeholder="Contexto de garantía"
                                           value="<?= esc($c['texto_garantia'] ?? '') ?>">
                                    Vigencia de garantía
                                    <input type="date" name="garantia_vigencia"
                                           value="<?= esc($c['garantia_vigencia'] ?? '') ?>">
                                </label>
                            </div>

                            <div class="garantia">
                                <label>Precio total
                                    <input type="text" name="precio_total"
                                           placeholder="$ 0.000"
                                           value="<?= esc($c['precio_total'] ?? '') ?>">
                                </label>
                            </div>

                            <div class="garantia elegir">
                                <label>Trabajadores en la reparación</label>
                                <?php if (empty($tecnicos)): ?>
                                    <small>No hay técnicos registrados. Agrega uno en "Lista de trabajadores".</small>
                                <?php else: ?>
                                    <?php foreach ($tecnicos as $t): ?>
                                        <label class="opciones">
                                            <input type="checkbox" name="tecnicos[]" value="<?= $t['id_tecnico'] ?>">
                                            <span><?= esc($t['nombre_tecnico']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn1 cancelar">Guardar, pasar a cierre</button>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /casilla -->

        <?php endif; ?>

        <?php endforeach; ?>
        <?php endif; ?>

        </div><!-- /tabla-calificaciones -->

        <footer class="stats-footer">
            <div>
                <strong>
                    Mostrando <?= count($citas) ?> cita<?= count($citas) !== 1 ? 's' : '' ?> en atención
                </strong>
            </div>
        </footer>

    </section>
</main>

<?php echo $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/mecanico_control.js') ?>"></script>
<?= $this->endSection() ?>