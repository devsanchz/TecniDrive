document.addEventListener("DOMContentLoaded", () => {

    // =========================================================================
    // CSRF — helper reutilizable para todos los fetch() de este archivo
    // =========================================================================
    function obtenerCsrf() {
        const metaName = document.querySelector('meta[name="csrf-name"]');
        const metaHash = document.querySelector('meta[name="csrf-hash"]');

        return {
            nombre: metaName ? metaName.content : null,
            valor:  metaHash ? metaHash.content : null,
        };
    }

    // Rota el hash en el HTML tras cada respuesta exitosa (CI4 regenera el
    // token en modo csrfProtection = 'cookie'); si no se actualiza, la
    // SIGUIENTE petición fetch en la misma carga de página fallará con el
    // mismo 403 aunque la primera haya funcionado.
    function actualizarCsrf(json) {
        if (json && json.csrf_token_value) {
            const metaHash = document.querySelector('meta[name="csrf-hash"]');
            if (metaHash) metaHash.content = json.csrf_token_value;
        }
    }

    // Envoltorio único: todo fetch POST JSON de este archivo pasa por aquí
    // para garantizar que siempre lleve el header X-CSRF-TOKEN y las
    // credenciales (cookie CSRF) requeridas por CodeIgniter.
    async function postJSON(url, payload) {
        const csrf = obtenerCsrf();

        const resp = await fetch(url, {
            method:      "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type":     "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN":     csrf.valor,
            },
            body: JSON.stringify(payload),
        });

        const json = await resp.json();
        actualizarCsrf(json);
        return json;
    }

    const backdrop = document.getElementById("modalBackdrop");

    // ── Cerrar tarjeta ────────────────────────────────────────────────────
    function cerrarTarjeta(card) {
        const vistaCompacta = card.querySelector(".vista-compacta");
        const contenido     = card.querySelector(".contenido-taller");
        contenido.classList.remove("visible");
        card.classList.replace("expandida", "compacta");
        backdrop.classList.remove("activo");
        setTimeout(() => vistaCompacta.classList.remove("oculto"), 200);
    }

    // ── Recalcular y actualizar el badge de promedio en la tarjeta ────────
    function actualizarBadgePromedio(card, promedio, total) {
        const h2    = card.querySelector(".subtitulo-taller.arriba");
        let badge   = h2?.querySelector(".badge-promedio");

        if (promedio > 0) {
            if (! badge) {
                badge = document.createElement("span");
                badge.className = "badge-promedio";
                h2.insertBefore(badge, h2.querySelector(".btn-group"));
            }
            badge.innerHTML = `${promedio} <i class="bi bi-star-fill" style="color:#f1c40f;font-size:13px;"></i>
                &nbsp;<small style="font-weight:400;font-size:13px;color:#888;">(${total})</small>`;
        } else if (badge) {
            badge.remove();
        }
    }

    // ── Construir HTML de un bloque de comentario ─────────────────────────
    function construirBloque(nombre, avatarcolor, puntuacion, comentario, esMio) {
        let estrellasHtml = "";
        for (let i = 1; i <= 5; i++) {
            const color = i <= puntuacion ? "#f1c40f" : "#ddd";
            estrellasHtml += `<i class="bi bi-star-fill" style="font-size:13px;color:${color};"></i>`;
        }
        return `
            <div class="usuario-header">
                <span class="avatar-icono" style="color:${avatarcolor};">
                    <i class="bi bi-person-check"></i>
                </span>
                <div class="usuario-meta">
                    <div class="nombre-estrellas-fila">
                        <span class="nombre-usuario">${nombre}</span>
                        <span class="estrellas-grupo">${estrellasHtml}</span>
                    </div>
                    <span class="fecha-comentario">Hoy</span>
                </div>
            </div>
            ${comentario ? `<p class="comentario-texto">${comentario}</p>` : ""}
        `;
    }

    // =========================================================================
    // TARJETAS — expandir / colapsar / estrellas / publicar / actualizar / eliminar
    // =========================================================================

    document.querySelectorAll(".card-taller").forEach((card) => {
        const header        = card.querySelector(".header-taller");
        const btnCerrar     = card.querySelector(".btn-cerrar");
        const vistaCompacta = card.querySelector(".vista-compacta");
        const contenido     = card.querySelector(".contenido-taller");

        // Expandir
        header.addEventListener("click", (e) => {
            if (e.target.closest(".btn-cerrar")) return;
            if (card.classList.contains("compacta")) {
                vistaCompacta.classList.add("oculto");
                card.classList.replace("compacta", "expandida");
                backdrop.classList.add("activo");
                setTimeout(() => contenido.classList.add("visible"), 80);
            }
        });

        // Cerrar con X
        btnCerrar.addEventListener("click", (e) => {
            e.stopPropagation();
            cerrarTarjeta(card);
        });

        // ── Lógica de estrellas (interactiva) ─────────────────────────────
        function iniciarEstrellas(contenedor) {
            const estrellas     = contenedor.querySelectorAll(".star");
            const inputPunt     = contenedor.closest(".tu-calificacion, .form-edicion")
                                            ?.querySelector(".input-puntuacion");
            let puntuacionActual = inputPunt ? parseInt(inputPunt.value) : 0;

            estrellas.forEach(s => {
                s.addEventListener("mouseover", () => {
                    const val = parseInt(s.dataset.value);
                    estrellas.forEach(e =>
                        e.style.color = parseInt(e.dataset.value) <= val ? "#f1c40f" : "");
                });

                s.addEventListener("mouseleave", () => {
                    estrellas.forEach(e =>
                        e.style.color = parseInt(e.dataset.value) <= puntuacionActual ? "#f1c40f" : "");
                });

                s.addEventListener("click", () => {
                    puntuacionActual = parseInt(s.dataset.value);
                    if (inputPunt) inputPunt.value = puntuacionActual;

                    estrellas.forEach(e =>
                        e.style.color = parseInt(e.dataset.value) <= puntuacionActual ? "#f1c40f" : "");

                    // Mostrar bloque de reseña si estaba oculto
                    const bloque = contenedor.closest(".tu-calificacion, .form-edicion")
                                             ?.querySelector(".bloque-resena");
                    if (bloque) bloque.classList.remove("oculto-js");
                });
            });
        }

        // Iniciar estrellas en el formulario nuevo (si existe)
        const estrellasNuevo = card.querySelector(".tu-calificacion .calificacion-estrellas");
        if (estrellasNuevo) iniciarEstrellas(estrellasNuevo);

        // Iniciar estrellas en el formulario de edición (si existe)
        const estrellasEdicion = card.querySelector(".form-edicion .calificacion-estrellas");
        if (estrellasEdicion) iniciarEstrellas(estrellasEdicion);

        // ── PUBLICAR (crear nueva) ─────────────────────────────────────────
        function activarPublicarNueva(card, btnPublicar, idTaller) {
            if (! btnPublicar) return;
            btnPublicar.addEventListener("click", async () => {
                const inputPunt  = card.querySelector(".tu-calificacion .input-puntuacion");
                const inputCom   = card.querySelector(".tu-calificacion .input-comentario");
                const errorMsg   = card.querySelector(".tu-calificacion .cali-error");
                idTaller = idTaller ?? card.querySelector(".tu-calificacion .calificacion-estrellas")
                                       ?.dataset.tallerId;

                const puntuacion = parseInt(inputPunt?.value ?? "0");
                const comentario = inputCom?.value.trim() ?? "";

                if (puntuacion < 1) {
                    if (errorMsg) { errorMsg.textContent = "Selecciona al menos 1 estrella."; errorMsg.style.display = "block"; }
                    return;
                }

                btnPublicar.disabled    = true;
                btnPublicar.textContent = "Publicando…";
                if (errorMsg) errorMsg.style.display = "none";

                try {
                    const data = await postJSON(CALIFICAR_URL, { taller_id: idTaller, puntuacion, comentario });

                    if (data.ok) {
                        // Marcar la tarjeta como calificada para el filtro "Mis puntuaciones"
                        card.dataset.calificado = "1";

                        if (data.estado === "pendiente") {
                            // No se agrega a la lista pública; solo mostramos aviso
                            const tuCali = card.querySelector(".tu-calificacion");
                            if (tuCali) {
                                tuCali.innerHTML = `
                                    <p class="cali-pendiente-msg" style="background:#fff3cd;color:#856404;padding:8px 12px;border-radius:5px;font-size:13px;">
                                        Tu calificación con comentario está en proceso de aprobación por el administrador.
                                    </p>
                                    <div class="form-edicion oculto-js">
                                        <h5>Edita tu calificación</h5>
                                        <div class="calificacion-estrellas" data-taller-id="${idTaller}">
                                            ${[1,2,3,4,5].map(s =>
                                                `<i class="bi bi-star-fill star" data-value="${s}"
                                                    style="color:${s <= data.puntuacion ? '#f1c40f' : ''}"></i>`
                                            ).join('')}
                                        </div>
                                        <div class="bloque-resena">
                                            <input type="hidden" class="input-puntuacion" value="${data.puntuacion}">
                                            <input type="text" class="input-comentario"
                                                   placeholder="Tu reseña (opcional)"
                                                   value="${data.comentario}">
                                            <button class="btn-guardar-cali" type="button">Publicar</button>
                                            <p class="cali-error" style="display:none;color:#dc3545;font-size:13px;"></p>
                                        </div>
                                    </div>`;
                                const nuevasEstrellas = tuCali.querySelector(".calificacion-estrellas");
                                if (nuevasEstrellas) iniciarEstrellas(nuevasEstrellas);
                                activarPublicarEdicion(card, tuCali.querySelector(".btn-guardar-cali"), idTaller);
                            }

                            // Mostrar botones actualizar/eliminar
                            const h2 = card.querySelector(".subtitulo-taller.arriba");
                            if (h2 && ! h2.querySelector(".btn-group")) {
                                const grupo = document.createElement("div");
                                grupo.className = "btn-group";
                                grupo.innerHTML = `
                                    <button class="btn-accion actualizar"
                                            data-taller-id="${idTaller}"
                                            data-puntuacion="${data.puntuacion}"
                                            data-comentario="${data.comentario}">Actualizar</button>
                                    <button class="btn-accion eliminar"
                                            data-taller-id="${idTaller}">Eliminar</button>`;
                                h2.appendChild(grupo);
                                activarBotonesGestion(card, grupo);
                            }

                            return;
                        }

                        // Agregar bloque al DOM (solo si quedó aprobada)
                        const listaCalis = card.querySelector(".lista-calificaciones");
                        listaCalis?.querySelector(".sin-calis")?.remove();

                        const div = document.createElement("div");
                        div.className = "bloque-comentario";
                        div.dataset.propietarioId = PROPIETARIO_ID;
                        div.innerHTML = construirBloque(data.nombre, data.avatarcolor,
                                                        data.puntuacion, data.comentario, true);
                        listaCalis?.prepend(div);

                        actualizarBadgePromedio(card, data.promedio, data.total);

                        // Mostrar botones actualizar/eliminar
                        const h2 = card.querySelector(".subtitulo-taller.arriba");
                        if (h2 && ! h2.querySelector(".btn-group")) {
                            const grupo = document.createElement("div");
                            grupo.className = "btn-group";
                            grupo.innerHTML = `
                                <button class="btn-accion actualizar"
                                        data-taller-id="${idTaller}"
                                        data-puntuacion="${data.puntuacion}"
                                        data-comentario="${data.comentario}">Actualizar</button>
                                <button class="btn-accion eliminar"
                                        data-taller-id="${idTaller}">Eliminar</button>`;
                            h2.appendChild(grupo);
                            // Activar listeners para los nuevos botones
                            activarBotonesGestion(card, grupo);
                        }

                        // Reemplazar formulario por formulario de edición
                        const tuCali = card.querySelector(".tu-calificacion");
                        if (tuCali) {
                            tuCali.innerHTML = `
                                <div class="form-edicion oculto-js">
                                    <h5>Edita tu calificación</h5>
                                    <div class="calificacion-estrellas"
                                         data-taller-id="${idTaller}">
                                        ${[1,2,3,4,5].map(s =>
                                            `<i class="bi bi-star-fill star" data-value="${s}"
                                                style="color:${s <= data.puntuacion ? '#f1c40f' : ''}"></i>`
                                        ).join('')}
                                    </div>
                                    <div class="bloque-resena">
                                        <input type="hidden" class="input-puntuacion" value="${data.puntuacion}">
                                        <input type="text" class="input-comentario"
                                               placeholder="Tu reseña (opcional)"
                                               value="${data.comentario}">
                                        <button class="btn-guardar-cali" type="button">Publicar</button>
                                        <p class="cali-error" style="display:none;color:#dc3545;font-size:13px;"></p>
                                    </div>
                                </div>`;
                            // Activar estrellas del nuevo formulario de edición
                            const nuevasEstrellas = tuCali.querySelector(".calificacion-estrellas");
                            if (nuevasEstrellas) iniciarEstrellas(nuevasEstrellas);
                            // Activar botón publicar del formulario de edición
                            activarPublicarEdicion(card, tuCali.querySelector(".btn-guardar-cali"), idTaller);
                        }

                    } else {
                        if (errorMsg) { errorMsg.textContent = data.error ?? "Error al publicar."; errorMsg.style.display = "block"; }
                        btnPublicar.disabled    = false;
                        btnPublicar.textContent = "Publicar";
                    }
                } catch {
                    if (errorMsg) { errorMsg.textContent = "No se pudo conectar."; errorMsg.style.display = "block"; }
                    btnPublicar.disabled    = false;
                    btnPublicar.textContent = "Publicar";
                }
            });
        }

        // Activar el botón Publicar ya renderizado por PHP al cargar la página
        const btnPublicarInicial = card.querySelector(".tu-calificacion .btn-guardar-cali");
        activarPublicarNueva(card, btnPublicarInicial);

        // ── Activar botón Publicar del formulario de edición ──────────────
        function activarPublicarEdicion(card, btnGuardar, idTaller) {
            if (! btnGuardar) return;
            btnGuardar.addEventListener("click", async () => {
                const formEdicion = card.querySelector(".form-edicion");
                const inputPunt   = formEdicion?.querySelector(".input-puntuacion");
                const inputCom    = formEdicion?.querySelector(".input-comentario");
                const errorMsg    = formEdicion?.querySelector(".cali-error");

                const puntuacion = parseInt(inputPunt?.value ?? "0");
                const comentario = inputCom?.value.trim() ?? "";

                if (puntuacion < 1) {
                    if (errorMsg) { errorMsg.textContent = "Selecciona al menos 1 estrella."; errorMsg.style.display = "block"; }
                    return;
                }

                btnGuardar.disabled    = true;
                btnGuardar.textContent = "Guardando…";
                if (errorMsg) errorMsg.style.display = "none";

                try {
                    const data = await postJSON(ACTUALIZAR_URL, { taller_id: idTaller, puntuacion, comentario });

                    if (data.ok) {
                        if (data.estado === "pendiente") {
                            // Quitar el bloque de la lista pública si existía
                            card.querySelector(`.bloque-comentario[data-propietario-id="${PROPIETARIO_ID}"]`)?.remove();

                            const lista = card.querySelector(".lista-calificaciones");
                            if (lista && lista.querySelectorAll(".bloque-comentario").length === 0) {
                                lista.innerHTML = `<p class="sin-calis">Aún no hay calificaciones. ¡Sé el primero!</p>`;
                            }

                            // Mostrar aviso de pendiente (y quitar el de rechazada si quedaba,
                            // ej. en el ciclo Publicar -> Actualizar -> Guardar tras un rechazo)
                            const tuCali = card.querySelector(".tu-calificacion");
                            tuCali?.querySelector(".cali-rechazada-msg")?.remove();
                            let aviso = tuCali?.querySelector(".cali-pendiente-msg");
                            if (tuCali && ! aviso) {
                                aviso = document.createElement("p");
                                aviso.className = "cali-pendiente-msg";
                                aviso.style = "background:#fff3cd;color:#856404;padding:8px 12px;border-radius:5px;font-size:13px;";
                                tuCali.insertBefore(aviso, tuCali.firstChild);
                            }
                            if (aviso) aviso.textContent = "Tu calificación con comentario está en proceso de aprobación por el administrador.";

                            actualizarBadgePromedio(card, data.promedio, data.total);

                            // Asegurar que exista el grupo de botones Actualizar/Eliminar.
                            // Si venía de un rechazo, este grupo nunca se había creado en
                            // este punto del DOM, así que hay que crearlo, no solo
                            // actualizar sus data-* como si ya existiera.
                            const h2 = card.querySelector(".subtitulo-taller.arriba");
                            let btnAct = card.querySelector(".btn-group .actualizar");
                            if (btnAct) {
                                btnAct.dataset.puntuacion = data.puntuacion;
                                btnAct.dataset.comentario = data.comentario;
                            } else if (h2 && ! h2.querySelector(".btn-group")) {
                                const grupo = document.createElement("div");
                                grupo.className = "btn-group";
                                grupo.innerHTML = `
                                    <button class="btn-accion actualizar"
                                            data-taller-id="${idTaller}"
                                            data-puntuacion="${data.puntuacion}"
                                            data-comentario="${data.comentario}">Actualizar</button>
                                    <button class="btn-accion eliminar"
                                            data-taller-id="${idTaller}">Eliminar</button>`;
                                h2.appendChild(grupo);
                                activarBotonesGestion(card, grupo);
                            }

                            formEdicion?.classList.add("oculto-js");
                            btnGuardar.disabled    = false;
                            btnGuardar.textContent = "Publicar";
                            return;
                        }

                        // Quitar aviso de pendiente/rechazada si ya fue aprobada
                        card.querySelector(".tu-calificacion .cali-pendiente-msg")?.remove();
                        card.querySelector(".tu-calificacion .cali-rechazada-msg")?.remove();

                        // Actualizar el bloque del propietario en la lista
                        const bloqueMio = card.querySelector(`.bloque-comentario[data-propietario-id="${PROPIETARIO_ID}"]`);
                        if (bloqueMio) {
                            // Actualizar estrellas dentro del bloque
                            bloqueMio.querySelectorAll(".estrellas-grupo .bi-star-fill").forEach((star, idx) => {
                                star.style.color = (idx + 1) <= data.puntuacion ? "#f1c40f" : "#ddd";
                            });
                            // Actualizar o agregar comentario
                            let pCom = bloqueMio.querySelector(".comentario-texto");
                            if (data.comentario) {
                                if (pCom) pCom.textContent = data.comentario;
                                else {
                                    pCom = document.createElement("p");
                                    pCom.className = "comentario-texto";
                                    pCom.textContent = data.comentario;
                                    bloqueMio.appendChild(pCom);
                                }
                            } else if (pCom) {
                                pCom.remove();
                            }
                        }

                        actualizarBadgePromedio(card, data.promedio, data.total);

                        // Actualizar data-* en botones de gestión
                        const btnAct = card.querySelector(".btn-group .actualizar");
                        if (btnAct) {
                            btnAct.dataset.puntuacion = data.puntuacion;
                            btnAct.dataset.comentario = data.comentario;
                        }

                        // Ocultar formulario de edición
                        formEdicion?.classList.add("oculto-js");
                        btnGuardar.disabled    = false;
                        btnGuardar.textContent = "Publicar";

                    } else {
                        if (errorMsg) { errorMsg.textContent = data.error ?? "Error al actualizar."; errorMsg.style.display = "block"; }
                        btnGuardar.disabled    = false;
                        btnGuardar.textContent = "Publicar";
                    }
                } catch {
                    if (errorMsg) { errorMsg.textContent = "No se pudo conectar."; errorMsg.style.display = "block"; }
                    btnGuardar.disabled    = false;
                    btnGuardar.textContent = "Publicar";
                }
            });
        }

        // ── Activar botones Actualizar y Eliminar ─────────────────────────
        function activarBotonesGestion(card, grupo) {
            const btnAct = grupo.querySelector(".actualizar");
            const btnElm = grupo.querySelector(".eliminar");

            // ACTUALIZAR: mostrar formulario de edición precargado
            if (btnAct) {
                btnAct.addEventListener("click", () => {
                    const formEdicion = card.querySelector(".form-edicion");
                    if (! formEdicion) return;

                    const puntuacion = parseInt(btnAct.dataset.puntuacion ?? "0");
                    const comentario = btnAct.dataset.comentario ?? "";

                    // Precargar valores actuales en el formulario
                    const inputPunt = formEdicion.querySelector(".input-puntuacion");
                    const inputCom  = formEdicion.querySelector(".input-comentario");
                    if (inputPunt) inputPunt.value = puntuacion;
                    if (inputCom)  inputCom.value  = comentario;

                    // Colorear estrellas según puntuación actual
                    formEdicion.querySelectorAll(".calificacion-estrellas .star").forEach(s => {
                        s.style.color = parseInt(s.dataset.value) <= puntuacion ? "#f1c40f" : "";
                    });

                    formEdicion.classList.toggle("oculto-js");
                });

                // Activar botón Publicar del form de edición que ya existe en el DOM
                const btnGuardar = card.querySelector(".form-edicion .btn-guardar-cali");
                activarPublicarEdicion(card, btnGuardar, btnAct.dataset.tallerId);
            }

            // ELIMINAR: confirm y fetch
            if (btnElm) {
                btnElm.addEventListener("click", async () => {
                    if (! confirm("¿Seguro que quieres eliminar tu calificación?")) return;

                    const idTaller = btnElm.dataset.tallerId;
                    btnElm.disabled    = true;
                    btnElm.textContent = "Eliminando…";

                    try {
                        const data = await postJSON(ELIMINAR_URL, { taller_id: idTaller });

                        if (data.ok) {
                            // Desmarcar la tarjeta como calificada para el filtro "Mis puntuaciones"
                            card.dataset.calificado = "0";

                            // Eliminar el bloque del propietario del DOM
                            card.querySelector(`.bloque-comentario[data-propietario-id="${PROPIETARIO_ID}"]`)?.remove();

                            // Si no quedan calificaciones mostrar mensaje vacío
                            const lista = card.querySelector(".lista-calificaciones");
                            if (lista && lista.querySelectorAll(".bloque-comentario").length === 0) {
                                lista.innerHTML = `<p class="sin-calis">Aún no hay calificaciones. ¡Sé el primero!</p>`;
                            }

                            actualizarBadgePromedio(card, data.promedio, data.total);

                            // Quitar los botones de gestión
                            grupo.remove();

                            // Mostrar formulario nuevo para volver a calificar
                            const tuCali = card.querySelector(".tu-calificacion");
                            if (tuCali) {
                                const estrellasNuevas = card.querySelector(".form-edicion .calificacion-estrellas");
                                const idT = estrellasNuevas?.dataset.tallerId ?? idTaller;
                                tuCali.innerHTML = `
                                    <h5>Califica este Taller</h5>
                                    <small>Comparte tu opinión con los demás</small>
                                    <div class="calificacion-estrellas" data-taller-id="${idT}">
                                        ${[1,2,3,4,5].map(s =>
                                            `<i class="bi bi-star-fill star" data-value="${s}"></i>`
                                        ).join('')}
                                    </div>
                                    <div class="bloque-resena oculto-js">
                                        <input type="hidden" class="input-puntuacion" value="0">
                                        <input type="text" class="input-comentario"
                                               placeholder="Tu reseña (opcional)">
                                        <button class="btn-guardar-cali" type="button">Publicar</button>
                                        <p class="cali-error" style="display:none;color:#dc3545;font-size:13px;"></p>
                                    </div>`;

                                // Reactivar estrellas y botón publicar del formulario nuevo
                                const nuevasEstrellas = tuCali.querySelector(".calificacion-estrellas");
                                if (nuevasEstrellas) iniciarEstrellas(nuevasEstrellas);
                                activarPublicarNueva(card, tuCali.querySelector(".btn-guardar-cali"), idT);
                            }

                        } else {
                            alert(data.error ?? "No se pudo eliminar.");
                            btnElm.disabled    = false;
                            btnElm.textContent = "Eliminar";
                        }
                    } catch {
                        alert("No se pudo conectar.");
                        btnElm.disabled    = false;
                        btnElm.textContent = "Eliminar";
                    }
                });
            }
        }

        // Activar botones de gestión que vienen del servidor (ya calificó)
        const grupoExistente = card.querySelector(".subtitulo-taller.arriba .btn-group");
        if (grupoExistente) activarBotonesGestion(card, grupoExistente);

        // Activar botón Publicar del formulario de edición ya renderizado por PHP
        const btnGuardarEdicion = card.querySelector(".form-edicion .btn-guardar-cali");
        const idTallerEdicion   = card.querySelector(".form-edicion .calificacion-estrellas")?.dataset.tallerId;
        if (btnGuardarEdicion) activarPublicarEdicion(card, btnGuardarEdicion, idTallerEdicion);
    });

    // Cerrar al hacer clic en el backdrop
    backdrop?.addEventListener("click", () => {
        const abierta = document.querySelector(".card-taller.expandida");
        if (abierta) cerrarTarjeta(abierta);
    });

    // ── Buscador, filtro, mis reseñas y filtro por rango ─────────────────
    const buscador      = document.querySelector(".buscador-wrapper input");
    const filtro        = document.getElementById("filtroEspecialidad");
    const btnMisReseñas = document.querySelector(".misreseñas");
    let soloMisReseñas  = false;
    let rangoActivo     = null; // "cinco" | "medio" | "bajo" | null

    function filtrarTarjetas() {
        const texto = buscador?.value.toLowerCase() ?? "";
        const esp   = filtro?.value ?? "todos";

        document.querySelectorAll(".card-taller").forEach(card => {
            const nombre       = card.dataset.nombre       ?? "";
            const direccion    = card.dataset.direccion    ?? "";
            const especialidad = card.dataset.especialidad ?? "";

            const coincideTexto = texto === "" ||
                nombre.includes(texto) || direccion.includes(texto) ||
                especialidad.toLowerCase().includes(texto);
            const coincideEsp   = esp === "todos" || especialidad === esp;
            const coincideMio   = !soloMisReseñas || card.dataset.calificado === "1";
            const coincideRango = rangoActivo === null || card.dataset.rango === rangoActivo;

            card.style.display = (coincideTexto && coincideEsp && coincideMio && coincideRango) ? "" : "none";
        });
    }

    if (btnMisReseñas) {
        btnMisReseñas.addEventListener("click", () => {
            soloMisReseñas = !soloMisReseñas;
            btnMisReseñas.classList.toggle("activo", soloMisReseñas);
            filtrarTarjetas();
        });
    }

    // Tarjetas de rango — toggle: clic activa, segundo clic desactiva
    document.querySelectorAll(".tarjeta-cantidad[data-rango]").forEach(tarjeta => {
        tarjeta.addEventListener("click", () => {
            const rango = tarjeta.dataset.rango;
            rangoActivo = rangoActivo === rango ? null : rango;

            document.querySelectorAll(".tarjeta-cantidad[data-rango]").forEach(t =>
                t.classList.toggle("activo", t.dataset.rango === rangoActivo)
            );
            filtrarTarjetas();
        });
    });

    buscador?.addEventListener("input",  filtrarTarjetas);
    filtro?.addEventListener("change", filtrarTarjetas);
});