<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_vehiculo.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_pro') ?>

<header class="titulos">
    <h1 class="titulo">Tus Vehículos</h1>
    <h5>Supervisa los vehículos registrados y su documentación vigente</h5>
</header>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alerta-error"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error_vehiculo')): ?>
    <div class="alerta-error"><?= session()->getFlashdata('error_vehiculo') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('vehiculo_guardado')): ?>
    <div class="alerta-exito">Vehículo registrado correctamente.</div>
<?php endif; ?>


<?php if (!$tiene_licencia): ?>
<!-- ══════════════════════════════════════════════
     SIN LICENCIA — mostrar formulario de registro
══════════════════════════════════════════════ -->
<div class="tarjeta-autenticacion">

    <div class="panel-vacio" id="panelInicial">
        <i class="bi bi-person-vcard-fill principal"></i>
        <h1>Aún no tienes licencia ni vehículos registrados</h1>
        <p>Comienza configurando tu perfil registrando tu licencia de conducción.</p>
        <button type="button" class="btn-principal" onclick="irAFormulario()">
            Ingresar datos
        </button>
    </div>

    <form id="formLicencia"
          class="hidden"
          method="post"
          action="<?= site_url('propietario/guardar-licencia') ?>">
        <?= csrf_field() ?>

        <h1>Registro de Licencia</h1>

        <label>Número de licencia</label>
        <div class="grupo-input">
            <input type="text"
                   name="numero_licencia"
                   placeholder="Ej: 10102345671"
                   maxlength="11"
                   required>
        </div>

        <div id="contenedorCategorias">
            <div class="categoria-item" id="categoria-1">
                <div class="fila-doble">
                    <div class="campo-bloque">
                        <label>Categoría</label>
                        <input type="text" name="categoria[]"
                               placeholder="Ej: B1" maxlength="3" required>
                    </div>
                    <div class="campo-bloque">
                        <label>Fecha vigencia</label>
                        <input type="date" name="fecha[]" required>
                    </div>
                    <button type="button" class="btn-eliminar-fila invisible">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="button" class="btn-accion-secundaria" onclick="agregarCategoria()">
            <i class="bi bi-plus-lg"></i> Agregar otra categoría
        </button>

        <button type="submit" class="btn-principal btn-guardar">Guardar licencia</button>
    </form>
</div>




<?php else: ?>
<!-- ══════════════════════════════════════════════
     CON LICENCIA — mostrar dashboard directamente
