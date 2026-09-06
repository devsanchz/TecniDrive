// 1. SELECCIÓN DE ELEMENTOS
const editButton = document.getElementById("editButton");
const saveButton = document.getElementById("saveButton");

// Elementos de Email y Teléfono (Textos e Inputs)
const userEmail = document.getElementById("userEmail");
const emailInput = document.getElementById("emailInput");
const userTelefono = document.getElementById("usertelefono");
const telefonoInput = document.getElementById("telefonoInput");

// Elementos del Color
const colorEditor = document.getElementById("colorEditor");
const avatarColor = document.getElementById("avatarColor");
const profileAvatar = document.getElementById("profileAvatar");
const profileIcon = document.getElementById("profileIcon");

// ==========================================
// 2. MOSTRAR LA SECCIÓN DE EDICIÓN
// ==========================================
editButton.addEventListener("click", () => {
    // Ocultar textos estáticos
    userEmail.style.display = "none";
    userTelefono.style.display = "none";

    // Mostrar casillas de entrada (inputs)
    emailInput.style.display = "inline-block";
    telefonoInput.style.display = "inline-block";

    // Mostrar el selector de color
    colorEditor.style.display = "block";

    // Intercambiar botones
    editButton.style.display = "none";
    saveButton.style.display = "inline-block";
});

// ==========================================
// 3. OCULTAR LA SECCIÓN DE EDICIÓN (SIN GUARDAR)
// ==========================================
saveButton.addEventListener("click", () => {
    // Volver a mostrar los textos tal y como estaban
    userEmail.style.display = "inline";
    userTelefono.style.display = "inline";

    // Ocultar las casillas de entrada y el selector de color
    emailInput.style.display = "none";
    telefonoInput.style.display = "none";
    colorEditor.style.display = "none";

    // Intercambiar botones de nuevo
    saveButton.style.display = "none";
    editButton.style.display = "inline-block";
});

// ==========================================
// 4. CAMBIAR EL COLOR EN VIVO (VISUAL)
// ==========================================
avatarColor.addEventListener("input", () => {
    const colorSeleccionado = avatarColor.value;
    profileAvatar.style.borderColor = colorSeleccionado;
    profileIcon.style.color = colorSeleccionado;
});