<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_taller.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_mecanico') ?>

<?php
/*
 * $taller   → array con datos del taller o null si no existe
 * $servicios → array de servicios del taller (puede estar vacío)
 *
 * La lógica de qué mostrar la maneja PHP, no JavaScript:
 *   - Sin taller     → pantalla vacía
 *   - Con taller     → vista completa del taller
 *   - JS solo maneja → mostrar/ocultar el formulario y el modo edición
 */
?>

<main class="main-content">

    <header class="titulos">
        <h1 class="titulo">Tu taller</h1>
        <h5>Registra tu taller, recibe reservas y haz seguimiento</h5>
    </header>

    <!-- ── ALERTAS ─────────────────────────────────────────────────────── -->
    <?php if (session()->getFlashdata('exito') ?? $exito ?? null): ?>
        <div class="alerta-exito" role="alert">
            <?= esc(session()->getFlashdata('exito') ?? $exito) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error') ?? $error ?? null): ?>
        <div class="alerta-error" role="alert">
            <?= esc(session()->getFlashdata('error') ?? $error) ?>
        </div>
    <?php endif; ?>


    <?php if (! $taller): ?>
    <!-- ══════════════════════════════════════════════════════════════════
         PANTALLA INICIAL — El mecánico no tiene taller aún
    ══════════════════════════════════════════════════════════════════ -->
    <div class="tarjeta-autenticacion" id="contenedorInicial">
        <div class="panel-vacio">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            <h1>Todavía no tienes un taller registrado</h1>
            <p>Registra tu taller y tu información comercial para comenzar a gestionar servicios y reservas</p>
            <button type="button" class="btn-principal" onclick="irAFormulario()">
                Ingresar datos
            </button>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         FORMULARIO DE REGISTRO
    ══════════════════════════════════════════════════════════════════ -->
    <div class="form-card oculto" id="contenedorFormulario">
        <div class="form-body">
            <form id="formTaller"
                  method="POST"
                  action="<?= site_url('mecanico/taller/registrar') ?>"
                  enctype="multipart/form-data">

                <?= csrf_field() ?>

                <!-- FOTO -->
                <div class="form-seccion">
                    <div class="form-seccion-titulo">
                        <i class="bi bi-image"></i> Foto del taller
                    </div>
                    <div class="grupo-campo">
                        <div class="zona-foto" id="zonaFoto">
                            <input type="file" accept="image/*" name="foto"
                                   class="input-archivo" id="inputFoto">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <span><strong>Haz clic para subir</strong> o arrastra una imagen aquí</span>
                        </div>
                        <img id="preview-foto" alt="Vista previa del taller">
                    </div>
                </div>

                <!-- INFORMACIÓN GENERAL -->
                <div class="form-seccion">
                    <div class="form-seccion-titulo">
                        <i class="bi bi-info-circle"></i> Información general
                    </div>
                    <div class="fila-formulario">
                        <div class="grupo-campo flex-1">
                            <label class="etiqueta-formulario">Nombre del taller</label>
                            <input type="text" name="nombre" class="input-registro"
                                   placeholder="Nombre característico" maxlength="80" required>
                        </div>
                        <div class="grupo-campo flex-1">
                            <label class="etiqueta-formulario">Especialidad</label>
                            <input type="text" name="especialidad" class="input-registro"
                                   placeholder="ej: Mecánica General" maxlength="70" required>
                        </div>
                    </div>
                    <div class="grupo-campo">
                        <label class="etiqueta-formulario">Descripción</label>
                        <textarea name="descripcion" class="input-registro" rows="3"
                                  placeholder="Descripción breve de tu taller..."
                                  maxlength="150" required></textarea>
                    </div>
                    <div class="grupo-campo">
                        <label class="etiqueta-formulario">Ubicación</label>
                        <input type="text" name="ubicacion" class="input-registro"
                               placeholder="Dirección exacta del local"
                               maxlength="80" required>
                    </div>
                </div>

                <!-- HORARIOS -->
                <div class="form-seccion">
                    <div class="form-seccion-titulo">
                        <i class="bi bi-clock"></i> Horarios de atención
                    </div>
                    <div id="horarios" class="contenedor-dinamico">
                        <div class="fila-dinamica">
                            <input type="text" name="dias[]" class="input-registro"
                                   placeholder="Ej: Lunes - Viernes" maxlength="25" required>
                            <input type="text" name="horas[]" class="input-registro"
                                   placeholder="8:00am-4:00pm o Cerrado" maxlength="40" required>
                            <button type="button" class="btn-eliminar" onclick="borrarFila(this)">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn-agregar" onclick="agregarHorario()">
                        <i class="bi bi-plus-lg"></i> Añadir otro horario
                    </button>
                </div>

                <!-- SERVICIOS Y PRECIOS -->
                <div class="form-seccion">
                    <div class="form-seccion-titulo">
                        <i class="bi bi-tag"></i> Servicios y Precios
                    </div>
                    <div id="servicios" class="contenedor-dinamico">
                        <div class="fila-dinamica">
                            <input type="text" name="servicio[]" class="input-registro"
                                   placeholder="Ej: Sistema de frenos" required>
                            <input type="text" name="precio[]" class="input-registro"
                                   placeholder="$20.000 (precio base)" required>
                            <button type="button" class="btn-eliminar" onclick="borrarFila(this)">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn-agregar" onclick="agregarServicio()">
                        <i class="bi bi-plus-lg"></i> Añadir otro servicio
                    </button>
                </div>

                <button type="submit" class="btn-enviar-registro">
                    Registrar Taller
                </button>

            </form>
        </div>
    </div>

    <?php else: ?>
    <!-- ══════════════════════════════════════════════════════════════════
         VISTA COMPLETA DEL TALLER
    ══════════════════════════════════════════════════════════════════ -->

    <!--
        FORMULARIO OCULTO DE ACTUALIZACIÓN
        ─────────────────────────────────────────────────────────────────
        Vive fuera de #contenidoCompleto para evitar anidamiento de forms.
        JS recopila los valores de los inputs de edición y rellena
        los campos hidden antes de hacer submit().
    -->
    <form id="formActualizar"
          method="POST"
          action="<?= site_url('mecanico/taller/actualizar') ?>"
          enctype="multipart/form-data"
          style="display:none;">

        <?= csrf_field() ?>

        <!-- Campos escalares -->
        <input type="hidden" name="nombre"       id="hidden-nombre">
        <input type="hidden" name="especialidad" id="hidden-especialidad">
        <input type="hidden" name="descripcion"  id="hidden-descripcion">
        <input type="hidden" name="ubicacion"    id="hidden-ubicacion">

        <!--
            Horarios: se generan dinámicamente desde JS antes del submit.
            El contenedor #hidden-horarios-wrap recibe <input> clonados.
        -->
        <div id="hidden-horarios-wrap"></div>

        <!--
            Servicios: igual que los horarios, se generan desde JS.
        -->
        <div id="hidden-servicios-wrap"></div>

        <!--
            Foto: se transfiere el FileList del input real al input del form
            mediante JS justo antes del submit para poder enviarla como multipart.
        -->
        <input type="file" name="foto" id="hidden-foto" accept="image/*" style="display:none;">

    </form>

    <div class="contenido-taller" id="contenidoCompleto">

        <!-- BANNER CON FOTO -->
        <header class="header-taller">
            <?php
            $rutaFoto = $taller['foto_taller']
                ? base_url('uploads/talleres/' . $taller['foto_taller'])
                : base_url('assets/img/taller-placeholder.jpg');
            ?>
            <img id="res-foto"
                 src="<?= esc($rutaFoto) ?>"
                 alt="Foto del taller <?= esc($taller['nombre_taller']) ?>"
                 class="imagen-banner">

            <input type="file" id="edit-res-foto" accept="image/*"
                   class="input-edicion-foto" style="display:none;">
            <label for="edit-res-foto" id="btn-cambiar-foto"
                   class="btn-cambiar-foto" style="display:none;">
                <i class="bi bi-camera-fill"></i> Cambiar Imagen
            </label>

            <div class="overlay-info">
                <h2 class="nombre-taller">
                    <span id="res-nombre" class="texto-vista">
                        <?= esc($taller['nombre_taller']) ?>
                    </span>
                    <input type="text" id="edit-res-nombre"
                           class="input-registro input-edicion"
                           value="<?= esc($taller['nombre_taller']) ?>"
                           maxlength="80"
                           style="display:none; max-width:300px;">
                </h2>
                <span class="badge-especialidad">
                    <?php
                    $nombreEsp = $especialidades[0]['nombre_especialidad'] ?? 'Sin especialidad';
                    ?>
                    <span id="res-especialidad" class="texto-vista"><?= esc($nombreEsp) ?></span>
                    <input type="text" id="edit-res-especialidad"
                           class="input-registro input-edicion"
                           value="<?= esc($nombreEsp) ?>"
                           maxlength="70"
                           style="display:none; color:#333; padding:2px 5px; font-size:12px; border-radius:4px;">
                </span>
            </div>
        </header>

        <!-- DESCRIPCIÓN -->
        <section class="seccion-detalle">
            <div class="cabecera-seccion">
                <h2 class="subtitulo-taller">Descripción</h2>
                <span class="status-badge <?= $taller['estado_taller'] ? 'activo' : 'inactivo' ?>">
                    <?= $taller['estado_taller'] ? 'Activo' : 'Desactivado' ?>
                </span>
            </div>
            <div class="linea-decorativa"></div>
            <div class="texto-descripcion">
                <p id="res-descripcion" class="texto-vista">
                    <?= esc($taller['descripcion_taller']) ?>
                </p>
                <textarea id="edit-res-descripcion"
                          class="input-registro input-edicion"
                          rows="2"
                          maxlength="150"
                          style="display:none; resize:none;"><?= esc($taller['descripcion_taller']) ?></textarea>
            </div>
        </section>

        <div class="contenedor-columnas">

            <!-- HORARIOS Y UBICACIÓN -->
            <section class="seccion-detalle">
                <h2 class="subtitulo-taller">Horarios y Lugar</h2>
                <div class="linea-decorativa"></div>
                <p class="direccion-texto">
                    <i class="bi bi-geo-alt-fill"></i>
                    <strong>Ubicación:</strong><br>
                    <span id="res-ubicacion" class="texto-vista">
                        <?= esc($taller['direccion_taller']) ?>
                    </span>
                    <input type="text" id="edit-res-ubicacion"
                           class="input-registro input-edicion"
                           value="<?= esc($taller['direccion_taller']) ?>"
                           maxlength="80"
                           style="display:none; margin-top:5px;">
                </p>

                <!--
                    Horarios almacenados como texto: "Lunes - Viernes: 8am-6pm | Sábado: 9am-3pm"
                    Se divide por | para mostrar cada línea como <li>.
                    En modo edición aparecen inputs; al guardar JS los recoge
                    y los inyecta como hidden inputs en #formActualizar.
                -->
                <ul class="lista-horarios" id="res-horarios">
                    <?php
                    $partes = explode(' | ', $taller['horario_taller']);
                    foreach ($partes as $parte):
                        $segmentos = explode(': ', $parte, 2);
                        $dia  = trim($segmentos[0] ?? $parte);
                        $hora = trim($segmentos[1] ?? '');
                    ?>
                    <li>
                        <div class="texto-vista">
                            <span class="dia-semana"><?= esc($dia) ?></span>
                            <span class="bloque-hora"><?= esc($hora) ?></span>
                        </div>
                        <div class="input-edicion flex-inputs" style="display:none;">
                            <input type="text" class="input-registro edit-dia"
                                   value="<?= esc($dia) ?>" maxlength="25">
                            <input type="text" class="input-registro edit-hora"
                                   value="<?= esc($hora) ?>" maxlength="40">
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Botón para añadir fila de horario en modo edición -->
                <button type="button"
                        id="btn-agregar-horario-edit"
                        class="btn-agregar input-edicion"
                        style="display:none;"
                        onclick="agregarHorarioEdicion()">
                    <i class="bi bi-plus-lg"></i> Añadir horario
                </button>
            </section>

            <!-- TABLA DE SERVICIOS Y PRECIOS -->
            <section class="seccion-detalle">
                <h2 class="subtitulo-taller">Precios de Servicios</h2>
                <div class="linea-decorativa"></div>
                <table class="tabla-precios">
                    <thead>
                        <tr>
                            <th>Servicio</th>
                            <th>Precio base</th>
                            <!-- Columna de borrar: visible solo en modo edición -->
                            <th class="input-edicion col-borrar" style="display:none;"></th>
                        </tr>
                    </thead>
                    <tbody id="res-servicios">
                        <?php if ($servicios): ?>
                            <?php foreach ($servicios as $servicio): ?>
                            <tr>
                                <td class="celda-servicio">
                                    <span class="texto-vista">
                                        <?= esc($servicio['nombre_servicio']) ?>
                                    </span>
                                    <input type="text"
                                           class="input-registro input-edicion edit-servicio"
                                           value="<?= esc($servicio['nombre_servicio']) ?>"
                                           style="display:none;">
                                </td>
                                <td class="celda-precio">
                                    <span class="texto-vista">
                                        $<?= number_format($servicio['precio_servicio'], 0, ',', '.') ?>
                                    </span>
                                    <input type="text"
                                           class="input-registro input-edicion edit-precio"
                                           value="<?= esc($servicio['precio_servicio']) ?>"
                                           style="display:none;">
                                </td>
                                <td class="input-edicion col-borrar" style="display:none;">
                                    <button type="button" class="btn-eliminar"
                                            onclick="borrarFilaServicioEdicion(this)">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="fila-sin-servicios">
                                <td colspan="3" style="text-align:center; color:#888;">
                                    Sin servicios registrados
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Botón para añadir fila de servicio en modo edición -->
                <button type="button"
                        id="btn-agregar-servicio-edit"
                        class="btn-agregar input-edicion"
                        style="display:none;"
                        onclick="agregarServicioEdicion()">
                    <i class="bi bi-plus-lg"></i> Añadir servicio
                </button>
            </section>

        </div><!-- /.contenedor-columnas -->

        <!-- GESTIÓN DE ACCIONES -->
        <section class="seccion-detalle">
            <div class="bloque-comentario">
                <h2 class="subtitulo-taller">
                    <i class="bi bi-toggles icono-naranja"></i> Gestión de acciones
                </h2>
                <div class="partes">
                    <strong class="tipo">Estado del taller</strong>
                    <strong class="tipo">Información del taller</strong>
                </div>
                <div class="btn-group" style="position:relative;">

                    <?php if ($taller['estado_taller']): ?>
                        <button class="btn-accion btn-rechazar" id="btnDesactivarTaller" type="button">
                            Desactivar
                        </button>

                        <form action="<?= site_url('mecanico/taller/desactivar') ?>"
                              method="POST"
                              id="formMotivo"
                              class="form-flotante-motivo oculto-motivo">
                            <?= csrf_field() ?>
                            <input type="text"
                                   name="motivo"
                                   placeholder="Motivo de desactivación"
                                   class="input-registro"
                                   maxlength="100"
                                   required>
                            <button type="submit" class="btn-enviar-motivo">Enviar</button>
                        </form>

                    <?php else: ?>
                        <form action="<?= site_url('mecanico/taller/activar') ?>"
                              method="POST"
                              style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-accion btn-aprobar">
                                Activar
                            </button>
                        </form>
                    <?php endif; ?>

                    <button class="btn-accion btn-editar" id="btnEditarTaller" type="button">
                        Editar información
                    </button>
                </div>
            </div>
        </section>

    </div><!-- /#contenidoCompleto -->
    <?php endif; ?>

</main>

<?php echo $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/mecanico_taller.js') ?>"></script>
<?= $this->endSection() ?>