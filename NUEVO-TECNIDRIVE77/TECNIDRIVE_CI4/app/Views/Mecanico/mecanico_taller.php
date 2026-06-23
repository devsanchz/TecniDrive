 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_taller.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_mecanico') ?>

   <main class="main-content">

        <header class="titulos">
            <h1 class="titulo">Tu taller</h1>
            <h5>Registra tu taller, recibe reservas y haz seguimiento</h5>
        </header>

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

        <div class="form-card oculto" id="contenedorFormulario">
            <div class="form-body">
                <form id="formTaller">

                    <div class="form-seccion">
                        <div class="form-seccion-titulo">
                            <i class="bi bi-image"></i> Foto del taller
                        </div>
                        <div class="grupo-campo">
                            <div class="zona-foto" id="zonaFoto">
                                <input type="file" accept="image/*" name="foto" class="input-archivo" id="inputFoto">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <span><strong>Haz clic para subir</strong> o arrastra una imagen aquí</span>
                            </div>
                            <img id="preview-foto" alt="Vista previa del taller">
                        </div>
                    </div>

                    <div class="form-seccion">
                        <div class="form-seccion-titulo">
                            <i class="bi bi-info-circle"></i> Información general
                        </div>

                        <div class="fila-formulario">
                            <div class="grupo-campo flex-1">
                                <label class="etiqueta-formulario">Nombre del taller</label>
                                <input type="text" name="nombre" class="input-registro" placeholder="Nombre característico" maxlength="35" required>
                            </div>
                            <div class="grupo-campo flex-1">
                                <label class="etiqueta-formulario">Especialidad</label>
                                <input type="text" name="especialidad" class="input-registro" placeholder="ej: Mecánica General" required>
                            </div>
                        </div>

                        <div class="grupo-campo">
                            <label class="etiqueta-formulario">Descripción</label>
                            <textarea name="descripcion" class="input-registro" rows="3" placeholder="Descripción breve de tu taller..." maxlength="150" required></textarea>
                        </div>

                        <div class="grupo-campo">
                            <label class="etiqueta-formulario">Ubicación</label>
                            <input type="text" name="ubicacion" class="input-registro" placeholder="Dirección exacta del local" maxlength="60" required>
                        </div>
                    </div>

                    <div class="form-seccion">
                        <div class="form-seccion-titulo">
                            <i class="bi bi-clock"></i> Horarios de atención
                        </div>
                        <div id="horarios" class="contenedor-dinamico">
                            <div class="fila-dinamica">
                                <input type="text" name="dias[]" class="input-registro" placeholder="Ej: Lunes - Viernes" maxlength="25" required>
                                <input type="text" name="horas[]" class="input-registro" placeholder="8:00am-4:00pm o Cerrado" maxlength="40" required>
                                <button type="button" class="btn-eliminar" onclick="borrarFila(this)">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-agregar" onclick="agregarHorario()">
                            <i class="bi bi-plus-lg"></i> Añadir otro horario
                        </button>
                    </div>

                    <div class="form-seccion">
                        <div class="form-seccion-titulo">
                            <i class="bi bi-tag"></i> Servicios y Precios
                        </div>
                        <div id="servicios" class="contenedor-dinamico">
                            <div class="fila-dinamica">
                                <input type="text" name="servicio[]" class="input-registro" placeholder="Ej: Sistema de frenos" required>
                                <input type="text" name="precio[]" class="input-registro" placeholder="$20.000 (precio base)" required>
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

        <div class="contenido-taller oculto" id="contenidoCompleto">

            <header class="header-taller">
                <img id="res-foto" src="php" alt="Foto del taller" class="imagen-banner">
                
                <input type="file" id="edit-res-foto" accept="image/*" class="input-edicion-foto" style="display: none;">
                <label for="edit-res-foto" id="btn-cambiar-foto" class="btn-cambiar-foto" style="display: none;">
                    <i class="bi bi-camera-fill"></i> Cambiar Imagen
                </label>

                <div class="overlay-info">
                    <h2 class="nombre-taller">
                        <span id="res-nombre" class="texto-vista">php</span>
                        <input type="text" id="edit-res-nombre" class="input-registro input-edicion" value="Maestro Mecánico" style="display: none; max-width: 300px;">
                    </h2>
                    <span class="badge-especialidad">
                        <span id="res-especialidad" class="texto-vista">php</span>
                        <input type="text" id="edit-res-especialidad" class="input-registro input-edicion" value="Mecánica General" style="display: none; color: #333; padding: 2px 5px; font-size: 12px; border-radius: 4px;">
                    </span>
                </div>
            </header>

            <section class="seccion-detalle">
                <div class="cabecera-seccion">
                    <h2 class="subtitulo-taller">Descripción</h2>
                    <span class="status-badge activo">Activo</span>
                </div>
                <div class="linea-decorativa"></div>
                <div class="texto-descripcion">
                    <p id="res-descripcion" class="texto-vista">php</p>
                    <textarea id="edit-res-descripcion" class="input-registro input-edicion" rows="2" style="display: none; resize: none;">php</textarea>
                </div>
            </section>

            <div class="contenedor-columnas">
                
                <section class="seccion-detalle">
                    <h2 class="subtitulo-taller">Horarios y Lugar</h2>
                    <div class="linea-decorativa"></div>
                    <p class="direccion-texto">
                        <i class="bi bi-geo-alt-fill"></i>
                        <strong>Ubicación:</strong><br>
                        <span id="res-ubicacion" class="texto-vista">php</span>
                        <input type="text" id="edit-res-ubicacion" class="input-registro input-edicion" value="Carrera 34 #38-25 sur" style="display: none; margin-top: 5px;">
                    </p>
                    <ul class="lista-horarios" id="res-horarios">
                        <li>
                            <div class="texto-vista"><span class="dia-semana">php</span> 
                                <span class="bloque-hora">php</span></div>
                            <div class="input-edicion flex-inputs" style="display: none;">
                                <input type="text" class="input-registro edit-dia" value="Lunes - Viernes">
                                <input type="text" class="input-registro edit-hora" value="8:00 - 19:00">
                            </div>
                        </li>
                        <li>
                            <div class="texto-vista"><span class="dia-semana">php</span> 
                                <span class="bloque-hora">php</span></div>
                            <div class="input-edicion flex-inputs" style="display: none;">
                                <input type="text" class="input-registro edit-dia" value="Sábado">
                                <input type="text" class="input-registro edit-hora" value="9:00 - 16:00">
                            </div>
                        </li>
                        <li>
                            <div class="texto-vista"><span class="dia-semana domingo">php</span>
                                 <span class="bloque-hora cerrado">php</span></div>
                            <div class="input-edicion flex-inputs" style="display: none;">
                                <input type="text" class="input-registro edit-dia" value="Domingo">
                                <input type="text" class="input-registro edit-hora" value="Cerrado">
                            </div>
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
                        <tbody id="res-servicios">
                            <tr>
                                <td class="celda-servicio">
                                    <span class="texto-vista">php</span>
                                    <input type="text" class="input-registro input-edicion edit-servicio" value="Sistema de frenos" style="display: none;">
                                </td>
                                <td class="celda-precio">
                                    <span class="texto-vista">php</span>
                                    <input type="text" class="input-registro input-edicion edit-precio" value="Desde $30.000" style="display: none;">
                                </td>
                            </tr>
                            <tr>
                                <td class="celda-servicio">
                                    <span class="texto-vista">Suspensiones</span>
                                    <input type="text" class="input-registro input-edicion edit-servicio" value="Suspensiones" style="display: none;">
                                </td>
                                <td class="celda-precio">
                                    <span class="texto-vista">php</span>
                                    <input type="text" class="input-registro input-edicion edit-precio" value="Desde $25.000" style="display: none;">
                                </td>
                            </tr>
                            <tr>
                                <td class="celda-servicio">
                                    <span class="texto-vista">php</span>
                                    <input type="text" class="input-registro input-edicion edit-servicio" value="Revisión de motor" style="display: none;">
                                </td>
                                <td class="celda-precio">
                                    <span class="texto-vista">php</span>
                                    <input type="text" class="input-registro input-edicion edit-precio" value="Desde $45.000" style="display: none;">
                                </td>
                            </tr>
                            <tr>
                                <td class="celda-servicio">
                                    <span class="texto-vista">php</span>
                                    <input type="text" class="input-registro input-edicion edit-servicio" value="Transmisión" style="display: none;">
                                </td>
                                <td class="celda-precio">
                                    <span class="texto-vista">php</span>
                                    <input type="text" class="input-registro input-edicion edit-precio" value="Desde $35.000" style="display: none;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>

    <section class="seccion-detalle">
    <div class="bloque-comentario">
        <h2 class="subtitulo-taller">
            <i class="bi bi-toggles icono-naranja"></i> Gestión de acciones
        </h2>
        <div class="partes">
            <strong class="tipo">Estado del taller</strong>
            <strong class="tipo">Información del taller</strong>
        </div>
        <div class="btn-group" style="position: relative;"> <button class="btn-accion btn-aprobar" id="btnActivarTaller">Activar</button>
            <button class="btn-accion btn-rechazar" id="btnDesactivarTaller">Desactivar</button>
            
            <form action="" id="formMotivo" class="form-flotante-motivo oculto-motivo">
                <input type="text" placeholder="Motivo de desactivación" class="input-registro" required>
                <button type="submit" class="btn-enviar-motivo">Enviar</button>
            </form>

            <button class="btn-accion btn-editar" id="btnEditarTaller">Editar información</button>
        </div>
    </div>
</section>
        </div>

    </main>



<?php echo $this->endSection()?>


 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/mecanico_taller.js') ?>"></script>
<?= $this->endSection() ?>