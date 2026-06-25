<?php echo $this->extend('Estructura/diseño'); ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/rol.css') ?>">
<?= $this->endSection() ?>

<?php echo $this->section('contenido') ?>

<div class="contenedor">

    <div class="cabeza">
        <a href="<?= site_url('autentificar/registro') ?>" title="Volver">
            <i class="bi bi-chevron-left"></i>
        </a>
        <h1>¿Qué rol quieres usar en TecniDrive?</h1>
    </div>

    <p class="subtitulo">
        Elige el tipo de cuenta que mejor se adapte a ti
    </p>

    <?php
    /*
     * Recuperar errores de validación del rol enviados como flashdata
     * desde procesarRol() cuando no se seleccionó ninguna opción.
     */
    $erroresRol = session()->getFlashdata('errores_rol') ?? $errores ?? [];
    ?>

    <?php if (isset($erroresRol['rol'])): ?>
        <!-- Error visible si el usuario presiona Finalizar sin elegir rol -->
        <span class="error-campo" role="alert">
            <?= esc($erroresRol['rol']) ?>
        </span>
    <?php endif; ?>

    <!--
        CAMBIOS RESPECTO AL ORIGINAL:
        1. <form> con method="POST" y action correctos (faltaba completamente)
        2. <?= csrf_field() ?> → token CSRF
        3. value="1" y value="2" en los radio buttons (sin value no se envía nada)
        4. Se eliminó la tarjeta "Propietario + Taller" del formulario principal
           porque según el diseño de BD solo hay dos roles base; ese plan
           implica lógica de pago que se implementa por separado.
        5. El </form> se movió al lugar correcto (estaba antes del </div>)
    -->
    <form method="POST" action="<?= site_url('autentificar/rol') ?>">

        <?= csrf_field() ?>

        <div class="roles">

            <!-- PROPIETARIO — id_rol = 1 -->
            <label class="card">
               <input type="radio" name="rol" value="2">  <!-- Propietario -->

                <div class="icono">
                    <i class="bi bi-car-front-fill"></i>
                </div>
                <h2>Propietario</h2>
                <p>
                    Gestiona tus vehículos, recuerda documentos importantes,
                    busca talleres y agenda citas fácilmente.
                </p>
            </label>

            <!-- MECÁNICO — id_rol = 2 -->
            <label class="card">
              <input type="radio" name="rol" value="3">  <!-- Mecánico    -->
                <div class="icono">
                    <i class="bi bi-tools"></i>
                </div>
                <h2>Mecánico / Taller</h2>
                <p>
                    Publica tu taller, administra citas y permite
                    que más personas encuentren tu negocio.
                </p>
            </label>

            <!--
                PROPIETARIO + TALLER: se mantiene visualmente pero
                abre el modal informativo (sin enviar el formulario).
                El plan de pago se gestiona en un flujo separado.
            -->
            <label class="card" onclick="abrirModal(); return false;">
                <input type="radio" name="rol" value="3" disabled>
                <div class="icono">
                    <i class="bi bi-stars"></i>
                </div>
                <h2>Propietario + Taller</h2>
                <p>
                    Usa los dos roles al mismo tiempo.
                    Gestiona tus vehículos y también
                    el taller que registres.
                </p>
                <p class="precio">Plan con costo mensual</p>
            </label>

        </div>

        <button type="submit">Finalizar</button>

    </form>

</div><!-- /.contenedor -->

<!-- VENTANA EMERGENTE (sin cambios) -->
<div class="modal" id="modalPlanes">
    <div class="modal-contenido">
        <h2>Plan Propietario + Taller</h2>
        <p>Obtén acceso a los dos roles en una sola cuenta.</p>
        <div class="plan">
            <h3>Plan mensual</h3>
            <div class="valor">$19.900</div>
            <span>Pago mensual</span>
        </div>
        <div class="botones-modal">
            <button class="cerrar" onclick="cerrarModal()">Cancelar</button>
            <button>Elegir plan</button>
        </div>
    </div>
</div>

<?php echo $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rol.js') ?>"></script>
<?= $this->endSection() ?>