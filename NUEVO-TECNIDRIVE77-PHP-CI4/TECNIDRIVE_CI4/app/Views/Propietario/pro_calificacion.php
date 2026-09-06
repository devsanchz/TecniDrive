 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_calificacion.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_pro') ?>

    <main class="main-content">
        <header class="titulos">
            <h1 class="titulo">Reseña y puntuacíon de talleres</h1>
            <h5>Revisa o actualiza las califiaciones que has dado a los talleres</h5>
        </header>

        <section class="dashboard-section">
          
            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar fecha, palabra clave o nombre del taller...">
                    <i class="bi bi-search"></i>
                </div>

                <select class="filtro-select">
                    <option value="todos">Todos</option>
                    <option value="pendientes">Solo Puntuación</option>
                    <option value="rechazadas">Solo reseñas</option>
                    <option value="rechazadas">No aprobadas</option>
                    <option value="rechazadas">Sin aprobación</option>
                </select>
            </div>

            <table class="tabla-calificaciones">
                <thead>
                    <tr class="cabeza-tabla">
                        <th>Fecha de publicación</th>
                        <th> Nombre del Taller</th>
                        <th> Tú Valoración</th>
                       <th>Estado de publicación</th>
                        <th>Lugar de publicación</th>
                       <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fila-cuerpo">
                        <td class="col-cliente">
                        <span class="nombre">30/05/2026</span>
                        </td>
                        <td class="col-taller">
                            <strong>Maestro Mecánico</strong><br>
                            <span class="subtexto-taller">Mecánica General</span>
                        </td>
                        <td class="col-puntuacion">
                            <div class="estrellas-wrapper">  
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-fill star"></i>
                            </div>
                        </td>
                      
                        <td class="col-estado">
                            <span class=" badge-publicado">Publicado</span>
                        </td>

                         <td >    <button class="btn-accion btn-ver">Ver publicación</button></td>
                        <td class="col-acciones">
                            <div class="btn-group">
                                <button class="btn-accion btn-aprobar">Actualizar</button>
                                <button class="btn-accion btn-rechazar">Eliminar</button>
                            </div>
                        </td>
                    </tr>

                    <tr class="fila-cuerpo">
                        <td class="col-cliente">
                            <span class="nombre">12/04/2025</span>
                        </td>
                        <td class="col-taller">
                            <strong>Maestro Mecánico</strong><br>
                            <span class="subtexto-taller">Mecánica General</span>
                        </td>
                        <td class="col-puntuacion">
                            <div class="estrellas-wrapper">  
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-black star-empty"></i>
                                <i class="bi bi-star-black star-empty"></i>
                                <i class="bi bi-star-black star-empty"></i>
                            </div>
                            <span class="col-comentario">Excelente servicio, muy rápido y confiable. Recomendado</span>
                        </td>
             
                        <td class="col-estado">
                            <span class="status-badge badge-pendiente">Pendiente</span>
                        </td>

                        <td >    <button class="btn-accion btn-ver">Ver publicación</button></td>
                        <td class="col-acciones">
                            <div class="btn-group">
                                   <button class="btn-accion btn-aprobar">Actualizar</button>
                                <button class="btn-accion btn-rechazar">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
             <footer class="stats-footer">
                <div>
                    <strong>Mostrando 1-1 de 2 Reseñas</strong>
                </div>
                <div class="pagination">
                    <button class="page-btn">«</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">»</button>
                </div>
            </footer>
        </section>
    </main>

    <div id="modal-editar" class="hidden modal-overlay">
        <div class="modal-contenedor">
            <h3 class="modal-titulo">Editar Valoración</h3>
            
            <div>
                <label class="modal-etiqueta">Calificación:</label>
                <div id="modal-estrellas" class="modal-estrellas-grupo">
                    <i class="bi bi-star-black star-modal" data-index="1"></i>
                    <i class="bi bi-star-black star-modal" data-index="2"></i>
                    <i class="bi bi-star-black star-modal" data-index="3"></i>
                    <i class="bi bi-star-black star-modal" data-index="4"></i>
                    <i class="bi bi-star-black star-modal" data-index="5"></i>
                </div>
            </div>
            
            <div>
                <label class="modal-etiqueta">Comentario:</label>
                <textarea id="modal-comentario-text" rows="3"></textarea>
            </div>
            
            <div class="modal-botones-contenedor">
                <button id="btn-cancelar-modal" type="button">Cancelar</button>
                <button id="btn-guardar-modal" type="button">Guardar</button>
            </div>
        </div>
    </div>
<?php echo $this->endSection()?>


 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/pro_calificacion.js') ?>"></script>
<?= $this->endSection() ?>
