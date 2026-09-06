 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/rol.css') ?>">
<?= $this->endSection() ?>


 <?php echo $this->section('contenido')?> 
<div class="contenedor">
<div class="cabeza">
<a href="<?= site_url('autentificar/registro') ?>" title="Volver" >
<i class="bi bi-chevron-left"></i>
</a>

    <h1>¿Qué rol quieres usar en TecniDrive?</h1>
</div>
    <p class="subtitulo">
        Elige el tipo de cuenta que mejor se adapte a ti
    </p>

    <div class="roles">

       
        <!-- PROPIETARIO -->
        <label class="card">

            <input type="radio" name="rol">

            <div class="icono">
                <i class="bi bi-car-front-fill"></i>
            </div>

            <h2>Propietario</h2>

            <p>
                Gestiona tus vehículos, recuerda documentos importantes,
                busca talleres y agenda citas fácilmente.
            </p>

        </label>

        <!-- MECANICO -->
        <label class="card">

            <input type="radio" name="rol">

            <div class="icono">
                <i class="bi bi-tools"></i>
            </div>

            <h2>Mecánico / Taller</h2>

            <p>
                Publica tu taller, administra citas y permite
                que más personas encuentren tu negocio.
            </p>

        </label>

        <!-- DOS ROLES -->
        <label class="card" onclick="abrirModal()">

            <input type="radio" name="rol">

            <div class="icono">
                <i class="bi bi-stars"></i>
            </div>

            <h2>Propietario + Taller</h2>

            <p>
                Usa los dos roles al mismo tiempo.
                Gestiona tus vehículos y también
                el taller que registres.
            </p>

            <p class="precio">
                Plan con costo mensual
            </p>

        </label>

    </div>

    <button>
        Finalizar
    </button>
</div>
</form>

<!-- VENTANA EMERGENTE -->
<div class="modal" id="modalPlanes">

    <div class="modal-contenido">

        <h2>Plan Propietario + Taller</h2>

        <p>Obtén acceso a los dos roles en una sola cuenta. </p>

        <div class="plan">
            <h3>Plan mensual</h3>
            <div class="valor">
                $19.900
            </div>
            <span>Pago mensual</span>
        </div>

        <div class="botones-modal">
            <button class="cerrar" onclick="cerrarModal()">
                Cancelar
            </button>

            <button>
                Elegir plan
            </button>

        </div>
    </div>
</div>
 <?php echo $this->endSection()?>

 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/rol.js') ?>"></script>
<?= $this->endSection() ?>
