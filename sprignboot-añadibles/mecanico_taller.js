function irAFormulario() {
    document.getElementById("contenedorInicial").classList.add("oculto");
    document.getElementById("contenedorFormulario").classList.remove("oculto");
}

function agregarHorario() {

    const contenedor = document.getElementById("horarios");

    const fila = document.createElement("div");
    fila.className = "fila-dinamica";

    fila.innerHTML = `
        <input type="text"
               name="dias[]"
               class="input-registro"
               placeholder="Ej: Sábado"
               maxlength="25"
               required>

        <input type="text"
               name="horas[]"
               class="input-registro"
               placeholder="8:00am-12:00pm"
               maxlength="40"
               required>

        <button type="button"
                class="btn-eliminar"
                onclick="borrarFila(this)">
            <i class="bi bi-trash3-fill"></i>
        </button>
    `;

    contenedor.appendChild(fila);
}

function agregarServicio() {

    const contenedor = document.getElementById("servicios");

    const fila = document.createElement("div");
    fila.className = "fila-dinamica";

    fila.innerHTML = `
        <input type="text"
               name="servicio[]"
               class="input-registro"
               placeholder="Ej: Cambio de aceite"
               required>

        <input type="text"
               name="precio[]"
               class="input-registro"
               placeholder="$20.000"
               required>

        <button type="button"
                class="btn-eliminar"
                onclick="borrarFila(this)">
            <i class="bi bi-trash3-fill"></i>
        </button>
    `;

    contenedor.appendChild(fila);
}

function borrarFila(boton) {
    boton.parentElement.remove();
}