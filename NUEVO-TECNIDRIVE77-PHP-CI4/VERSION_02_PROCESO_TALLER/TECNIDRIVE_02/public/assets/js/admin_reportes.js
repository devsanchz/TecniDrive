// admin_reportes.js
// Detecta cuál <details> está abierto al enviar el formulario,
// valida los campos requeridos según la sección y luego inyecta
// el campo "seccion" que lee el controlador con getPost('seccion').

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("formReporte");
    if (! form) return;

    form.addEventListener("submit", function (e) {

        // ── 1. Detectar el <details> abierto ─────────────────────────────────
        const detalleAbierto = form.querySelector("details[open]");

        if (! detalleAbierto) {
            e.preventDefault();
            alert("Abre y configura una sección antes de generar el reporte.");
            return;
        }

        // ── 2. Leer el valor de sección desde el input oculto interno ─────────
        const inputOculto = detalleAbierto.querySelector("input[type='hidden']");
        const seccion     = inputOculto ? inputOculto.value : null;

        if (! seccion) {
            e.preventDefault();
            alert("No se pudo identificar la sección seleccionada.");
            return;
        }

        // ── 3. Validaciones específicas por sección ───────────────────────────

        if (seccion === "usuarios") {
            const checksUsuarios = form.querySelectorAll("input[name='roles_usuarios[]']:checked");
            if (checksUsuarios.length === 0) {
                e.preventDefault();
                alert("Selecciona al menos un tipo de usuario (Propietarios o Mecánicos).");
                return;
            }
        }

        if (seccion === "vehiculos") {
            const checksVehiculos = form.querySelectorAll("input[name='tipos_vehiculos[]']:checked");
            if (checksVehiculos.length === 0) {
                e.preventDefault();
                alert("Selecciona al menos un tipo de vehículo (Automóviles o Motocicletas).");
                return;
            }
        }

        // "talleres" no necesita validación adicional en el cliente:
        // el estado tiene "Todos" marcado por defecto y el período tiene
        // "Esta semana" marcado por defecto, por lo que siempre hay valores.

        // ── 4. Inyectar el campo "seccion" que leerá el controlador ───────────
        let campSeccion = form.querySelector("input[name='seccion']");
        if (! campSeccion) {
            campSeccion       = document.createElement("input");
            campSeccion.type  = "hidden";
            campSeccion.name  = "seccion";
            form.appendChild(campSeccion);
        }
        campSeccion.value = seccion;
    });
});