// ==========================================
// 1. LÓGICA PARA LOS ICONOS DE OJO (TOOLTIP)
// ==========================================
const iconosOjo = document.querySelectorAll(".icono-ojo");

iconosOjo.forEach(icono => {
    icono.addEventListener("click", (evento) => {
        const tooltipMotivo = evento.target.nextElementSibling;
        if (tooltipMotivo) {
            tooltipMotivo.classList.toggle("activo");
        }
    });
});

// ==========================================
// 2. LÓGICA PARA EL MODAL DEL TALLER
// ==========================================
const botonesDetalles = document.querySelectorAll(".btn-detalles");
const btnCerrar = document.querySelector(".btn-cerrar");
const modalTaller = document.querySelector("#modalTaller");

// Abrir el modal al hacer clic en cualquier botón de detalles
botonesDetalles.forEach(btn => {
    btn.addEventListener("click", () => {
        if (modalTaller) modalTaller.classList.add("active");
    });
});

// Cerrar el modal al hacer clic en la "X"
if (btnCerrar) {
    btnCerrar.addEventListener("click", () => {
        if (modalTaller) modalTaller.classList.remove("active");
    });
}

// Cerrar el modal al hacer clic fuera (en el fondo oscuro)
if (modalTaller) {
    modalTaller.addEventListener("click", (e) => {
        if (e.target === modalTaller) {
            modalTaller.classList.remove("active");
        }
    });
}
