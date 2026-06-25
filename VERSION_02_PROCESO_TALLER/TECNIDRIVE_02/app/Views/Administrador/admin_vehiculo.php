<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_vehiculo.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_admin') ?>

<main class="main-content">
    <header class="titulos">
        <h1 class="titulo">Consultar Vehículos</h1>
        <h4>Supervisa los vehículos registrados y su documentación vigente</h4>
    </header>

    <section class="dashboard-section">

        <!-- CONTADORES -->
        <div class="numeros-container">
            <div class="tarjeta-cantidad">
                <span class="num"><?= $total ?></span><br>
                <span>Total vehículos</span>
            </div>
            <div class="tarjeta-cantidad">
                <span class="num"><?= $papel_completo ?></span><br>
                <span>Papeles Completos</span>
            </div>
        </div>

        <!-- BUSCADOR Y FILTRO -->
        <div class="controles">
            <div class="buscador-wrapper">
                <input type="text" id="buscadorVehiculo"
                       placeholder="Buscar por placa, propietario...">
                <i class="bi bi-search"></i>
            </div>
            <select class="filtro-select" id="filtroEstado">
                <option value="todos">Todos</option>
                <option value="activo">Activos</option>
                <option value="desactivo">Desactivados</option>
            </select>
        </div>

        <!-- TABLA -->
        <table class="tabla-vehiculos">
            <thead>
                <tr class="cabeza-tabla">
                    <th>Placa</th>
                    <th>Registro</th>
                    <th>Estado Vehículo</th>
                    <th>Propietario</th>
                    <th>Marca / Modelo</th>
                    <th>Estado Papeles<br><small>(SOAT y Tecno)</small></th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla">

            <?php if (empty($vehiculos)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:30px; color:#888;">
                        No hay vehículos registrados aún.
                    </td>
                </tr>

            <?php else: ?>
            <?php foreach ($vehiculos as $v):

                $estaActivo  = (bool) $v['estado_vehi'];
                $docs        = $documentos[$v['placa']] ?? [];
                $soat        = $docs[1] ?? null;
                $tecno       = $docs[2] ?? null;

                // Badge de papeles
                if ($soat && $tecno && $soat['estado_papel'] && $tecno['estado_papel']) {
                    $badgePapeles  = 'Ambos al día';
                    $clasePapeles  = 'al-dia';
                } elseif (! $soat && ! $tecno) {
                    $badgePapeles  = 'Sin documentos';
                    $clasePapeles  = 'pendiente';
                } else {
                    $badgePapeles  = 'Incompletos';
                    $clasePapeles  = 'pendiente';
                }

                $colorAvatar = esc($v['avatarcolor'] ?? '#000000');
            ?>
                <tr class="fila-cuerpo"
                    data-placa="<?= esc(strtolower($v['placa'])) ?>"
                    data-nombre="<?= esc(strtolower($v['primer_nombre'] . ' ' . $v['primer_apellido'])) ?>"
                    data-estado="<?= $estaActivo ? 'activo' : 'desactivo' ?>">

                    <!-- Placa -->
                    <td class="col-placa">
                        <strong><?= esc($v['placa']) ?></strong>
                    </td>

                    <!-- Fecha de registro -->
                    <td class="col-fecha">
                        <?= date('d/m/Y', strtotime($v['fecha_registro'])) ?>
                    </td>

                    <!-- Estado del vehículo -->
                    <td class="col-estado">
                        <?php if ($estaActivo): ?>
                            <span class="status-badge activo">Activo</span>
                        <?php else: ?>
                            <div class="estado-con-motivo">
                                <span class="status-badge desactivo">Desactivado</span>
                                <?php if ($v['motivo_estado']): ?>
                                    <i class="bi bi-eye icono-ojo"></i>
                                    <div class="tooltip-motivo">
                                        <?= esc($v['motivo_estado']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- Propietario: nombre + email -->
                    <td class="col-propietario">
                        <i class="bi bi-person-check icon-usuario"
                           style="color: <?= $colorAvatar ?>;"></i>
                        <div class="usuario-info">
                            <span class="nombre">
                                <?= esc($v['primer_nombre'] . ' ' . $v['primer_apellido']) ?>
                            </span>
                            <small class="email"><?= esc($v['email']) ?></small>
                        </div>
                    </td>

                    <!-- Marca / Modelo · Año -->
                    <td class="col-marca">
                        <strong><?= esc($v['nombre_marca']) ?> <?= esc($v['nombre_modelo']) ?></strong><br>
                        <small><?= esc($v['model_year']) ?></small>
                    </td>

                    <!-- Estado de papeles -->
                    <td class="col-papeles">
                        <span class="badge-papeles <?= $clasePapeles ?>">
                            <?= $badgePapeles ?>
                        </span>
                    </td>

                </tr>
            <?php endforeach; ?>
            <?php endif; ?>

            </tbody>
        </table>

        <footer class="stats-footer">
            <div>
                <strong>
                    Mostrando <?= $total ?> vehículo<?= $total !== 1 ? 's' : '' ?>
                </strong>
            </div>
            <div class="pagination">
                <button class="page-btn">«</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">»</button>
            </div>
        </footer>

    </section>
</main>

<?php echo $this->endSection() ?>



<?= $this->section('scripts') ?>
<script>
//1. TOOLTIP DE MOTIVO — versión única

document.querySelectorAll('.icono-ojo').forEach(icono => {
    icono.addEventListener('click', (e) => {
        e.stopPropagation(); // evita que document.click lo cierre al instante

        const tooltip = icono.nextElementSibling; // .tooltip-motivo
        if (!tooltip) return;

        // Cerrar cualquier otro tooltip abierto antes de abrir este
        document.querySelectorAll('.tooltip-motivo.activo').forEach(t => {
            if (t !== tooltip) t.classList.remove('activo');
        });

        tooltip.classList.toggle('activo');
    });
});

// Clic fuera cierra todos los tooltips
document.addEventListener('click', () => {
    document.querySelectorAll('.tooltip-motivo.activo')
            .forEach(t => t.classList.remove('activo'));
});
</script>
<?= $this->endSection() ?>