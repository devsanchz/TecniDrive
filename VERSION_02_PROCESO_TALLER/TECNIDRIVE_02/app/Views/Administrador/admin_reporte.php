<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_reportee.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_admin') ?>

<main class="main-content">
    <header class="titulos">
        <h1 class="titulo">Reportes de calidad y el sistema</h1>
        <h4>Revisa y filtra la información para recibir reportes del sistema</h4>
    </header>

    <?php
    $mensaje     = session()->getFlashdata('mensaje')      ?? $mensaje      ?? null;
    $tipoMensaje = session()->getFlashdata('tipo_mensaje') ?? $tipo_mensaje ?? 'info';
    ?>

    <?php if ($mensaje): ?>
        <div class="alerta-<?= esc($tipoMensaje) ?>" role="alert" style="margin: 0 20px 15px;">
            <?= esc($mensaje) ?>
        </div>
    <?php endif; ?>

    <section class="dashboard-section">
        <div class="casilla">
            <h1>Filtros para generar reportes PDF</h1>

            <form method="POST" action="<?= site_url('administrador/reporte/generar') ?>" id="formReporte">
                <?= csrf_field() ?>

                <div class="seccion-casillas">

                    <!-- ── USUARIOS REGISTRADOS ────────────────────────────────── -->
                    <details class="mi-detalle">
                        <summary>
                            <i class="bi bi-person-fill"></i> Usuarios registrados
                            <i class="bi bi-caret-down-fill icono-abajo"></i>
                            <i class="bi bi-caret-up-fill icono-arriba"></i>
                        </summary>

                        <!-- El JS lee este value para saber qué sección enviar -->
                        <input type="hidden" name="seccion_usuarios" value="usuarios">

                        <div class="filtros">
                            <div class="f1">
                                <label><input type="checkbox" name="roles_usuarios[]" value="propietarios"> Propietarios</label>
                                <label><input type="checkbox" name="roles_usuarios[]" value="mecanicos"> Mecánicos</label>
                            </div>
                            <div class="f2">
                                <h3>Fecha de registro</h3>
                                <div class="fechas">
                                    <label><input type="radio" name="periodo_usuarios" value="semana"   checked> Esta semana</label>
                                    <label><input type="radio" name="periodo_usuarios" value="mes">            Este mes</label>
                                    <label><input type="radio" name="periodo_usuarios" value="semestre">       Hace 6 meses</label>
                                    <label><input type="radio" name="periodo_usuarios" value="año">            Hace un año</label>
                                </div>
                            </div>
                        </div>
                    </details>

                    <!-- ── VEHÍCULOS REGISTRADOS ───────────────────────────────── -->
                    <!--
                        CAMBIOS vs original:
                        - Añadido name="seccion_vehiculos" con value="vehiculos"
                        - Checkboxes con name="tipos_vehiculos[]" y value correcto
                        - Radios con name="periodo_vehiculos" y value que coincide
                          con resolverPeriodo() del controlador
                        - Eliminado el "checked" doble que existía en talleres
                    -->
                    <details class="mi-detalle">
                        <summary>
                            <i class="fa-solid fa-car"></i> Vehículos registrados
                            <i class="bi bi-caret-down-fill icono-abajo"></i>
                            <i class="bi bi-caret-up-fill icono-arriba"></i>
                        </summary>

                        <input type="hidden" name="seccion_vehiculos" value="vehiculos">

                        <div class="filtros">
                            <div class="f1">
                                <label><input type="checkbox" name="tipos_vehiculos[]" value="automovil">    Automóviles</label>
                                <label><input type="checkbox" name="tipos_vehiculos[]" value="motocicleta"> Motocicletas</label>
                            </div>
                            <div class="f2">
                                <h3>Fecha de registro</h3>
                                <div class="fechas">
                                    <label><input type="radio" name="periodo_vehiculos" value="semana"   checked> Esta semana</label>
                                    <label><input type="radio" name="periodo_vehiculos" value="mes">            Este mes</label>
                                    <label><input type="radio" name="periodo_vehiculos" value="semestre">       Hace 6 meses</label>
                                    <label><input type="radio" name="periodo_vehiculos" value="año">            Hace un año</label>
                                </div>
                            </div>
                        </div>
                    </details>

                    <!-- ── TALLERES REGISTRADOS ────────────────────────────────── -->
                    <!--
                        CAMBIOS vs original:
                        - Añadido name="seccion_talleres" con value="talleres"
                        - Botones convertidos a <div> con clase para identificar
                          el modo activo (registrados vs ranking), sin alterar estilos
                        - Checkboxes de estado: name="estado_talleres", values: todos/activo/desactivado
                        - Radios de período: name="periodo_talleres"
                        - Radios de ranking: name="ranking_talleres", values que
                          coinciden con el switch del controlador
                        - Corregido el doble "checked" que tenía el original
                    -->
                    <details class="mi-detalle">
                        <summary>
                            <i class="fa-solid fa-screwdriver-wrench"></i> Talleres registrados
                            <i class="bi bi-caret-down-fill icono-abajo"></i>
                            <i class="bi bi-caret-up-fill icono-arriba"></i>
                        </summary>

                        <input type="hidden" name="seccion_talleres" value="talleres">

                        <div class="filtros">

                            <!-- Estado del taller -->
                            <div class="f1">
                                <h3>Estado</h3>
                                <label><input type="radio" name="estado_talleres" value="todos"        checked> Todos</label>
                                <label><input type="radio" name="estado_talleres" value="activo">             Activos</label>
                                <label><input type="radio" name="estado_talleres" value="desactivado">        Desactivados</label>
                            </div>

                            <!-- Período de registro -->
                            <div class="f2">
                                <h3>Fecha de registro</h3>
                                <div class="fechas">
                                    <label><input type="radio" name="periodo_talleres" value="semana"   checked> Esta semana</label>
                                    <label><input type="radio" name="periodo_talleres" value="mes">            Este mes</label>
                                    <label><input type="radio" name="periodo_talleres" value="semestre">       Hace 6 meses</label>
                                    <label><input type="radio" name="periodo_talleres" value="año">            Hace un año</label>
                                </div>
                            </div>

                            <!-- Ranking (opcional — si se marca, sobreescribe el orden por fecha) -->
                            <div class="f2">
                                <h3>Ordenar por ranking</h3>
                                <div class="fechas">
                                    <!--
                                        Sin "checked" por defecto: si el usuario no elige ninguno
                                        el controlador ordena por fecha_registro (comportamiento seguro).
                                        value="" en la primera opción resetea la selección de ranking.
                                    -->
                                    <label><input type="radio" name="ranking_talleres" value="">                Sin ranking (fecha)</label>
                                    <label><input type="radio" name="ranking_talleres" value="mejor_puntuacion"> Mejor puntuación</label>
                                    <label><input type="radio" name="ranking_talleres" value="peor_puntuacion">  Peor puntuación</label>
                                    <label><input type="radio" name="ranking_talleres" value="mas_citas">        Con más citas</label>
                                    <label><input type="radio" name="ranking_talleres" value="menos_citas">      Con menos citas</label>
                                </div>
                            </div>

                        </div>
                    </details>

                </div>

                <button type="submit" class="reporte">Generar reporte</button>

            </form>
        </div>
    </section>

</main>

<?php echo $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/admin_reportes.js') ?>"></script>
<?= $this->endSection() ?>