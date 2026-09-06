document.addEventListener("DOMContentLoaded", () => {

    // =========================================================================
    // ELEMENTOS
    // =========================================================================
    const profileCard  = document.getElementById("profileCard");
    const editButton   = document.getElementById("editButton");
    const saveButton   = document.getElementById("saveButton");
    const perfilError  = document.getElementById("perfilError");

    // Email
    const userEmail  = document.getElementById("userEmail");
    const emailInput = document.getElementById("emailInput");

    // Teléfono
    const usertelefono  = document.getElementById("usertelefono");
    const telefonoInput = document.getElementById("telefonoInput");

    // Color de avatar
    const colorEditor   = document.getElementById("colorEditor");
    const avatarColor   = document.getElementById("avatarColor");
    const profileAvatar = document.getElementById("profileAvatar");
    const profileIcon   = document.getElementById("profileIcon");

    // =========================================================================
    // ACTIVAR MODO EDICIÓN
    // =========================================================================
    editButton.addEventListener("click", () => {
        profileCard.classList.add("editing");

        userEmail.style.display     = "none";
        emailInput.style.display    = "inline-block";

        usertelefono.style.display  = "none";
        telefonoInput.style.display = "inline-block";

        colorEditor.style.display   = "block";

        editButton.style.display = "none";
        saveButton.style.display = "inline-block";

        ocultarError();
    });

    // =========================================================================
    // CAMBIAR COLOR EN VIVO (solo visual, aún no guarda)
    // =========================================================================
    avatarColor.addEventListener("input", () => {
        aplicarColor(avatarColor.value);
    });

    // =========================================================================
    // GUARDAR — envía fetch POST al servidor con token CSRF
    // =========================================================================
    saveButton.addEventListener("click", async () => {
        ocultarError();

        saveButton.disabled    = true;
        saveButton.textContent = "Guardando…";

        const payload = {
            email:       emailInput.value.trim(),
            telefono:    telefonoInput.value.trim(),
            avatarcolor: avatarColor.value,
        };

        try {
            const data = await guardarPerfil(payload);

            if (data.ok) {
                // Actualizar la UI con los valores confirmados por el servidor
                userEmail.textContent    = emailInput.value.trim();
                usertelefono.textContent = telefonoInput.value.trim();
                aplicarColor(avatarColor.value);

                salirModoEdicion();
            } else {
                const primerError = Object.values(data.errores ?? {})[0] ?? "Error al guardar.";
                mostrarError(primerError);
                saveButton.disabled    = false;
                saveButton.textContent = "Listo";
            }

        } catch (e) {
            mostrarError("No se pudo conectar con el servidor.");
            saveButton.disabled    = false;
            saveButton.textContent = "Listo";
        }
    });

    // =========================================================================
    // FUNCIONES AUXILIARES — UI
    // =========================================================================

    function aplicarColor(color) {
        if (profileAvatar) profileAvatar.style.borderColor = color;
        if (profileIcon)   profileIcon.style.color         = color;
    }

    function salirModoEdicion() {
        profileCard.classList.remove("editing");

        userEmail.style.display     = "inline";
        emailInput.style.display    = "none";

        usertelefono.style.display  = "inline";
        telefonoInput.style.display = "none";

        colorEditor.style.display   = "none";

        saveButton.disabled    = false;
        saveButton.textContent = "Listo";
        saveButton.style.display = "none";
        editButton.style.display = "inline-block";
    }

    function mostrarError(msg) {
        perfilError.textContent   = msg;
        perfilError.style.display = "block";
    }

    function ocultarError() {
        perfilError.textContent   = "";
        perfilError.style.display = "none";
    }

    // =========================================================================
    // FUNCIONES AUXILIARES — CSRF
    // =========================================================================

    // Leer el token CSRF actual desde el HTML
    function obtenerCsrf() {
        const metaName = document.querySelector('meta[name="csrf-name"]');
        const metaHash = document.querySelector('meta[name="csrf-hash"]');

        return {
            nombre: metaName ? metaName.content : null,
            valor:  metaHash ? metaHash.content : null,
        };
    }

    // Único punto de guardado: envía el header X-CSRF-TOKEN requerido por CI4
    // (modo csrfProtection = 'cookie') y rota el token en el HTML tras cada
    // respuesta exitosa, para que la SIGUIENTE edición en la misma carga de
    // página no falle con 403.
    async function guardarPerfil(datos) {
        const csrf = obtenerCsrf();

        const respuesta = await fetch(PERFIL_URL, {
            method:      "POST",
            credentials: "same-origin", // necesario para que viaje csrf_cookie_name
            headers: {
                "Content-Type":     "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN":     csrf.valor, // sin esto, CI4 rechaza la petición
            },
            body: JSON.stringify(datos),
        });

        const json = await respuesta.json();

        if (json.csrf_token_value) {
            const metaHash = document.querySelector('meta[name="csrf-hash"]');
            if (metaHash) metaHash.content = json.csrf_token_value;
        }

        return json;
    }
});