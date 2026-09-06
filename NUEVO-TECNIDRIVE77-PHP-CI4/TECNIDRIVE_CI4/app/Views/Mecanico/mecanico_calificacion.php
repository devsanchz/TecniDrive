 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_calificacion.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_mecanico') ?>

 <main class="main-content">
        <header class="titulos">
            <h1 class="titulo">Calificaciones del taller</h1>
            <h5>Colsulta las puntuaciones o reseñas de los servicios del taller</h5>
        </header>

        <section class="dashboard-section">
            <div class="numeros-container">
                <div class="tarjeta-cantidad">
                  <span class="num">3.0</span><br>
                    <span>Puntajes promedio</span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num">2</span><br>
                    <span>    Nuevas hoy</span>
                </div>
            </div>
          

            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar fecha, cliente o puntuacion">
                    <i class="bi bi-search"></i>
                </div>

                <select class="filtro-select">
                    <option value="todos">Todos</option>
                    <option value="rechazadas">Solo puntuación</option>
                    <option value="rechazadas">Solo reseñas</option>
                    <option value="rechazadas">Reseñas buenas</option>
                    <option value="rechazadas">Reseñas Malas</option>

                </select>
            </div>

            <table class="tabla-calificaciones">
                <thead>
                    <tr class="cabeza-tabla">
                        <th>Fecha de publicación</th>
                        <th>Cliente</th>
                        <th> Tú Valoración</th>
                       <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fila-cuerpo">
                        <td class="col-registro">
                        <span >30/05/2026</span>
                        </td>

                          <td class="col-propietario">
                            <i class="bi bi-person-check icon-usuario"></i>
                            <div class="usuario-info">
                                <span class="nombre">Pedro Rodrigo Sanchez</span>
                            </div>
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
                 
                        <td class="col-acciones">
                            <div class="btn-group">
                                <button class="btn-accion btn-aprobar">Visto</button>
                             
                            </div>
                        </td>
                    </tr>

                    <tr class="fila-cuerpo">
                        <td class="col-registro">
                       
                            <span>12/04/2025</span>
                        </td>
                           <td class="col-propietario">
                            <i class="bi bi-person-check icon-usuario"></i>
                            <div class="usuario-info">
                                <span class="nombre">Pedro Rodrigo Sanchez</span>
                            </div>
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
             
                        <td class="col-acciones">
                            <div class="btn-group">
                                   <button class="btn-accion btn-aprobar">Visto</button>
                           
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
             <footer class="stats-footer">
                <div>
                    <strong>Mostrando 1-1 de 2 Calificaciones</strong>
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
<script src="<?= base_url('assets/js/mecanico_calificacion.js') ?>"></script>
<?= $this->endSection() ?>