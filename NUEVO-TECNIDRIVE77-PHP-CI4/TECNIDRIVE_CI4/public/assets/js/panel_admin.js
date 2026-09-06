// 1. SELECCIÓN DE ELEMENTOS (Solo los que existen en tu HTML)
const editButton = document.getElementById("editButton");
const saveButton = document.getElementById("saveButton");

// Elementos de Email
const userEmail = document.getElementById("userEmail");
const emailInput = document.getElementById("emailInput");

// Elementos del Color
const colorEditor = document.getElementById("colorEditor");
const avatarColor = document.getElementById("avatarColor");
const profileAvatar = document.getElementById("profileAvatar");
const profileIcon = document.getElementById("profileIcon");

// ==========================================
// 2. MOSTRAR LA SECCIÓN DE EDICIÓN
// ==========================================
editButton.addEventListener("click", () => {
    // Ocultar texto del email
    if(userEmail) userEmail.style.display = "none";

    // Mostrar casilla de entrada (input)
    if(emailInput) emailInput.style.display = "inline-block";

    // Mostrar el selector de color
    if(colorEditor) colorEditor.style.display = "block";

    // Intercambiar botones
    editButton.style.display = "none";
    saveButton.style.display = "inline-block";
});

// ==========================================
// 3. OCULTAR LA SECCIÓN DE EDICIÓN (SIN GUARDAR)
// ==========================================
saveButton.addEventListener("click", () => {
    // Volver a mostrar el texto original
    if(userEmail) userEmail.style.display = "inline";

    // Ocultar la casilla de entrada y el selector de color
    if(emailInput) emailInput.style.display = "none";
    if(colorEditor) colorEditor.style.display = "none";

    // Intercambiar botones de nuevo
    saveButton.style.display = "none";
    editButton.style.display = "inline-block";
});

// ==========================================
// 4. CAMBIAR EL COLOR EN VIVO
// ==========================================
avatarColor.addEventListener("input", () => {
    const colorSeleccionado = avatarColor.value;
    
    if(profileAvatar) {
        profileAvatar.style.setProperty('border-color', colorSeleccionado, 'important');
    }
    if(profileIcon) {
        profileIcon.style.setProperty('color', colorSeleccionado, 'important');
    }
});