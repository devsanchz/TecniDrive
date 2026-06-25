 // =========================================================
    // SECCIÓN 1: Formulario de Rechazo en Tarjeta (sin cambios)
    // =========================================================
    const boton = document.getElementById("btnMostrar");
    const formulario = document.getElementById("formulario");

    boton.addEventListener("click", () => {
      formulario.classList.toggle("mostrar");
    });
