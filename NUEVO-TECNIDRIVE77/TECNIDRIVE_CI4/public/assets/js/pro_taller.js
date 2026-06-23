// Esperamos a que toda la página cargue antes de ejecutar el código
document.addEventListener("DOMContentLoaded", () => {

    // ─────────────────────────────────────────
    // 1. REFERENCIAS A LOS ELEMENTOS DEL HTML
    // ─────────────────────────────────────────

    const card          = document.getElementById("cardTaller");       // La tarjeta del taller
    const header        = document.getElementById("headerTaller");      // Encabezado con la imagen
    const btnCerrar     = document.getElementById("btnCerrar");         // Botón X para cerrar
    const vistaCompacta = document.getElementById("vistaCompacta");     // Resumen inicial
    const contenido     = document.getElementById("contenidoCompleto"); // Detalle completo

    // ─────────────────────────────────────────
    // 2. EXPANDIR / COLAPSAR LA TARJETA
    // ─────────────────────────────────────────

    // Al hacer clic en el encabezado, expandimos la tarjeta
    header.addEventListener("click", (evento) => {
        // Si el clic vino del botón cerrar, no hacemos nada aquí
        if (evento.target.closest("#btnCerrar")) return;

        // Solo expandimos si la tarjeta está en modo compacto
        if (card.classList.contains("compacta")) {
            expandirTarjeta();
        }
    });

    // Al hacer clic en la X, colapsamos la tarjeta
    btnCerrar.addEventListener("click", (evento) => {
        evento.stopPropagation(); // Evita que el clic llegue al header
        colapsarTarjeta();
    });

    // Muestra todo el contenido del taller
    function expandirTarjeta() {
        // Ocultamos el resumen compacto
        vistaCompacta.classList.add("oculto");

        // Cambiamos la tarjeta a modo expandido
        card.classList.replace("compacta", "expandida");

        // Pequeño delay para que la animación de tamaño empiece primero
        setTimeout(() => {
            contenido.classList.add("visible");
        }, 80);
    }

    // Vuelve la tarjeta a su estado inicial compacto
    function colapsarTarjeta() {
        // Ocultamos el contenido completo
        contenido.classList.remove("visible");

        // Volvemos la tarjeta a modo compacto
        card.classList.replace("expandida", "compacta");

        // Mostramos el resumen nuevamente con un pequeño delay
        setTimeout(() => {
            vistaCompacta.classList.remove("oculto");
        }, 200);

        // Subimos la página al inicio de forma suave
        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    // ─────────────────────────────────────────
    // 3. CALIFICACIÓN CON ESTRELLAS
    // ─────────────────────────────────────────

    const estrellas        = document.querySelectorAll(".calificacion-estrellas .star");
    const contenedorEstrellas = document.querySelector(".calificacion-estrellas");
    const bloqueResena     = document.querySelector(".bloque-resena");

    // Pintamos las estrellas de amarillo según el valor hover
    function resaltarEstrellas(hastaValor) {
        estrellas.forEach((estrella) => {
            const valor = parseInt(estrella.getAttribute("data-value"));
            estrella.style.color = valor <= hastaValor ? "#f1c40f" : "";
        });
    }

    // Quitamos el color amarillo de todas las estrellas
    function limpiarEstrellas() {
        estrellas.forEach((estrella) => {
            estrella.style.color = "";
        });
    }

    estrellas.forEach((estrella) => {
        // Al pasar el mouse: resaltamos hasta la estrella actual
        estrella.addEventListener("mouseover", () => {
            const valor = parseInt(estrella.getAttribute("data-value"));
            resaltarEstrellas(valor);
        });

        // Al quitar el mouse sin hacer clic: volvemos al estado original
        estrella.addEventListener("mouseleave", limpiarEstrellas);

        // Al hacer clic: guardamos la puntuación y mostramos el campo de reseña
        estrella.addEventListener("click", () => {
            const puntuacion = estrella.getAttribute("data-value");
            console.log("Puntuación seleccionada:", puntuacion); // Útil para conectar con backend

            contenedorEstrellas.classList.add("oculto-js");
            bloqueResena.classList.remove("oculto-js");
        });
    });

    // ─────────────────────────────────────────
    // 4. SELECCIÓN DE VEHÍCULO EN EL FORMULARIO
    // ─────────────────────────────────────────

    const casillasVehiculo = document.querySelectorAll(".casilla-vehiculo");

    casillasVehiculo.forEach((casilla) => {
        casilla.addEventListener("click", () => {
            // Quitamos el estado activo de todas las casillas
            casillasVehiculo.forEach((c) => c.classList.remove("activo"));

            // Marcamos como activa la casilla seleccionada
            casilla.classList.add("activo");
        });
    });

});