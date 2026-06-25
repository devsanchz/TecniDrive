// ============================================================
// admin_taller.js
// ============================================================

document.addEventListener("DOMContentLoaded", () => {

    // ── 1. TOOLTIP DE MOTIVO (ícono ojo en talleres desactivados) ─────
    document.querySelectorAll(".icono-ojo").forEach(icono => {
        icono.addEventListener("click", (e) => {
            e.stopPropagation();
            const tooltip = icono.nextElementSibling;
            if (tooltip) tooltip.classList.toggle("activo");
        });
    });

    // Cerrar tooltips al hacer clic en cualquier otro lugar
    document.addEventListener("click", () => {
        document.querySelectorAll(".tooltip-motivo.activo")
                .forEach(t => t.classList.remove("activo"));
    });


    // ── 2. MODAL DE DETALLES DEL TALLER ──────────────────────────────
    const modalTaller = document.getElementById("modalTaller");
    const btnCerrar   = modalTaller.querySelector(".btn-cerrar");

    // Abrir modal y llenarlo con los datos del taller clicado
    document.querySelectorAll(".btn-detalles").forEach(btn => {
        btn.addEventListener("click", () => {
            const id     = parseInt(btn.dataset.id);
            const taller = TALLERES_DATA.find(t => t.id === id);
            if (! taller) return;

            // Llenar los campos del modal
            document.getElementById("modalFoto").src           = taller.foto;
            document.getElementById("modalFoto").alt           = "Foto de " + taller.nombre;
            document.getElementById("modalNombre").textContent     = taller.nombre;
            document.getElementById("modalEspecialidad").textContent = taller.especialidad;
            document.getElementById("modalDescripcion").textContent  = taller.descripcion;
            document.getElementById("modalDireccion").textContent    = taller.direccion;

            // Horarios: parsear "L-V: 8am | Sáb: 9am" en <li>
            const ulHorarios = document.getElementById("modalHorarios");
            ulHorarios.innerHTML = "";
            taller.horario.split("|").forEach(bloque => {
                const partes = bloque.split(":").map(s => s.trim());
                const dia    = partes[0] ?? bloque.trim();
                const hora   = partes.slice(1).join(":").trim();
                const li     = document.createElement("li");
                li.innerHTML = `<span class="dia-semana">${dia}</span>
                                <span class="bloque-hora">${hora}</span>`;
                ulHorarios.appendChild(li);
            });

            // Servicios: llenar la tabla
            const tbodyServ = document.getElementById("modalServicios");
            tbodyServ.innerHTML = "";
            if (taller.servicios.length === 0) {
                tbodyServ.innerHTML = `<tr><td colspan="2" style="text-align:center;color:#888;">Sin servicios registrados</td></tr>`;
            } else {
                taller.servicios.forEach(s => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `<td class="celda-servicio">${s.nombre}</td>
                                    <td class="celda-precio">$${s.precio}</td>`;
                    tbodyServ.appendChild(tr);
                });
            }

            modalTaller.classList.add("active");
        });
    });

    // Cerrar con la X
    btnCerrar.addEventListener("click", () => {
        modalTaller.classList.remove("active");
    });

    // Cerrar al hacer clic en el fondo oscuro
    modalTaller.addEventListener("click", (e) => {
        if (e.target === modalTaller) modalTaller.classList.remove("active");
    });


    // ── 3. BUSCADOR Y FILTRO EN TIEMPO REAL ───────────────────────────
    const buscador    = document.getElementById("buscadorTaller");
    const filtroEstado = document.getElementById("filtroEstado");
    const filas        = document.querySelectorAll("#cuerpoTabla .fila-cuerpo");

    function filtrar() {
        const texto  = buscador.value.toLowerCase();
        const estado = filtroEstado.value;

        filas.forEach(fila => {
            const nombre  = fila.dataset.nombre  ?? "";
            const taller  = fila.dataset.taller  ?? "";
            const estFila = fila.dataset.estado   ?? "";

            const coincideTexto  = texto === "" || nombre.includes(texto) || taller.includes(texto);
            const coincideEstado = estado === "todos" || estFila === estado;

            fila.style.display = (coincideTexto && coincideEstado) ? "" : "none";
        });
    }

    // ── 4. FORM FLOTANTE DE MOTIVO (DESACTIVAR TALLER ADMIN) ─────────────
    document.querySelectorAll(".btn-abrir-motivo").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            const targetId = btn.dataset.target;
            const form     = document.getElementById(targetId);
            if (! form) return;

            // Cerrar cualquier otro abierto
            document.querySelectorAll(".form-flotante-motivo:not(.oculto-motivo)").forEach(f => {
                if (f !== form) f.classList.add("oculto-motivo");
            });

            form.classList.toggle("oculto-motivo");
        });
    });

    // Clic fuera cierra los forms flotantes
    document.addEventListener("click", () => {
        document.querySelectorAll(".form-flotante-motivo:not(.oculto-motivo)")
                .forEach(f => f.classList.add("oculto-motivo"));
    });
});