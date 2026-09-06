// ============================================================================
// mecanico_taller.js
// Maneja exclusivamente la interfaz de usuario:
//   - Mostrar/ocultar el formulario de registro
//   - Vista previa de la foto antes de subir
//   - Filas dinámicas de horarios y servicios (formulario de registro)
//   - Modo edición en línea de la tarjeta del taller
//   - Submit real al servidor mediante #formActualizar
//   - Modal de desactivación
// ============================================================================


// ── 1. MOSTRAR FORMULARIO DE REGISTRO ────────────────────────────────────────
function irAFormulario() {
    document.getElementById("contenedorInicial").classList.add("oculto");
    document.getElementById("contenedorFormulario").classList.remove("oculto");
}


// ── 2. VISTA PREVIA DE LA FOTO (formulario de registro) ───────────────────────
const inputFoto = document.getElementById("inputFoto");
if (inputFoto) {
    inputFoto.addEventListener("change", function () {
        const archivo = this.files[0];
        if (!archivo) return;

        const lector = new FileReader();
        lector.onload = function (e) {
            const preview = document.getElementById("preview-foto");
            preview.src = e.target.result;
            preview.style.display = "block";
            document.querySelector(".zona-foto span").style.display = "none";
            document.querySelector(".zona-foto i").style.display   = "none";
        };
        lector.readAsDataURL(archivo);
    });
}


// ── 3. FILAS DINÁMICAS — HORARIOS (registro) ──────────────────────────────────
function agregarHorario() {
    const contenedor = document.getElementById("horarios");
    const fila = document.createElement("div");
    fila.className = "fila-dinamica";
    fila.innerHTML = `
        <input type="text" name="dias[]" class="input-registro"
               placeholder="Ej: Sábado" maxlength="25" required>
        <input type="text" name="horas[]" class="input-registro"
               placeholder="8:00am-4:00pm o cerrado" maxlength="40" required>
        <button type="button" class="btn-eliminar" onclick="borrarFila(this)">
            <i class="bi bi-trash3-fill"></i>
        </button>
    `;
    contenedor.appendChild(fila);
}


// ── 4. FILAS DINÁMICAS — SERVICIOS (registro) ─────────────────────────────────
function agregarServicio() {
    const contenedor = document.getElementById("servicios");
    const fila = document.createElement("div");
    fila.className = "fila-dinamica";
    fila.innerHTML = `
        <input type="text" name="servicio[]" class="input-registro"
               placeholder="Ej: Alineación" required>
        <input type="text" name="precio[]" class="input-registro"
               placeholder="$20.000 (precio base)" required>
        <button type="button" class="btn-eliminar" onclick="borrarFila(this)">
            <i class="bi bi-trash3-fill"></i>
        </button>
    `;
    contenedor.appendChild(fila);
}


// ── 5. ELIMINAR FILA DINÁMICA (registro) ──────────────────────────────────────
function borrarFila(btn) {
    btn.closest(".fila-dinamica").remove();
}


// ── 6. MODO EDICIÓN EN LÍNEA ──────────────────────────────────────────────────
const btnEditarTaller   = document.getElementById("btnEditarTaller");
const contenidoCompleto = document.getElementById("contenidoCompleto");

