<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Seleccionar Rol</title>
<link rel="stylesheet" href="roles.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="contenedor">

    <h1>¿Qué rol quieres usar en TecniDrive?</h1>

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
        Continuar
    </button>

</div>

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

<script>

function abrirModal(){
    document.getElementById("modalPlanes").style.display = "flex";
}

function cerrarModal(){
    document.getElementById("modalPlanes").style.display = "none";
}

</script>

</body>
</html>