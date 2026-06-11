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
    const userTelefono  = document.getElementById("usertelefono");
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

        userTelefono.style.display  = "none";
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
    // GUARDAR — envía fetch POST al servidor
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
            const respuesta = await fetch(PERFIL_URL, {
                method:  "POST",
                headers: {
                    "Content-Type":     "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(payload),
            });

            const data = await respuesta.json();

            if (data.ok) {
                // Actualizar la UI con los valores confirmados por el servidor
                userEmail.textContent    = emailInput.value.trim();
                userTelefono.textContent = telefonoInput.value.trim();
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
    // FUNCIONES AUXILIARES
    // =========================================================================

    function aplicarColor(color) {
        if (profileAvatar) profileAvatar.style.borderColor = color;
        if (profileIcon)   profileIcon.style.color         = color;
    }

    function salirModoEdicion() {
        profileCard.classList.remove("editing");

        userEmail.style.display     = "inline";
        emailInput.style.display    = "none";

        userTelefono.style.display  = "inline";
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

});