if (btnEditarTaller && contenidoCompleto) {

    // ── 6a. Botón Editar / Guardar ────────────────────────────────────────────
    btnEditarTaller.addEventListener("click", function () {
        const modoActivo = contenidoCompleto.classList.contains("modo-edicion");

        if (!modoActivo) {
            // ── ACTIVAR modo edición ──────────────────────────────────────────
            contenidoCompleto.classList.add("modo-edicion");
            btnEditarTaller.textContent = "Guardar cambios";
            btnEditarTaller.style.backgroundColor = "#28a745";

            contenidoCompleto.querySelectorAll(".texto-vista").forEach(el => el.style.display = "none");
            contenidoCompleto.querySelectorAll(".input-edicion").forEach(el => el.style.display = "block");
            document.getElementById("btn-cambiar-foto").style.display = "flex";

        } else {
            // ── GUARDAR: actualizar la vista Y enviar al servidor ─────────────
            guardarCambiosEnVista();
            enviarFormActualizar();
        }
    });

    // ── 6b. Vista previa de la nueva foto en modo edición ────────────────────
    const editFoto = document.getElementById("edit-res-foto");
    if (editFoto) {
        editFoto.addEventListener("change", function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("res-foto").src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }
}


// ── 6c. Actualizar la vista con los valores editados ─────────────────────────
function guardarCambiosEnVista() {
    // Campos de texto simples
    document.getElementById("res-nombre").textContent       = document.getElementById("edit-res-nombre").value;
    document.getElementById("res-especialidad").textContent = document.getElementById("edit-res-especialidad").value;
    document.getElementById("res-descripcion").textContent  = document.getElementById("edit-res-descripcion").value;
    document.getElementById("res-ubicacion").textContent    = document.getElementById("edit-res-ubicacion").value;

    // Horarios
    document.getElementById("res-horarios").querySelectorAll("li").forEach(li => {
        const diaInput  = li.querySelector(".edit-dia");
        const horaInput = li.querySelector(".edit-hora");
        if (diaInput && horaInput) {
            li.querySelector(".dia-semana").textContent  = diaInput.value;
            li.querySelector(".bloque-hora").textContent = horaInput.value;
        }
    });

    // Servicios
    document.getElementById("res-servicios").querySelectorAll("tr[data-nueva]").forEach(tr => {
        // Las filas nuevas ya tienen sus spans; nada extra que hacer
    });
    document.getElementById("res-servicios").querySelectorAll("tr:not([data-nueva])").forEach(tr => {
        const inputServ = tr.querySelector(".edit-servicio");
        const inputPrec = tr.querySelector(".edit-precio");
        if (inputServ && inputPrec) {
            tr.querySelector(".celda-servicio .texto-vista").textContent = inputServ.value;
            tr.querySelector(".celda-precio .texto-vista").textContent   = inputPrec.value;
        }
    });

    // Desactivar modo edición
    contenidoCompleto.classList.remove("modo-edicion");
    btnEditarTaller.textContent = "Editar información";
    btnEditarTaller.style.backgroundColor = "";

    contenidoCompleto.querySelectorAll(".texto-vista").forEach(el => {
        el.style.display = (el.tagName === "SPAN" || el.tagName === "P") ? "inline" : "block";
    });
    contenidoCompleto.querySelectorAll(".input-edicion").forEach(el => el.style.display = "none");
    document.getElementById("btn-cambiar-foto").style.display = "none";
}


// ── 6d. Construir el formActualizar y enviarlo al servidor ────────────────────
function enviarFormActualizar() {
    // 1. Campos escalares
    document.getElementById("hidden-nombre").value       = document.getElementById("edit-res-nombre").value.trim();
    document.getElementById("hidden-especialidad").value = document.getElementById("edit-res-especialidad").value.trim();
    document.getElementById("hidden-descripcion").value  = document.getElementById("edit-res-descripcion").value.trim();
    document.getElementById("hidden-ubicacion").value    = document.getElementById("edit-res-ubicacion").value.trim();

    // 2. Horarios: leer todos los <li> del listado (incluyendo los nuevos)
    const wrapHorarios = document.getElementById("hidden-horarios-wrap");
    wrapHorarios.innerHTML = ""; // limpiar de ejecuciones anteriores

    document.getElementById("res-horarios").querySelectorAll("li").forEach(li => {
        // Puede ser una fila editada (tiene .edit-dia) o una nueva (también la tiene)
        const editDia  = li.querySelector(".edit-dia");
        const editHora = li.querySelector(".edit-hora");

        // Si por alguna razón no hay inputs (modo vista ya se restauró),
        // leer los spans que ya fueron actualizados en guardarCambiosEnVista()
        const dia  = editDia  ? editDia.value.trim()  : (li.querySelector(".dia-semana")?.textContent.trim()  ?? "");
        const hora = editHora ? editHora.value.trim() : (li.querySelector(".bloque-hora")?.textContent.trim() ?? "");

        if (dia === "") return; // omitir filas vacías

        const inputDia  = document.createElement("input");
        inputDia.type   = "hidden";
        inputDia.name   = "dias[]";
        inputDia.value  = dia;
        wrapHorarios.appendChild(inputDia);

        const inputHora  = document.createElement("input");
        inputHora.type   = "hidden";
        inputHora.name   = "horas[]";
        inputHora.value  = hora;
        wrapHorarios.appendChild(inputHora);
    });

    // 3. Servicios: leer todas las <tr> de la tabla (incluyendo las nuevas)
    const wrapServicios = document.getElementById("hidden-servicios-wrap");
    wrapServicios.innerHTML = "";

    document.getElementById("res-servicios").querySelectorAll("tr").forEach(tr => {
        // Saltar la fila de "sin servicios"
        if (tr.id === "fila-sin-servicios") return;

        const editServ = tr.querySelector(".edit-servicio");
        const editPrec = tr.querySelector(".edit-precio");

        // Misma lógica: preferir el input, caer al span si ya fue restaurado
        const nombreServ = editServ ? editServ.value.trim()
            : (tr.querySelector(".celda-servicio .texto-vista")?.textContent.trim() ?? "");
        const precioServ = editPrec ? editPrec.value.trim()
            : (tr.querySelector(".celda-precio .texto-vista")?.textContent.trim() ?? "");

        if (nombreServ === "") return;

        const inputServ  = document.createElement("input");
        inputServ.type   = "hidden";
        inputServ.name   = "servicio[]";
        inputServ.value  = nombreServ;
        wrapServicios.appendChild(inputServ);

        const inputPrec  = document.createElement("input");
        inputPrec.type   = "hidden";
        inputPrec.name   = "precio[]";
        inputPrec.value  = precioServ;
        wrapServicios.appendChild(inputPrec);
    });

    // 4. Foto: transferir el FileList al input oculto del formulario
    //    DataTransfer es la forma estándar de construir un FileList programáticamente
    const editFotoInput  = document.getElementById("edit-res-foto");
    const hiddenFotoInput = document.getElementById("hidden-foto");

    if (editFotoInput && editFotoInput.files.length > 0) {
        try {
            const dt = new DataTransfer();
            dt.items.add(editFotoInput.files[0]);
            hiddenFotoInput.files = dt.files;
        } catch (e) {
            // Fallback: si DataTransfer no está disponible (entorno antiguo),
            // la foto simplemente no se enviará y PHP conservará la existente.
            console.warn("No se pudo transferir la foto:", e);
        }
    }

    // 5. Enviar
    document.getElementById("formActualizar").submit();
}


// ── 7. AÑADIR HORARIO EN MODO EDICIÓN ────────────────────────────────────────
function agregarHorarioEdicion() {
    const lista = document.getElementById("res-horarios");
    const li = document.createElement("li");
    li.innerHTML = `
        <div class="texto-vista" style="display:none;">
            <span class="dia-semana"></span>
            <span class="bloque-hora"></span>
        </div>
        <div class="input-edicion flex-inputs" style="display:block;">
            <input type="text" class="input-registro edit-dia"
                   placeholder="Ej: Domingo" maxlength="25">
            <input type="text" class="input-registro edit-hora"
                   placeholder="Cerrado" maxlength="40">
            <button type="button" class="btn-eliminar"
                    onclick="this.closest('li').remove()">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </div>
    `;
    lista.appendChild(li);
}


// ── 8. AÑADIR SERVICIO EN MODO EDICIÓN ───────────────────────────────────────
function agregarServicioEdicion() {
    // Si existía la fila de "sin servicios", la ocultamos
    const filaSinServs = document.getElementById("fila-sin-servicios");
    if (filaSinServs) filaSinServs.remove();

    const tbody = document.getElementById("res-servicios");
    const tr = document.createElement("tr");
    tr.setAttribute("data-nueva", "1"); // marcar como fila nueva
    tr.innerHTML = `
        <td class="celda-servicio">
            <span class="texto-vista" style="display:none;"></span>
            <input type="text"
                   class="input-registro input-edicion edit-servicio"
                   placeholder="Ej: Alineación"
                   style="display:block;">
        </td>
        <td class="celda-precio">
            <span class="texto-vista" style="display:none;"></span>
            <input type="text"
                   class="input-registro input-edicion edit-precio"
                   placeholder="$20.000"
                   style="display:block;">
        </td>
        <td class="input-edicion col-borrar" style="display:block;">
            <button type="button" class="btn-eliminar"
                    onclick="borrarFilaServicioEdicion(this)">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}


// ── 9. BORRAR FILA DE SERVICIO EN MODO EDICIÓN ───────────────────────────────
function borrarFilaServicioEdicion(btn) {
    btn.closest("tr").remove();
}


// ── 10. FORMULARIO DE DESACTIVACIÓN ──────────────────────────────────────────
const btnDesactivar = document.getElementById("btnDesactivarTaller");
const formMotivo    = document.getElementById("formMotivo");

if (btnDesactivar && formMotivo) {
    btnDesactivar.addEventListener("click", function (e) {
        e.stopPropagation();
        formMotivo.classList.toggle("oculto-motivo");
    });

    document.addEventListener("click", function (e) {
        if (!formMotivo.contains(e.target) && e.target !== btnDesactivar) {
            formMotivo.classList.add("oculto-motivo");
        }
    });
}