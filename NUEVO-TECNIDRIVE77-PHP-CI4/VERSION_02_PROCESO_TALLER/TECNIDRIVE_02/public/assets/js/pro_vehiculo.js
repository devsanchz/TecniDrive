// ══════════════════════════════════════════════════════
// pro_vehiculo.js
// ══════════════════════════════════════════════════════

// ════════════════════════════════════
// 1. REGISTRO DE LICENCIA
// ════════════════════════════════════

// Pasar del panel de bienvenida al formulario
function irAFormulario() {
    document.getElementById('panelInicial').classList.add('hidden');
    document.getElementById('formLicencia').classList.remove('hidden');
}

// Agregar fila de categoría dinámica
let contadorCategorias = 1;

function agregarCategoria() {
    contadorCategorias++;
    const contenedor = document.getElementById('contenedorCategorias');
    const div = document.createElement('div');
    div.className = 'categoria-item';
    div.id = 'categoria-' + contadorCategorias;

    div.innerHTML = `
        <div class="fila-doble">
            <div class="campo-bloque">
                <label>Categoría</label>
                <input type="text" name="categoria[]" placeholder="Ej: B2" maxlength="3" required>
            </div>
            <div class="campo-bloque">
                <label>Fecha vigencia</label>
                <input type="date" name="fecha[]" required>
            </div>
            <button type="button" class="btn-eliminar-fila"
                    onclick="eliminarCategoria(${contadorCategorias})" title="Eliminar fila">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </div>
    `;
    contenedor.appendChild(div);
}

// Eliminar fila de categoría
function eliminarCategoria(id) {
    const item = document.getElementById('categoria-' + id);
    if (item) item.remove();
}

// ════════════════════════════════════
// 2. DASHBOARD (solo si existe en el DOM)
// ════════════════════════════════════

document.addEventListener("DOMContentLoaded", () => {

    // Desplegar info de licencia
    const btnLicencia  = document.querySelector(".btn-desplegar");
    const infoLicencia = document.querySelector(".info-licencia");
    if (btnLicencia && infoLicencia) {
        btnLicencia.addEventListener("click", () => infoLicencia.classList.toggle("hidden"));
    }

    // Desplegar formulario de vehículo
    const btnForm     = document.querySelector(".btn-desplegar-form");
    const formVehiculo = document.querySelector(".formulario-vehiculo");
    if (btnForm && formVehiculo) {
        btnForm.addEventListener("click", () => formVehiculo.classList.toggle("hidden"));
    }

    // Desplegar documentos en tabla (por cada fila)
    document.querySelectorAll(".btn-ver-docs").forEach((btnDocs) => {
        const contenedorDocs = btnDocs.parentElement.querySelector(".contenedor-documentos");
        if (contenedorDocs) {
            btnDocs.addEventListener("click", () => contenedorDocs.classList.toggle("hidden"));
        }
    });

    // Mostrar/ocultar formulario de motivo al desactivar (por cada fila)
    document.querySelectorAll(".btn-rechazar").forEach((btnRechazar) => {
        const formMotivo = btnRechazar.parentElement.querySelector(".form-motivo");
        if (formMotivo) {
            btnRechazar.addEventListener("click", () => formMotivo.classList.toggle("hidden"));
        }
    });
});