══════════════════════════════════════════════ -->
<main class="dashboard-section" id="dashboardvehiculos">

    <section class="controles">

        <!-- Tarjeta licencia -->
        <div class="tarjeta-licencia">
            <div class="header-licencia">
                <p class="titulo-licencia">
                    <i class="bi bi-person-vcard-fill"></i> Tu licencia
                </p>
                <button class="btn-desplegar" type="button">
                    <i class="bi bi-caret-down-fill"></i>
                </button>
            </div>

            <div class="info-licencia hidden">
                <div class="conten">
                    <div class="cates">
                        <strong>Categorías</strong>
                        <ul class="lista-categorias">
                            <?php foreach ($mis_categorias as $cat): ?>
                                <li><?= esc($cat['tipo_categoria']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="viges">
                        <strong>Fecha de vigencia</strong>
                        <?php foreach ($mis_categorias as $cat): ?>
                            <p class="texto-detalle">
                                <?= esc($cat['vigencia_lice']) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="numer">
                    <strong>Número de licencia</strong>
                    <p class="texto-detalle si"><?= esc($numero_licencia) ?></p>
                </div>
            </div>
        </div>


        <!-- Agregar vehículo -->
        <div class="tarjeta-agregar-vehiculo">
            <div class="header-licencia">
                <p class="titulo-vehiculo">Agregar Vehículo</p>
                <button class="btn-desplegar btn-desplegar-form" type="button">
                    <i class="bi bi-caret-down-fill"></i>
                </button>
            </div>

            <form class="formulario-vehiculo<?= (session()->getFlashdata('error_vehiculo') || session()->getFlashdata('vehiculo_guardado')) ? '' : ' hidden' ?>"
                  method="post"
                  action="<?= site_url('propietario/guardar-vehiculo') ?>">
                <?= csrf_field() ?>
                <h2>Agregar datos</h2>
                <input type="text" name="placa" placeholder="Placa" maxlength="6" required>
                <input type="text" name="marca" placeholder="Marca" maxlength="25" required>
                <input type="text" name="modelo" placeholder="Modelo" maxlength="28" required>
                <input type="text" name="ano" placeholder="Año" maxlength="4" required>
                <select name="tipo_vehi" id="selectTipoVehi" required>
                    <option value="">Tipo de vehículo</option>
                    <option value="1">Carro</option>
                    <option value="2">Moto</option>
                </select>
                <span id="iconoTipoVehi"></span>
                <select name="tipo_servicio" required>
                    <option value="">Servicio del vehículo</option>
                    <option value="1">Particular</option>
                    <option value="2">Público</option>
                </select>
                <button type="submit" class="btn-guardar-vehiculo">Guardar vehículo</button>
            </form>
        </div>

    </section>


    <!-- Tabla de vehículos -->
    <section class="tabla-wrapper">
        <table class="tabla-calificaciones">
            <thead>
                <tr class="cabeza-tabla">
                    <th>Datos básicos</th>
                    <th>Estado del vehículo</th>
                    <th>Documentos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php if (empty($mis_vehiculos)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding:2rem; color:#888;">
                        <i class="bi bi-car-front" style="font-size:2rem; display:block; margin-bottom:.5rem;"></i>
                        Sin vehículos registrados aún
                    </td>
                </tr>

                <?php else: ?>
                <?php foreach ($mis_vehiculos as $v): ?>
                <tr class="fila-cuerpo">

                    <td class="col-datos-basicos">
                        <div class="bloque-vehiculo">
                            <div class="badge-vehiculo">
                                <?php if ((int) $v['tipos_vehiculo_id_tipo_vehi'] === 2): ?>
                                    <i class="fa-solid fa-motorcycle"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-car"></i>
                                <?php endif; ?>
                                <p class="texto-placa"><?= esc($v['placa']) ?></p>
                            </div>
                            <div class="info-texto-vehiculo">
                                <strong class="nombre-vehiculo"><?= esc($v['nombre_marca']) ?> <?= esc($v['nombre_modelo']) ?> · <?= esc($v['model_year']) ?></strong>
                                <span class="tipo-servicio">
                                    <?= $v['servicio_vehiculo_id_tipo_servicio'] == 1 ? 'Particular' : 'Público' ?>
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="col-estado-vehiculo">
                        <span class="status-badge <?= $v['estado_vehi'] ? 'activo' : 'inactivo' ?>">
                            <?= $v['estado_vehi'] ? 'Activo' : 'Desactivado' ?>
                        </span>
                    </td>

                    <td class="col-documentos-vehiculo">
                        <?php
                            $docs       = $mis_documentos[$v['placa']] ?? [];
                            $soat       = $docs[1] ?? null;
                            $tecno      = $docs[2] ?? null;
                            $tieneDocs  = $soat || $tecno;
                        ?>
                        <button type="button" class="btn-ver-docs">
                            <span><?= $tieneDocs ? 'Ver documentos' : 'Sin documentos aún' ?></span>
                            <i class="bi bi-caret-down-fill"></i>
                        </button>
                        <form class="contenedor-documentos hidden"
                              method="post"
                              action="<?= site_url('propietario/guardar-documentos') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="placa" value="<?= esc($v['placa']) ?>">

                            <div class="grupo-input-doc">
                                <strong>SOAT</strong>
                                <label>Vigencia:
                                    <input type="date" name="soat"
                                           value="<?= esc($soat['fecha_vencimiento'] ?? '') ?>">
                                </label>
                                <?php if ($soat): ?>
                                    <span class="status-badge <?= $soat['estado_papel'] ? 'activo' : 'inactivo' ?>">
                                        <?= $soat['estado_papel'] ? 'Vigente' : 'Vencido' ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="grupo-input-doc">
                                <strong>Tecnomecánica</strong>
                                <label>Vigencia:
                                    <input type="date" name="tecnomecanica"
                                           value="<?= esc($tecno['fecha_vencimiento'] ?? '') ?>">
                                </label>
                                <?php if ($tecno): ?>
                                    <span class="status-badge <?= $tecno['estado_papel'] ? 'activo' : 'inactivo' ?>">
                                        <?= $tecno['estado_papel'] ? 'Vigente' : 'Vencido' ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn-guardar-docs">Guardar</button>
                        </form>
                    </td>

                    <td class="col-acciones-vehiculo" style="position:relative;">
                        <div class="grupo-botones-accion">

                            <form method="post" action="<?= site_url('propietario/activar-vehiculo') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="placa" value="<?= esc($v['placa']) ?>">
                                <button type="submit" class="btn-accion btn-aprobar">Activar</button>
                            </form>

                            <button type="button" class="btn-accion btn-rechazar">Desactivar</button>

                            <form method="post"
                                  action="<?= site_url('propietario/desactivar-vehiculo') ?>"
                                  class="hidden form-motivo"
                                  style="position:absolute; z-index:1050; background:#fff;
                                         border:1px solid #cbd5e0; padding:10px;
                                         border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15);
                                         width:180px; right:10px; top:10px;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="placa" value="<?= esc($v['placa']) ?>">
                                <input type="text" name="motivo" placeholder="Motivo" style="margin-bottom:5px;" required>
                                <button type="submit" class="btn-guardar-docs"
                                        style="margin:0 auto; width:100%;">Enviar</button>
                            </form>
                        </div>
                    </td>

                </tr>
                <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>
    </section>

</main>
<?php endif; ?>


<?php echo $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/pro_vehiculo.js') ?>"></script>
<script>
    const selectTipoVehi = document.getElementById('selectTipoVehi');
    const iconoTipoVehi  = document.getElementById('iconoTipoVehi');

    if (selectTipoVehi) {
        selectTipoVehi.addEventListener('change', () => {
            if (selectTipoVehi.value === '1') {
                iconoTipoVehi.innerHTML = '<i class="fa-solid fa-car"></i>';
            } else if (selectTipoVehi.value === '2') {
                iconoTipoVehi.innerHTML = '<i class="fa-solid fa-motorcycle"></i>';
            } else {
                iconoTipoVehi.innerHTML = '';
            }
        });
    }
</script>
<?= $this->endSection() ?>