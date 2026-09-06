<?php echo $this->extend('Estructura/diseño'); ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/mecanico_calificacion.css') ?>">
<style>
    /* Calificación ya marcada como "Visto": fila en gris */
    .fila-vista {
        background-color: #f1f1f1 !important;
        opacity: 0.7;
    }
    .btn-visto-gris {
        background-color: #b0b0b0 !important;
        color: #fff !important;
        cursor: default !important;
    }
</style>
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>
<?= $this->include('Estructura/menu_mecanico') ?>

<main class="main-content">
    <header class="titulos">
        <h1 class="titulo">Calificaciones del taller</h1>
        <h5>Consulta las puntuaciones o reseñas de los servicios del taller</h5>
    </header>

    <section class="dashboard-section">
        <div class="numeros-container">
            <div class="tarjeta-cantidad">
                <span class="num"><?= number_format($promedio, 1) ?></span><br>
                <span>Puntajes promedio</span>
            </div>
            <div class="tarjeta-cantidad">
                <span class="num"><?= $nuevas_hoy ?></span><br>
                <span>Nuevas hoy</span>
            </div>
        </div>

        <div class="controles">
            <div class="buscador-wrapper">
                <input type="text" id="buscadorCalif" placeholder="Buscar fecha, cliente o puntuación">
                <i class="bi bi-search"></i>
            </div>

            <select class="filtro-select" id="filtroCalif">
                <option value="todos">Todos</option>
                <option value="solo-puntuacion">Solo puntuación</option>
                <option value="solo-resena">Solo reseñas</option>
                <option value="buenas">Reseñas buenas</option>
                <option value="malas">Reseñas malas</option>
            </select>
        </div>

        <table class="tabla-calificaciones">
            <thead>
                <tr class="cabeza-tabla">
                    <th>Fecha de publicación</th>
                    <th>Cliente</th>
                    <th>Tú Valoración</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cuerpoTabla">

            <?php if (empty($calificaciones)): ?>
                <tr>
                    <td colspan="4" style="text-align:center; padding:30px; color:#888;">
                        Aún no tienes calificaciones registradas.
                    </td>
                </tr>

            <?php else: ?>
            <?php foreach ($calificaciones as $cal):
                $tienComentario = ! empty($cal['comentario']);
                $puntuacion     = (int) $cal['puntuacion'];
                $colorAvatar    = esc($cal['avatarcolor'] ?? '#000000');
                $fechaFmt       = date('d/m/Y', strtotime($cal['fecha_registro']));
            ?>
                <tr class="fila-cuerpo <?= $cal['visto'] ? 'fila-vista' : '' ?>"
                    data-tipo="<?= $tienComentario ? 'resena' : 'puntuacion' ?>"
                    data-calificacion="<?= $puntuacion >= 4 ? 'buena' : 'mala' ?>">

                    <td class="col-registro">
                        <span><?= $fechaFmt ?></span>
                    </td>

                    <td class="col-propietario">
                        <i class="bi bi-person-check icon-usuario"
                           style="color: <?= $colorAvatar ?>;"></i>
                        <div class="usuario-info">
                            <span class="nombre">
                                <?= esc($cal['primer_nombre'] . ' ' . $cal['primer_apellido']) ?>
                            </span>
                        </div>
                    </td>

                    <td class="col-puntuacion">
                        <div class="estrellas-wrapper">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $puntuacion): ?>
                                    <i class="bi bi-star-fill star"></i>
                                <?php else: ?>
                                    <i class="bi bi-star-black star-empty"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <?php if ($tienComentario): ?>
                            <span class="col-comentario"><?= esc($cal['comentario']) ?></span>
                        <?php endif; ?>
                    </td>

                    <td class="col-acciones">
                        <div class="btn-group">
                            <?php if ($cal['visto']): ?>
                                <button class="btn-accion btn-visto-gris" type="button" disabled>
                                    Visto
                                </button>
                            <?php else: ?>
                                <form method="post"
                                      action="<?= site_url('mecanico/calificacion/marcar-visto') ?>"
                                      class="form-marcar-visto">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_calificacion" value="<?= $cal['id_calificacion'] ?>">
                                    <button class="btn-accion btn-aprobar" type="submit">
                                        Visto
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>

                </tr>
            <?php endforeach; ?>
            <?php endif; ?>

            </tbody>
        </table>

        <footer class="stats-footer">
            <div>
                <strong>Mostrando <?= count($calificaciones) ?> calificación<?= count($calificaciones) !== 1 ? 'es' : '' ?></strong>
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
<script src="<?= base_url('assets/js/mecanico_calificacion.js') ?>"></script>

<script>
// Buscador + filtro en tiempo real (sin librerías extra)
const buscador = document.getElementById('buscadorCalif');
const filtro    = document.getElementById('filtroCalif');
const filas     = document.querySelectorAll('#cuerpoTabla .fila-cuerpo');

function filtrarCalif() {
    const texto = buscador ? buscador.value.toLowerCase() : '';
    const valor = filtro ? filtro.value : 'todos';

    filas.forEach(fila => {
        const textoFila = fila.textContent.toLowerCase();
        const tipo      = fila.dataset.tipo;
        const nivel     = fila.dataset.calificacion;

        const coincideTexto = texto === '' || textoFila.includes(texto);

        let coincideFiltro = true;
        if (valor === 'solo-puntuacion') coincideFiltro = tipo === 'puntuacion';
        if (valor === 'solo-resena')     coincideFiltro = tipo === 'resena';
        if (valor === 'buenas')          coincideFiltro = nivel === 'buena';
        if (valor === 'malas')           coincideFiltro = nivel === 'mala';

        fila.style.display = (coincideTexto && coincideFiltro) ? '' : 'none';
    });
}

if (buscador) buscador.addEventListener('input', filtrarCalif);
if (filtro)   filtro.addEventListener('change', filtrarCalif);
</script>
<?= $this->endSection() ?>