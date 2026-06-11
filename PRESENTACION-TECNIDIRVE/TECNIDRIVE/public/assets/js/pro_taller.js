document.addEventListener("DOMContentLoaded", () => {

    // ─────────────────────────────────────────
    // 1. EXPANDIR / COLAPSAR — múltiples tarjetas
    // ─────────────────────────────────────────

    const backdrop = document.getElementById("modalBackdrop");

    // Función reutilizable para cerrar cualquier tarjeta expandida
    function cerrarTarjeta(card) {
        const vistaCompacta = card.querySelector(".vista-compacta");
        const contenido     = card.querySelector(".contenido-taller");

        contenido.classList.remove("visible");
        card.classList.replace("expandida", "compacta");
        backdrop.classList.remove("activo");
        setTimeout(() => vistaCompacta.classList.remove("oculto"), 200);
    }

    document.querySelectorAll(".card-taller").forEach((card) => {
        const header        = card.querySelector(".header-taller");
        const btnCerrar     = card.querySelector(".btn-cerrar");
        const vistaCompacta = card.querySelector(".vista-compacta");
        const contenido     = card.querySelector(".contenido-taller");

        // Clic en el encabezado → abrir como modal
        header.addEventListener("click", (e) => {
            if (e.target.closest(".btn-cerrar")) return;
            if (card.classList.contains("compacta")) {
                vistaCompacta.classList.add("oculto");
                card.classList.replace("compacta", "expandida");
                backdrop.classList.add("activo");          // mostrar fondo oscuro
                setTimeout(() => contenido.classList.add("visible"), 80);
            }
        });

        // Clic en la X → cerrar modal
        btnCerrar.addEventListener("click", (e) => {
            e.stopPropagation();
            cerrarTarjeta(card);
        });

        // ─────────────────────────────────────────
        // 2. ESTRELLAS Y PUBLICAR CALIFICACIÓN
        // ─────────────────────────────────────────

        const estrellas           = card.querySelectorAll(".calificacion-estrellas .star");
        const contenedorEstrellas = card.querySelector(".calificacion-estrellas");
        const bloqueResena        = card.querySelector(".bloque-resena");

        if (!estrellas.length) return; // ya calificó — no hay estrellas

        let puntuacionElegida = 0;

        // Hover: ilumina hasta la estrella apuntada
        estrellas.forEach((estrella) => {
            estrella.addEventListener("mouseover", () => {
                const val = parseInt(estrella.dataset.value);
                estrellas.forEach(s =>
                    s.style.color = parseInt(s.dataset.value) <= val ? "#f1c40f" : ""
                );
            });

            // Al salir del grupo: mantener el color de la elegida, si hay
            estrella.addEventListener("mouseleave", () => {
                estrellas.forEach(s =>
                    s.style.color = parseInt(s.dataset.value) <= puntuacionElegida ? "#f1c40f" : ""
                );
            });

            // Clic: fijar puntuación y mostrar el formulario de reseña
            estrella.addEventListener("click", () => {
                puntuacionElegida = parseInt(estrella.dataset.value);

                // Guardar en el input oculto para leerlo al publicar
                const inputPuntuacion = card.querySelector(".input-puntuacion");
                if (inputPuntuacion) inputPuntuacion.value = puntuacionElegida;

                // Colorear permanentemente las estrellas elegidas
                estrellas.forEach(s =>
                    s.style.color = parseInt(s.dataset.value) <= puntuacionElegida ? "#f1c40f" : ""
                );

                // Mostrar bloque de reseña sin ocultar las estrellas
                // (el propietario puede cambiar de opinión antes de publicar)
                if (bloqueResena) bloqueResena.classList.remove("oculto-js");
            });
        });

        // Publicar — envía fetch POST
        const btnPublicar = card.querySelector(".btn-guardar-cali");
        if (btnPublicar) {
            btnPublicar.addEventListener("click", async () => {
                const inputPuntuacion = card.querySelector(".input-puntuacion");
                const inputComentario = card.querySelector(".input-comentario");
                const errorMsg        = card.querySelector(".cali-error");
                const idTaller        = contenedorEstrellas?.dataset.tallerId;

                const puntuacion = parseInt(inputPuntuacion?.value ?? "0");
                const comentario = inputComentario?.value.trim() ?? "";

                if (puntuacion < 1) {
                    if (errorMsg) { errorMsg.textContent = "Selecciona al menos 1 estrella."; errorMsg.style.display = "block"; }
                    return;
                }

                btnPublicar.disabled    = true;
                btnPublicar.textContent = "Publicando…";
                if (errorMsg) errorMsg.style.display = "none";

                try {
                    const resp = await fetch(CALIFICAR_URL, {
                        method:  "POST",
                        headers: {
                            "Content-Type":     "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                        },
                        body: JSON.stringify({
                            taller_id:  idTaller,
                            puntuacion: puntuacion,
                            comentario: comentario,
                        }),
                    });

                    const data = await resp.json();

                    if (data.ok) {
                        // Construir y agregar el nuevo bloque de comentario al DOM
                        const listaCalis = card.querySelector(".lista-calificaciones");

                        // Quitar el mensaje "sin calificaciones" si existía
                        const sinCalis = listaCalis?.querySelector(".sin-calis");
                        if (sinCalis) sinCalis.remove();

                        // Estrellas del nuevo comentario en HTML
                        let estrellasHtml = "";
                        for (let i = 1; i <= 5; i++) {
                            const color = i <= data.puntuacion ? "#f1c40f" : "#ddd";
                            estrellasHtml += `<i class="bi bi-star-fill" style="font-size:13px;color:${color};"></i>`;
                        }

                        const nuevoBloque = document.createElement("div");
                        nuevoBloque.className = "bloque-comentario";
                        nuevoBloque.innerHTML = `
                            <div class="usuario-header">
                                <span class="avatar-icono"><i class="bi bi-person-circle"></i></span>
                                <div class="usuario-meta">
                                    <div class="nombre-estrellas-fila">
                                        <span class="nombre-usuario">${data.nombre}</span>
                                        <span class="estrellas-grupo">${estrellasHtml}</span>
                                    </div>
                                    <span class="fecha-comentario">Hoy</span>
                                </div>
                            </div>
                            ${data.comentario ? `<p class="comentario-texto">${data.comentario}</p>` : ""}
                        `;

                        if (listaCalis) listaCalis.prepend(nuevoBloque);

                        // Actualizar el promedio en el encabezado
                        const seccion = card.querySelector(".seccion-detalle[data-taller-id]");
                        let badgeP = seccion?.querySelector(".badge-promedio");
                        if (!badgeP && seccion) {
                            // Crear badge si no existía (primer voto)
                            badgeP = document.createElement("span");
                            badgeP.className = "badge-promedio";
                            seccion.querySelector(".subtitulo-taller").appendChild(badgeP);
                        }
                        if (badgeP) {
                            badgeP.innerHTML = `${data.promedio} <i class="bi bi-star-fill" style="color:#f1c40f;font-size:13px;"></i>
                                &nbsp;<small style="font-weight:400;font-size:13px;color:#888;">(${data.total})</small>`;
                        }

                        // Reemplazar el formulario por el mensaje "ya calificaste"
                        const tuCali = card.querySelector(".tu-calificacion");
                        if (tuCali) {
                            tuCali.innerHTML = `<p class="ya-califico-msg">
                                <i class="bi bi-check-circle-fill" style="color:#28a745;"></i>
                                Ya calificaste este taller.
                            </p>`;
                        }

                    } else {
                        if (errorMsg) { errorMsg.textContent = data.error ?? "Error al publicar."; errorMsg.style.display = "block"; }
                        btnPublicar.disabled    = false;
                        btnPublicar.textContent = "Publicar";
                    }

                } catch (e) {
                    if (errorMsg) { errorMsg.textContent = "No se pudo conectar con el servidor."; errorMsg.style.display = "block"; }
                    btnPublicar.disabled    = false;
                    btnPublicar.textContent = "Publicar";
                }
            });
        }
    });

    // Clic en el backdrop → cerrar la tarjeta abierta
    backdrop.addEventListener("click", () => {
        const abierta = document.querySelector(".card-taller.expandida");
        if (abierta) cerrarTarjeta(abierta);
    });

    // ─────────────────────────────────────────
    // 3. BUSCADOR Y FILTRO DE ESPECIALIDAD
    // ─────────────────────────────────────────

    const buscador = document.querySelector(".buscador-wrapper input");
    const filtro   = document.getElementById("filtroEspecialidad");
    const tarjetas = document.querySelectorAll(".card-taller");

    function filtrarTarjetas() {
        const texto = buscador ? buscador.value.toLowerCase() : "";
        const esp   = filtro   ? filtro.value                  : "todos";

        tarjetas.forEach((card) => {
            const nombre    = card.dataset.nombre    ?? "";
            const direccion = card.dataset.direccion ?? "";
            const especialidad = card.dataset.especialidad ?? "";

            const coincideTexto = texto === "" ||
                nombre.includes(texto) ||
                direccion.includes(texto) ||
                especialidad.toLowerCase().includes(texto);

            const coincideEsp = esp === "todos" ||
                especialidad === esp;

            card.style.display = (coincideTexto && coincideEsp) ? "" : "none";
        });
    }

    if (buscador) buscador.addEventListener("input",  filtrarTarjetas);
    if (filtro)   filtro.addEventListener("change", filtrarTarjetas);

});