// =========================================================
// SECCIÓN 1: Toggle formulario de cancelación por cita
// =========================================================
function toggleCancelar(idCita) {
    const form = document.getElementById('formulario-' + idCita);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// =========================================================
// SECCIÓN 2: Toggle del panel "Verificar cita"
// El formulario envía normalmente al servidor (POST real).
// NO se hace preventDefault — el controlador verifica el
// código y redirige con flashdata de éxito o error.
// =========================================================
const btnVerificar  = document.getElementById('btnVerificar');
const formVerificar = document.getElementById('formVerificar');

if (btnVerificar && formVerificar) {
    btnVerificar.addEventListener('click', (e) => {
        e.stopPropagation();
        formVerificar.classList.toggle('mostrar');
    });

    // Cerrar el panel si se hace clic fuera
    document.addEventListener('click', (e) => {
        if (!btnVerificar.contains(e.target) && !formVerificar.contains(e.target)) {
            formVerificar.classList.remove('mostrar');
        }
    });
}

// =========================================================
// SECCIÓN 3: Filtro por estado
// =========================================================
const filtro = document.getElementById('filtroEstado');
if (filtro) {
    filtro.addEventListener('change', function () {
        const valor    = this.value;
        const casillas = document.querySelectorAll('.casilla');

        casillas.forEach(casilla => {
            const estado = casilla.dataset.estado;
            casilla.style.display = (valor === 'todos' || estado === valor) ? '' : 'none';
        });
    });
}