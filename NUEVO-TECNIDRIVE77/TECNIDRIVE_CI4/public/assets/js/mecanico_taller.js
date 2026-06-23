 // 1. IR DE PANTALLA INICIAL A FORMULARIO
    function irAFormulario() {
        document.getElementById("contenedorInicial").classList.add("oculto");
        document.getElementById("contenedorFormulario").classList.remove("oculto");
    }

    // IR DE FORMULARIO A VISTA DEL TALLER COMPLETO
    document.getElementById("formTaller").addEventListener("submit", function (evento) {
        evento.preventDefault();
        document.getElementById("contenedorFormulario").classList.add("oculto");
        document.getElementById("contenidoCompleto").classList.remove("oculto");
    });

    // VISTA PREVIA DE LA FOTO SELECCIONADA EN REGISTRO
    document.getElementById("inputFoto").addEventListener("change", function () {
        const archivo = this.files[0];
        if (!archivo) return;

        const lector = new FileReader();
        lector.onload = function (e) {
            const preview = document.getElementById("preview-foto");
            preview.src = e.target.result;
            preview.style.display = "block";

            document.querySelector(".zona-foto span").style.display = "none";
            document.querySelector(".zona-foto i").style.display = "none";
            
            // Asignamos también esta imagen por defecto a la tarjeta de visualización
            document.getElementById("res-foto").src = e.target.result;
        };
        lector.readAsDataURL(archivo);
    });

    // AGREGAR Y ELIMINAR FILAS DINÁMICAS (FORMULARIO ORIGINAL)
    function agregarHorario() {
        const contenedor = document.getElementById("horarios");
        const fila = document.createElement("div");
        fila.className = "fila-dinamica";
        fila.innerHTML = `
            <input type="text" name="dias[]" class="input-registro" placeholder="Ej: Sábado" maxlength="25" required>
            <input type="text" name="horas[]" class="input-registro" placeholder="8:00am-4:00pm o cerrado" maxlength="40" required>
            <button type="button" class="btn-eliminar" onclick="borrarFila(this)">
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
            <input type="text" name="servicio[]" class="input-registro" placeholder="Ej: Alineación" required>
            <input type="text" name="precio[]" class="input-registro" placeholder="$20.000 (precio base)" required>
            <button type="button" class="btn-eliminar" onclick="borrarFila(this)">
                <i class="bi bi-trash3-fill"></i>
            </button>
        `;
        contenedor.appendChild(fila);
    }

    function borrarFila(btn) {
        btn.closest(".fila-dinamica").remove();
    }

    // ==========================================
    // LÓGICA DE EDICIÓN EN LINEA DE LA TARJETA
    // ==========================================
    const btnEditarTaller = document.getElementById("btnEditarTaller");
    const contenidoCompleto = document.getElementById("contenidoCompleto");

    btnEditarTaller.addEventListener("click", function() {
        const modoEditarActivo = contenidoCompleto.classList.contains("modo-edicion");

        if (!modoEditarActivo) {
            // ACTIVAR MODO EDICIÓN
            contenidoCompleto.classList.add("modo-edicion");
            btnEditarTaller.textContent = "Guardar cambios";
            btnEditarTaller.style.backgroundColor = "#28a745"; // Verde temporal de éxito

            // Intercambiar visibilidad de elementos simples
            contenidoCompleto.querySelectorAll(".texto-vista").forEach(el => el.style.display = "none");
            contenidoCompleto.querySelectorAll(".input-edicion").forEach(el => el.style.display = "block");
            document.getElementById("btn-cambiar-foto").style.display = "flex";

        } else {
            // GUARDAR CAMBIOS REALIZADOS
            // 1. Textos Generales
            document.getElementById("res-nombre").textContent = document.getElementById("edit-res-nombre").value;
            document.getElementById("res-especialidad").textContent = document.getElementById("edit-res-especialidad").value;
            document.getElementById("res-descripcion").textContent = document.getElementById("edit-res-descripcion").value;
            document.getElementById("res-ubicacion").textContent = document.getElementById("edit-res-ubicacion").value;

            // 2. Horarios
            const listaHorarios = document.getElementById("res-horarios");
            listaHorarios.querySelectorAll("li").forEach(li => {
                const inputs = li.querySelectorAll(".input-edicion input");
                if (inputs.length === 2) {
                    li.querySelector(".dia-semana").textContent = inputs[0].value;
                    li.querySelector(".bloque-hora").textContent = inputs[1].value;
                }
            });

            // 3. Tabla de precios
            const tablaServicios = document.getElementById("res-servicios");
            tablaServicios.querySelectorAll("tr").forEach(tr => {
                const inputServ = tr.querySelector(".edit-servicio");
                const inputPrec = tr.querySelector(".edit-precio");
                if (inputServ && inputPrec) {
                    tr.querySelector(".celda-servicio .texto-vista").textContent = inputServ.value;
                    tr.querySelector(".celda-precio .texto-vista").textContent = inputPrec.value;
                }
            });

            // DESACTIVAR MODO EDICIÓN Y VOLVER A LA VISTA NORMAL
            contenidoCompleto.classList.remove("modo-edicion");
            btnEditarTaller.textContent = "Editar información";
            btnEditarTaller.style.backgroundColor = ""; // Regresa al color original de tu CSS

            contenidoCompleto.querySelectorAll(".texto-vista").forEach(el => {
                if (el.tagName === "SPAN" || el.tagName === "P") {
                    el.style.display = "inline";
                } else {
                    el.style.display = "block";
                }
            });
            contenidoCompleto.querySelectorAll(".input-edicion").forEach(el => el.style.display = "none");
            document.getElementById("btn-cambiar-foto").style.display = "none";
        }
    });

    // Actualizar la foto directamente en el banner al seleccionarla
    document.getElementById("edit-res-foto").addEventListener("change", function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("res-foto").src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    const btnDesactivar = document.getElementById("btnDesactivarTaller");
const formMotivo = document.getElementById("formMotivo");

// Al hacer clic en Desactivar, aparece el formulario
btnDesactivar.addEventListener("click", function(e) {
    e.stopPropagation();
    formMotivo.classList.toggle("oculto-motivo"); // Toggle permite que si vuelven a hacer clic, se oculte
});

// Al enviar el formulario, procesa la info y se vuelve a ocultar
formMotivo.addEventListener("submit", function(e) {
    e.preventDefault();
    const motivo = this.querySelector("input").value;
    
    // Aquí ejecutas tu acción (ej. enviar datos al servidor)
    console.log("Motivo enviado:", motivo);
    
    formMotivo.classList.add("oculto-motivo"); // Se oculta tras enviar
    this.reset(); // Limpia el input
});