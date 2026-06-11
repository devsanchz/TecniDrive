 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_calificacionn.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_admin') ?>
   <main class="main-content">
        <header class="titulos">
            <h1 class="titulo">Monitoreo de Calificaciones</h1>
            <h5>Revisa y elimina comentarios no aptos</h5>
        </header>

        <section class="dashboard-section">
            <div class="numeros-container">
                <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span>Nuevas hoy</span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span>Aprobadas</span>
                </div>
                  <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span>Total de Reseñas</span>
                </div>
            </div>

            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar por cliente, taller o comentario...">
                    <i class="bi bi-search"></i>
                </div>

                <select class="filtro-select">
                    <option value="todos">Todos</option>
                    <option value="pendientes">Pendientes</option>
                    <option value="rechazadas">Rechazadas</option>
                </select>
            </div>

            <table class="tabla-calificaciones">
                <thead>
                    <tr class="cabeza-tabla">
                        <th>Cliente</th>
                        <th>Taller</th>
                        <th>Puntuación</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fila-cuerpo">
                        <td class="col-cliente">
                            <i class="bi bi-person-check icon-cliente">php</i> 
                        <span class="nombre">php</span>
                        </td>
                        <td class="col-taller">
                            <strong>php</strong><br>
                            <span class="subtexto-taller">php</span>
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
                        <td class="col-comentario">php</td>
                        <td class="col-fecha">php</td>
                        <td class="col-estado">
                            <span class=" badge-pendiente">php</span>
                        </td>
                        <td class="col-acciones">
                            <div class="btn-group">
                                <button class="btn-accion btn-aprobar">Aprobar</button>
                                <button class="btn-accion btn-rechazar">Rechazar</button>
                            </div>
                        </td>
                    </tr>

                    <tr class="fila-cuerpo">
                        <td class="col-cliente">
                            <i class="bi bi-person-check icon-cliente"></i> 
                            <span class="nombre">php</span>
                        </td>
                        <td class="col-taller">
                            <strong>php</strong><br>
                            <span class="subtexto-taller">php</span>
                        </td>
                        <td class="col-puntuacion">
                            <div class="estrellas-wrapper"> php 
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-fill star"></i>
                                <i class="bi bi-star-black star-empty"></i>
                                <i class="bi bi-star-black star-empty"></i>
                                <i class="bi bi-star-black star-empty"></i>
                                
                            </div>
                        </td>
                        <td class="col-comentario">php</td>
                        <td class="col-fecha">php</td>
                        <td class="col-estado">
                            <span class="status-badge badge-pendiente">php</span>
                        </td>
                        <td class="col-acciones">
                            <div class="btn-group">
                                <button class="btn-accion btn-aprobar">Aprobar</button>
                                <button class="btn-accion btn-rechazar">Rechazar</button>
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


<?php echo $this->endSection()?>
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/admin_califiacion.js') ?>"></script>
<?= $this->endSection() ?>