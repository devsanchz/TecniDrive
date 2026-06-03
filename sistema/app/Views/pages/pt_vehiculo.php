<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>propietario_vehiculo</title>
    <link rel="stylesheet" href="pt_vehiculos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .oculto { display: none !important; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <ul>
            <li><a href=""><span class="icon"><i class="bi bi-list"></i></span><span class="text">Panel de usuario</span></a></li>
            <li><a href="panel_propietario.html"><span class="icon"><i class="bi bi-file-person-fill"></i></span><span class="text">Mi perfil</span></a></li>
            <li><a href="pt_vehiculo.html"><span class="icon"><i class="bi bi-car-front-fill"></i></span><span class="text">Vehículos</span></a></li>
            <li><a href="pt_taller.html"><span class="icon"><i class="bi bi-tools"></i></span><span class="text">Talleres</span></a></li>
            <li><a href="pt_citas.html"><span class="icon"><i class="bi bi-calendar2-plus"></i></span><span class="text">Cita Agendada</span></a></li>
            <li><a href="pt_calificacion.html"><span class="icon"><i class="bi bi-star-half"></i></span><span class="text">Mis puntuaciones</span></a></li>
            <li><a href="pt_notificaciones.html"><span class="icon"><i class="bi bi-bell-fill"></i></span><span class="text">Notificaciones</span></a></li>
            <li><a href="index.html"><span class="icon"><i class="bi bi-box-arrow-left"></i></span><span class="text">Cerrar sesión</span></a></li>
        </ul>
    </div>

    <!-- TÍTULOS -->
    <div class="titulos">
        <h1>Tus vehículos</h1>
        <h5>Registra tu vehículo y te ayudamos con recordatorios de papeles y mantenimiento</h5>
    </div>

    <!-- PASO 1: visible por defecto -->
    <div class="agregar mostrar" id="panelInicial">
        <h1>Aún no tienes licencia o vehículos registrados</h1>
        <small>Comienza registrando tu licencia</small>
        <br>
        <button onclick="mostrarFormularioLicencia()">Ingresar datos</button>
    </div>

    <!-- PASO -->
    <form id="formLicencia" class="formulario oculto">
        <h2>Registro de Licencia</h2>
        <div id="contenedorCategorias">
            <div class="categoria-item" id="categoria-1">
                <div class="fila-inputs">
                    <div class="campo">
                        <label>Numero de licencia</label>
                        <input type="text" name="nlicencia" placeholder="LC..."  maxlength="11" required>
                        <label>Categoría:</label>
                        <input type="text" name="categoria[]" placeholder="EJ: A1, B2, C1" maxlength="3" required>
                    </div>
                    <div class="campo">
                        <label>Fecha vigencia:</label>
                        <input type="date" name="fecha[]" required>
                    </div>
                    <button type="button" class="btn-eliminar" onclick="eliminarCategoria(1)" style="visibility: hidden;">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>
            </div>
        </div>
        <button type="button" class="btn-agregar" onclick="agregarCategoria()">+ Agregar categoría</button>
        <!-- PASO 2 a 3 -->
        <button type="submit" class="btn-enviar" onclick="mostrarSeccionVehiculos()">Datos listo</button>
    </form>

    <!-- PASO 3, 4 y 5 -->
    <section class="vehiculos oculto" id="seccionVehiculos">

        <div class="lice">
            <p>Licencia Registrada</p>
            <button class="actu" onclick="mostrarFormularioLicencia()">Actualizar</button>
            <!-- PASO 3 a 4 -->
            <button class="agre" onclick="mostrarFormularioVehiculo()">+ Agregar vehículo</button>
        </div>

        <!-- PASO 4-->
        <form class="formulario2 oculto" id="formulario2">
            <h2>Registro de Vehículo</h2>
            <input type="text" name="placa" placeholder="Placa" maxlength="6" required>
            <input type="text" name="marca" placeholder="Marca" required>
            <input type="text" name="modelo" placeholder="Modelo" required>
            <input type="text" name="ano" placeholder="Año" maxlength="4">
            <select name="tipo" required>
                <option value="">Tipo de vehículo</option>
                <option value="carro">Carro</option>
                <option value="moto">Moto</option>
            </select>
            
            <input type="text" placeholder="Servicio(Particular o publico)" required>
            <input type="number" placeholder="Kilometraje actual" required>
            <input type="text" placeholder="Chasis o VIN del vehículo" maxlength="17" required>
            <!-- PASO 4 a 5 -->
            <button type="sutmit" class="btn-agregar" onclick="mostrarTablaVehiculo()">Registrar Vehículo</button>
        </form>

        <!-- PASO 5-->
        <div class="tabla oculto" id="tablaVehiculo">
            <div class="iz">
                <div class="cuadro-iz">
                    <h3>Placa</h3>
                    <div class="valor">AJK203</div>
                </div>
                 <div class="cuadro-iz">
                    <h3>Servicio</h3>
                    <div class="valor">Particular</div>
                </div>
                <div class="cuadro-iz">
                    <h3>Kilometraje</h3>
                    <div class="valor">120,000 Km</div>
                </div>
                <div class="cuadro-iz">
                    <h3>Chasis</h3>
                    <div class="valor">JT2BG22KX12045678</div>
                </div>
                 
            </div>

            <div class="der">
                <h1>Toyota Corolla 2022 <span>Activo</span></h1>

                <div class="doc">
                    <h2>Documentos Vehiculares</h2>
                    <div class="cuadro">
                        <p>SOAT</p>
                        <button type="button" class="btn-registrar" onclick="toggleForm(this)">Registrar</button>
                        <form>
                            <small>F.Expedición</small>
                            <input type="date" required>
                            <small>F.Vigencia</small>
                            <input type="date" required>
                            <button type="submit">Guardar</button>
                        </form>
                    </div>
                    <div class="cuadro">
                        <p>Tecnomecánica</p>
                        <button type="button" class="btn-registrar" onclick="toggleForm(this)">Registrar</button>
                        <form>
                            <small>F.Expedición</small>
                            <input type="date" required>
                            <small>F.Vigencia</small>
                            <input type="date" required>
                            <button type="submit">Guardar</button>
                        </form>
                    </div>
                </div>

                <div class="estado">
                    <h2>Cambiar estado del vehículo</h2>
                    <button class="Activo">Activar</button>
                    <button class="inactivo" onclick="toggleEstadoForm()">Desactivar</button>
                    <form>
                        <label>Motivo:</label>
                        <input type="text" placeholder="Razón..." maxlength="100">
                        <button type="submit">Confirmar</button>
                    </form>
                </div>
            </div>
        </div>

    </section>

