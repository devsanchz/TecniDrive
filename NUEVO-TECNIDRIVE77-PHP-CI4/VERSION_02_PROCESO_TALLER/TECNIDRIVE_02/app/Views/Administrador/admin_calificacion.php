<?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/admin_calificacionnn.css') ?>">
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
                    <span class="num"><?= esc($total) ?></span><br>
                    <span>Total</span>
                </div>
                <div class="tarjeta-cantidad">
                    <span class="num"><?= esc($totalRechazadas) ?></span><br>
                    <span>Rechazadas</span>
                </div>
                  <div class="tarjeta-cantidad">
                    <span class="num"><?= esc($totalComentarios) ?></span><br>
                    <span>Total de Comentarios</span>
                </div>
            </div>

            <div class="controles">
                <div class="buscador-wrapper">
                    <input type="text" placeholder="Buscar por cliente, taller o comentario...">
                    <i class="bi bi-search"></i>
                </div>

                <select class="filtro-select" id="filtroEstado">
                    <option value="todos">Todos</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="rechazada">Rechazadas</option>
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
                    <?php foreach ($calificaciones as $cal): ?>
                    <tr class="fila-cuerpo" data-estado="<?= esc($cal['estado']) ?>">
                        <td class="col-cliente">
                            <i class="bi bi-person-check icon-cliente" style="color: <?= esc($cal['avatarcolor'] ?? '#cccccc') ?>"></i>
                            <span class="nombre"><?= esc($cal['primer_nombre'] . ' ' . $cal['primer_apellido']) ?></span>
                        </td>
                        <td class="col-taller">
                            <strong><?= esc($cal['nombre_taller']) ?></strong>
                        </td>
                        <td class="col-puntuacion">
                            <div class="estrellas-wrapper">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= (int) $cal['puntuacion']): ?>
                                        <i class="bi bi-star-fill star"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star-black star-empty"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </td>
                        <td class="col-comentario"><?= esc($cal['comentario'] ?? '') ?></td>
                        <td class="col-fecha"><?= esc($cal['fecha_registro']) ?></td>
                        <td class="col-estado">
                            <?php if ($cal['estado'] === 'pendiente'): ?>
                                <span class="status-badge badge-pendiente">Pendiente</span>
                            <?php elseif ($cal['estado'] === 'rechazada'): ?>
                                <span class="status-badge badge-rechazado">Rechazada</span>
                            <?php else: ?>
                                <span class="status-badge badge-aprobado">Publicado</span>
                            <?php endif; ?>
                        </td>
                        <td class="col-acciones">
                            <?php if ($cal['estado'] === 'pendiente'): ?>
                            <div class="btn-group">
                                <button class="btn-accion btn-aprobar" data-id="<?= $cal['id_calificacion'] ?>">Aceptar</button>
                                <button class="btn-accion btn-rechazar" data-id="<?= $cal['id_calificacion'] ?>">Rechazar</button>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
             <footer class="stats-footer">
                <div>
                    <strong>Mostrando <?= count($calificaciones) ?> Reseñas</strong>
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
<!--
    Token CSRF expuesto para los fetch() JSON de aceptar/rechazar.
-->
<meta name="csrf-name" content="<?= csrf_token() ?>">
<meta name="csrf-hash" content="<?= csrf_hash() ?>">
<script>
    const ACEPTAR_URL  = "<?= site_url('administrador/calificacion/aceptar') ?>";
    const RECHAZAR_URL = "<?= site_url('administrador/calificacion/rechazar') ?>";

    // Helper CSRF reutilizable, mismo patrón usado en panel_pro.js / pro_taller02.js
    function obtenerCsrf() {
        const metaName = document.querySelector('meta[name="csrf-name"]');
        const metaHash = document.querySelector('meta[name="csrf-hash"]');
        return {
            nombre: metaName ? metaName.content : null,
            valor:  metaHash ? metaHash.content : null,
        };
    }

    function actualizarCsrf(json) {
        if (json && json.csrf_token_value) {
            const metaHash = document.querySelector('meta[name="csrf-hash"]');
            if (metaHash) metaHash.content = json.csrf_token_value;
        }
    }

    async function postJSON(url, payload) {
        const csrf = obtenerCsrf();
        const resp = await fetch(url, {
            method:      'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type':     'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN':     csrf.valor,
            },
            body: JSON.stringify(payload),
        });
        const json = await resp.json();
        actualizarCsrf(json);
        return json;
    }

    document.getElementById('filtroEstado')?.addEventListener('change', (e) => {
        const valor = e.target.value;
        document.querySelectorAll('.fila-cuerpo').forEach(fila => {
            fila.style.display = (valor === 'todos' || fila.dataset.estado === valor) ? '' : 'none';
        });
    });

    document.querySelectorAll('.btn-aprobar, .btn-rechazar').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id  = btn.dataset.id;
            const url = btn.classList.contains('btn-aprobar') ? ACEPTAR_URL : RECHAZAR_URL;

            try {
                const data = await postJSON(url, { id });
                if (data.ok) {
                    location.reload();
                } else {
                    alert('No se pudo procesar la acción.');
                }
            } catch {
                alert('No se pudo conectar.');
            }
        });
    });
</script>
<script src="<?= base_url('assets/js/admin_califiacion.js') ?>"></script>
<?= $this->endSection() ?>