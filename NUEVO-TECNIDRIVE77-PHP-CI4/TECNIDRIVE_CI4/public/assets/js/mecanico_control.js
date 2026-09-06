 // =========================================================
    // SECCIÓN 1: Formulario de Pre-cierre en Tarjeta
    // =========================================================
    const boton = document.getElementById("btnMostrar");
    const formulario = document.getElementById("formulario");

    boton.addEventListener("click", () => {
      formulario.classList.toggle("mostrar");
    });

    // =========================================================
    // SECCIÓN 2: Panel de Lista de Trabajadores
    // =========================================================
    const btnLista     = document.querySelector(".btn-lista");
    const panelTrabaj  = document.getElementById("trabajadores");

    btnLista.addEventListener("click", (e) => {
      e.stopPropagation();
      panelTrabaj.classList.toggle("mostrar");
    });

    document.addEventListener("click", (e) => {
      if (!btnLista.contains(e.target) && !panelTrabaj.contains(e.target)) {
        panelTrabaj.classList.remove("mostrar");
      }
    });

    // =========================================================
    // SECCIÓN 3: Formulario de Finalizar Cita — toggle del panel
    // =========================================================
    const btnVerificar  = document.getElementById("btnVerificar");
    const formVerificar = document.getElementById("formVerificar");

    btnVerificar.addEventListener("click", (e) => {
      e.stopPropagation();
      formVerificar.classList.toggle("mostrar");
    });

    document.addEventListener("click", (e) => {
      if (!btnVerificar.contains(e.target) && !formVerificar.contains(e.target)) {
        formVerificar.classList.remove("mostrar");
      }
    });

    // =========================================================
    // SECCIÓN 4: Agregar / Guardar trabajador
    // =========================================================
    const btnAgregar      = document.getElementById("btn-agregar-trabajador");
    const inputAgregar    = document.getElementById("input-agregar");
    const inputNombre     = document.getElementById("nombre-nuevo-trabajador");
    const listaTrabajadores = document.getElementById("lista-trabajadores");
    let modoAgregar = false;

    btnAgregar.addEventListener("click", () => {
      if (!modoAgregar) {
        // Modo: mostrar input
        inputAgregar.style.display = "block";
        btnAgregar.textContent = "Guardar";
        modoAgregar = true;
        inputNombre.focus();
      } else {
        // Modo: guardar
        const nombre = inputNombre.value.trim();
        if (nombre !== "") {
          const li = document.createElement("li");
          li.innerHTML = `${nombre} <button class="btn-x-eliminar" onclick="eliminarTrabajador(this)" title="Eliminar">×</button>`;
          listaTrabajadores.appendChild(li);
          inputNombre.value = "";
        }
        inputAgregar.style.display = "none";
        btnAgregar.textContent = "Agregar";
        modoAgregar = false;
      }
    });

    // =========================================================
    // SECCIÓN 5: Eliminar trabajador del panel (botón Eliminar)
    // — elimina el último de la lista como acción rápida
    // =========================================================
    const btnEliminar = document.getElementById("btn-eliminar-trabajador");
    btnEliminar.addEventListener("click", () => {
      const items = listaTrabajadores.querySelectorAll("li");
      if (items.length > 0) {
        const ultimo = items[items.length - 1];
        ultimo.style.transition = "opacity 0.3s, transform 0.3s";
        ultimo.style.opacity = "0";
        ultimo.style.transform = "translateX(20px)";
        setTimeout(() => ultimo.remove(), 300);
      }
    });

    // =========================================================
    // SECCIÓN 6: Eliminar trabajador con la X de cada ítem
    // =========================================================
    function eliminarTrabajador(btn) {
      const li = btn.closest("li");
      li.style.transition = "opacity 0.3s, transform 0.3s";
      li.style.opacity = "0";
      li.style.transform = "translateX(20px)";
      setTimeout(() => li.remove(), 300);
    }
 