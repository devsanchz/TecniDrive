// =========================================================
// SECCIÓN 1: Toggle del formulario de pre-cierre por cita
// (llamada desde el atributo onclick="toggleCierre(id)" en la vista)
// =========================================================
function toggleCierre(idCita) {
    const form = document.getElementById('formulario-' + idCita);
    if (!form) return;
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// =========================================================
// SECCIÓN 2: Panel "Lista de trabajadores" — abrir/cerrar
// =========================================================
const btnLista    = document.getElementById('btnListaTrabajadores');
const panelTrabaj = document.getElementById('trabajadores');

if (btnLista && panelTrabaj) {
    btnLista.addEventListener('click', (e) => {
        e.stopPropagation();
        panelTrabaj.classList.toggle('mostrar');
    });

    // Cerrar el panel si se hace clic fuera de él
    document.addEventListener('click', (e) => {
        if (!btnLista.contains(e.target) && !panelTrabaj.contains(e.target)) {
            panelTrabaj.classList.remove('mostrar');
        }
    });
}

// =========================================================
// SECCIÓN 3: Formulario "Finalizar cita" (escanear código) — toggle
// Solo aplica si existen estos elementos en la vista.
// =========================================================
const btnVerificar  = document.getElementById('btnVerificar');
const formVerificar = document.getElementById('formVerificar');

if (btnVerificar && formVerificar) {
    btnVerificar.addEventListener('click', (e) => {
        e.stopPropagation();
        formVerificar.classList.toggle('mostrar');
    });

    document.addEventListener('click', (e) => {
        if (!btnVerificar.contains(e.target) && !formVerificar.contains(e.target)) {
            formVerificar.classList.remove('mostrar');
        }
    });
}

// =========================================================
// SECCIÓN 4: Agregar trabajador
// El input ya está siempre visible en el nuevo diseño.
// Solo se valida que no esté vacío antes de permitir el envío.
// El POST real va a mecanico/control/agregar-tecnico y el
// servidor guarda el técnico en la BD; la página se recarga
// mostrando la lista real actualizada.
// =========================================================
const formAgregar = document.getElementById('form-agregar-trabajador');
const inputNombre = document.getElementById('nombre-nuevo-trabajador');

if (formAgregar && inputNombre) {
    formAgregar.addEventListener('submit', (e) => {
        if (inputNombre.value.trim() === '') {
            e.preventDefault();
            inputNombre.focus();
        }
        // Si tiene texto, el form se envía normalmente (POST real)
    });
}

// =========================================================
// SECCIÓN 5: Eliminar trabajador con la X de cada ítem
// Cada <li> contiene su propio <form> que hace POST real a
// mecanico/control/eliminar-tecnico — no se requiere JS para
// que funcione, pero se deja una pequeña animación de salida
// antes de que el navegador navegue al enviar el formulario.
// =========================================================
document.querySelectorAll('.btn-x-eliminar').forEach((btn) => {
    btn.addEventListener('click', (e) => {
        // El form se envía normalmente; solo se da feedback visual rápido.
        const li = btn.closest('li');
        if (li) {
            li.style.transition = 'opacity 0.2s';
            li.style.opacity = '0.4';
        }
        // No se hace preventDefault: el POST debe llegar al servidor.
    });
});