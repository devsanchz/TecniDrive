 document.addEventListener("DOMContentLoaded", () => {

    // ELEMENTOS HTML
    const profileCard = document.getElementById("profileCard");
    const editButton = document.getElementById("editButton");
    const saveButton = document.getElementById("saveButton");

    // EMAIL
    const userEmail = document.getElementById("userEmail");
    const emailInput = document.getElementById("emailInput");

    // TELEFONO
    const usertelefono = document.getElementById("usertelefono");
    const telefonoInput = document.getElementById("telefonoInput");

    // COLOR AVATAR
    const colorEditor = document.getElementById("colorEditor");
    const avatarColor = document.getElementById("avatarColor");
    const profileAvatar = document.getElementById("profileAvatar");

    // EVENTOS
    editButton.addEventListener("click", enableEditMode);
    saveButton.addEventListener("click", saveProfileChanges);

    if (avatarColor) {
        avatarColor.addEventListener("input", updateAvatarColor);
    }

    // FUNCION: ACTIVAR EDICION
    function enableEditMode() {
        // Añadir clase de edición a la carta
        profileCard.classList.add("editing");

        // Alternar visibilidad de textos e inputs (Email)
        userEmail.style.display = "none";
        emailInput.style.display = "inline-block";

        // Alternar visibilidad de textos e inputs (Teléfono)
        usertelefono.style.display = "none";
        telefonoInput.style.display = "inline-block";

        // Mostrar selector de color
        if (colorEditor) {
            colorEditor.style.display = "block";
        }

        // Alternar botones
        editButton.style.display = "none";
        saveButton.style.display = "inline-block";
    }

    // FUNCION: GUARDAR CAMBIOS
    function saveProfileChanges() {
        // Actualizar los textos con los nuevos valores de los inputs
        userEmail.textContent = emailInput.value;
        usertelefono.textContent = telefonoInput.value;

        // Mostrar textos y ocultar inputs
        userEmail.style.display = "inline";
        usertelefono.style.display = "inline";
        emailInput.style.display = "none";
        telefonoInput.style.display = "none";

        // Ocultar selector de color
        if (colorEditor) {
            colorEditor.style.display = "none";
        }

        // Quitar clase de edición de la carta
        profileCard.classList.remove("editing");

        // Alternar botones de nuevo
        saveButton.style.display = "none";
        editButton.style.display = "inline-block";
    }

    // FUNCION: CAMBIAR COLOR DESDE EL INPUT
    function updateAvatarColor() {
        const selectedColor = avatarColor.value;

        if (profileAvatar) {
            // Actualiza el borde del contenedor del avatar
            profileAvatar.style.borderColor = selectedColor;
            
            // Actualiza el color del ícono interno por si el diseño lo requiere
            const icon = profileAvatar.querySelector("i");
            if (icon) {
                icon.style.color = selectedColor;
            }
        }
    }
});