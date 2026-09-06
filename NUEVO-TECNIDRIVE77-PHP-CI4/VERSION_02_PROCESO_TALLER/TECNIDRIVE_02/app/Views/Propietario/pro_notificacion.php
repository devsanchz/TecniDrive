 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_notificacion.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_pro') ?>

 <section class="profile-card" id="profileCard">
        
        <div id="panelNotificaciones">
            <div class="cabeza">
                <a href="<?= site_url('propietario/panel') ?>"  aria-label="Volver al panel"><i class="bi bi-chevron-left"></i></a>
                <h1>Sección de notificaciones</h1>
            </div>

            <h2>Recientes</h2>

            <div class="casilla-noti" id="notiNormal">
                <div class="vehiculo">
                    <i class="fa-solid fa-car"></i>
                    <p>php</p>
                </div>
                <strong>php pronto a vencer</strong>
                <div class="botones">
                    <small>php</small>
                    <i class="bi bi-three-dots btn-menu-contextual"></i>
                    <div class="opciones-boton">
                        <p class="accion-quitar">Quitar</p>
                        <p class="abrir-config">Ver configuración de notificaciones</p>
                    </div>
                </div>
            </div>

            <div class="casilla-noti completa" id="notiCompleta">
                <div class="cabeza-interna">
                    <i class="bi bi-chevron-left btn-ocultar-detalle"></i>
                    <strong>php pronto a vencer</strong>
                </div>
                <small class="etiqueta-mensaje">Mensaje:</small>
                <p class="texto-mensaje">php</p>
                <div class="opciones-noti">
                    <button class="btn-renovar">Ya la renové</button>
                    <button class="btn-recordar">Recordar después <br> (en 5 días)</button>
                </div>
            </div>

            <div class="casilla-noti">
                <div class="vehiculo">
                    <i class="bi bi-person-vcard-fill"></i>
                    <p>Licencia</p>
                </div>
                <strong>php está próxima a vencer</strong>
                <div class="botones">
                    <small>php</small>
                    <i class="bi bi-three-dots btn-menu-contextual"></i>
                    <div class="opciones-boton">
                        <p class="accion-quitar">Quitar</p>
                        <p class="abrir-config">Ver configuración de notificaciones</p>
                    </div>
                </div>
            </div>


            <!-- PARTE DE NOTIFICACIONES VISTAS -->
            <h2>Visto</h2>

            <div class="casilla-noti">
                <div class="vehiculo">
                    <i class="fa-solid fa-motorcycle"></i>
                    <p>php</p>
                </div>
                <strong>php vencido</strong>
                <div class="botones">
                    <small>php</small>
                    <i class="bi bi-three-dots btn-menu-contextual"></i>
                    <div class="opciones-boton">
                        <p class="accion-quitar">Quitar</p>
                        <p class="abrir-config">Ver configuración de notificaciones</p>
                    </div>
                </div>
            </div>
        </div>

<!-- ===========================================
        PANEL CONFIGURACION DE NOTIFIACIONES
 ============================================= -->
        <div id="panelConfiguracion" class="hidden">
            <div class="cabeza">
                <i class="bi bi-chevron-left" id="volverNotis"></i>
                <h1>Configuración de notificaciones</h1>
            </div>

            <article class="notification-item">
                <div class="notification-header">
                    <div class="vehicle-info">
                        <i class="fa-solid fa-car"></i>
                        <p>php</p>
                    </div>
                    <button class="toggle-button" aria-label="Desplegar alertas">
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                </div>

                <div class="notification-content hidden">
                    <h2>Alertas del vehículo</h2>
                    <div class="option-row">
                        <span>SOAT</span>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="option-row">
                        <span>Tecnomecánica</span>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="settings-group">
                        <p>Avisar antes del vencimiento</p>
                        <select>
                            <option>3 días antes</option>
                            <option>7 días antes</option>
                            <option>15 días antes</option>
                            <option>30 días antes</option>
                        </select>
                    </div>
                    <div class="settings-group">
                        <p>Repetir recordatorio</p>
                        <label><input type="radio" name="carro1" checked> Nunca</label>
                        <label><input type="radio" name="carro1"> Cada 3 días</label>
                        <label><input type="radio" name="carro1"> Cada 15 días</label>
                        <label><input type="radio" name="carro1"> Cada mes</label>
                    </div>
                </div>
            </article>

            <article class="notification-item">
                <div class="notification-header">
                    <div class="vehicle-info">
                        <i class="fa-solid fa-motorcycle"></i>
                        <p>php</p>
                    </div>
                    <button class="toggle-button" aria-label="Desplegar alertas">
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                </div>

                <div class="notification-content hidden">
                    <h2>Alertas del vehículo</h2>
                    <div class="option-row">
                        <span>SOAT</span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="option-row">
                        <span>Tecnomecánica</span>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="settings-group">
                        <p>Avisar antes del vencimiento</p>
                        <select>
                           <option>3 días antes</option>
                            <option>7 días antes</option>
                            <option>15 días antes</option>
                            <option>30 días antes</option>
                        </select>
                    </div>
                    <div class="settings-group">
                        <p>Repetir recordatorio</p>
                        <label><input type="radio" name="carro2" checked> Nunca</label>
                        <label><input type="radio" name="carro2"> Cada 3 días</label>
                        <label><input type="radio" name="carro2"> Cada 15 días</label>
                        <label><input type="radio" name="carro2"> Cada mes</label>
                    </div>
                </div>
            </article>

            <article class="notification-item">
                <div class="notification-header">
                    <div class="vehicle-info">
                        <i class="bi bi-person-vcard-fill"></i>
                        <p>Tu licencia</p>
                    </div>
                    <button class="toggle-button" aria-label="Desplegar alertas">
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                </div>

                <div class="notification-content hidden">
                    <h2>Alertas del propietario</h2>
                    <div class="option-row">
                        <span>Licencia</span>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="settings-group">
                        <p>Avisar antes del vencimiento</p>
                        <select>
                          <option>3 días antes</option>
                            <option>7 días antes</option>
                            <option>15 días antes</option>
                            <option>30 días antes</option>
                        </select>
                    </div>
                    <div class="settings-group">
                        <p>Repetir recordatorio</p>
                        <label><input type="radio" name="licencia" checked> Nunca</label>
                        <label><input type="radio" name="licencia"> Cada 3 días</label>
                        <label><input type="radio" name="licencia"> Cada 15 días</label>
                        <label><input type="radio" name="licencia"> Cada mes</label>
                    </div>
                </div>
            </article>

            <div class="btn-group">
                <button class="btn-accion btn-restablecer">Restablecer</button>
                <button class="btn-accion btn-guardar">Guardar</button>
            </div>
        </div>

    </section>





<?php echo $this->endSection()?>
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/pro_notificacionn.js') ?>"></script>
<?= $this->endSection() ?>