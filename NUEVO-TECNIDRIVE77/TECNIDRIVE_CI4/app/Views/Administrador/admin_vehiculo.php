 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_vehiculo.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_admin') ?>
  <main class="main-content">
        <header class="titulos">
            <h1 class="titulo">Consultar Vehículos</h1>
            <h4>Supervisa los vehículos registrados y su documentación vigente</h4>
        </header>

        <section class="dashboard-section">
            <div class="numeros-container">
                <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span>Total vehículos</span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span>Registrados Hoy</span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num">php</span><br>
                    <span>Papeles Completos</span>
                </div>
            </div>

            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar por placa, propietario o fecha...">
                    <i class="bi bi-search"></i>
                </div>

                <select class="filtro-select">
                    <option value="todos">Todos</option>
                    <option value="recientes">Recientes</option>
                    <option value="sin-papeles">Sin papeles aún</option>
                    <option value="con-todo">Con todo al día</option>
                </select>
            </div>

            <table class="tabla-vehiculos">
                <thead>
                    <tr class="cabeza-tabla">
                        <th>Placa</th>
                        <th>Estado Vehículo</th>
                        <th>Propietario</th>
                        <th>Marca / Modelo</th>
                        <th>Fecha de Registro</th>
                        <th>Estado Papeles<br><small>(SOAT y Tecno)</small></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fila-cuerpo">
                        <td class="col-placa">php</td>
                        <td class="col-estado">
                            <span class="status-badge activo">php</span>
                        </td>
                        <td class="col-propietario">
                            <i class="bi bi-person-check icon-usuario"></i>
                            <div class="usuario-info">
                                <span class="nombre">php</span>
                                <small class="email">php</small>
                            </div>
                        </td>
                        <td class="col-marca">php</td>
                        <td class="col-registro">php<br><small>php</small></td>
                        <td class="col-papeles">
                            <span class="badge-papeles al-dia">Ambos al día</span>
                        </td>
                    </tr>

                    <tr class="fila-cuerpo">
                        <td class="col-placa">phph</td>
                        <td class="col-estado">
                            <div class="estado-con-motivo">
                                <span class="status-badge desactivo">php</span>
                                <i class="bi bi-eye icono-ojo"></i>
                                <div class="tooltip-motivo">
                                  php
                                </div>
                            </div>
                        </td>
                        <td class="col-propietario">
                            <i class="bi bi-person-check icon-usuario"></i>
                            <div class="usuario-info">
                                <span class="nombre">php</span>
                                <small class="email">php</small>
                            </div>
                        </td>
                        <td class="col-marca">php</td>
                        <td class="col-registro">php<br><small>php</small></td>
                        <td class="col-papeles">
                            <span class="badge-papeles al-dia">Ambos al día</span>
                        </td>
                    </tr>

                    <tr class="fila-cuerpo">
                        <td class="col-placa">php</td>
                        <td class="col-estado">
                            <span class="status-badge activo">php</span>
                        </td>
                        <td class="col-propietario">
                            <i class="bi bi-person-check icon-usuario"></i>
                            <div class="usuario-info">
                                <span class="nombre">php</span>
                                <small class="email">php</small>
                            </div>
                        </td>
                        <td class="col-marca">php</td>
                        <td class="col-registro">php<br><small>php</small></td>
                        <td class="col-papeles">
                            <span class="badge-papeles pendiente">Pendiente <strong>SOAT</strong></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <footer class="stats-footer">
                <div>
                    <strong>Mostrando 1-3 de 3 Vehículos</strong>
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
<script src="<?= base_url('assets/js/admin_vehiculo.js') ?>"></script>
<?= $this->endSection() ?>