</body>
<script>
    let contadorCategorias = 1;

    /* PASO 1 → 2 */
    function mostrarFormularioLicencia() {
        ocultarElemento('panelInicial');
        ocultarElemento('seccionVehiculos');
        mostrarElemento('formLicencia');
    }

    /* PASO 3 → 4 */
    function mostrarFormularioVehiculo() {
        mostrarElemento('formulario2');
        ocultarElemento('tablaVehiculo');
    }

    /* Utilidades */
    function mostrarElemento(id) {
        document.getElementById(id).classList.remove('oculto');
    }
    function ocultarElemento(id) {
        document.getElementById(id).classList.add('oculto');
    }

    /* El navegador valida con required; si pasa, el submit avanza al siguiente paso */
    document.getElementById('formLicencia').addEventListener('submit', function(e) {
        e.preventDefault();
        ocultarElemento('formLicencia');
        mostrarElemento('seccionVehiculos');
        ocultarElemento('formulario2');
        ocultarElemento('tablaVehiculo');
    });

    document.getElementById('formulario2').addEventListener('submit', function(e) {
        e.preventDefault();
        ocultarElemento('formulario2');
        mostrarElemento('tablaVehiculo');
    });

    /* Categorías dinámicas de licencia */
    function agregarCategoria() {
        contadorCategorias++;
        const contenedor = document.getElementById('contenedorCategorias');
        const div = document.createElement('div');
        div.className = 'categoria-item';
        div.id = 'categoria-' + contadorCategorias;
        div.innerHTML = `
            <div class="fila-inputs">
                <div class="campo">
                    <label>Categoría:</label>
                    <input type="text" name="categoria[]" placeholder="EJ: A1, B2, C1" maxlength="3" required>
                </div>
                <div class="campo">
                    <label>Fecha vigencia:</label>
                    <input type="date" name="fecha[]" required>
                </div>
                <button type="button" class="btn-eliminar" onclick="eliminarCategoria(${contadorCategorias})">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>`;
        contenedor.appendChild(div);
    }

    function eliminarCategoria(id) {
        const item = document.getElementById('categoria-' + id);
        if (item) item.remove();
    }

    /* Toggle formularios inline */
    function toggleForm(btn) {
        const form = btn.nextElementSibling;
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }

    function toggleEstadoForm() {
        const form = document.querySelector('.estado form');
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
    }
</script>
</html>
