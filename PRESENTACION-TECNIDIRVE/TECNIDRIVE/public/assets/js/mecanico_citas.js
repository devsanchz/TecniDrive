 // =========================================================
    // SECCIÓN 1: Formulario de Rechazo en Tarjeta (sin cambios)
    // =========================================================
    const boton = document.getElementById("btnMostrar");
    const formulario = document.getElementById("formulario");

    boton.addEventListener("click", () => {
      formulario.classList.toggle("mostrar");
    });

    // =========================================================
    // SECCIÓN 2: Formulario de Verificar Cita — toggle del panel
    // =========================================================
    const btnVerificar  = document.getElementById("btnVerificar");
    const formVerificar = document.getElementById("formVerificar");

    btnVerificar.addEventListener("click", (e) => {
      e.stopPropagation();
      formVerificar.classList.toggle("mostrar");
    });

    // Cerrar el formulario si se hace clic fuera (comportamiento original)
    document.addEventListener("click", (e) => {
      if (!btnVerificar.contains(e.target) && !formVerificar.contains(e.target)) {
        formVerificar.classList.remove("mostrar");
      }
    });

    // =========================================================
    // SECCIÓN 3: Lógica de verificación de código
    //   - Busca la casilla cuyo data-codigo coincide con el valor
    //     ingresado en el formulario.
    //   - Si hay coincidencia, activa el banner interno de esa
    //     casilla y actualiza el chip de estado.
    //   - Si no hay coincidencia, muestra feedback sutil en el input.
    // =========================================================
    formVerificar.addEventListener("submit", (e) => {
      e.preventDefault();

      const inputCodigo    = formVerificar.querySelector("input[type='text']");
      const codigoIngresado = inputCodigo.value.trim().toUpperCase();

      if (!codigoIngresado) return;

      // Buscar la casilla que tenga el data-codigo coincidente
      const casilla = document.querySelector(
        `.casilla[data-codigo="${codigoIngresado}"]`
      );

      if (casilla) {
        // ✅ Código correcto

        // 1. Mostrar el banner de estado dentro de la casilla
        const banner = casilla.querySelector(".banner-en-atencion");
        if (banner) banner.classList.add("visible");

        // 2. Actualizar el chip de estado en el encabezado de esa casilla
        const chipEstado = casilla.querySelector(".estado");
        if (chipEstado) {
          chipEstado.textContent = "En atención";
          chipEstado.className   = "estado en-atencion";
        }

        // 3. Limpiar y cerrar el formulario de verificación
        inputCodigo.value = "";
        formVerificar.classList.remove("mostrar");

        // 4. Llevar la casilla al foco visual con scroll suave
        casilla.scrollIntoView({ behavior: "smooth", block: "center" });

      } else {
        // ❌ Código incorrecto: feedback visual en el input, 2 segundos
        inputCodigo.style.borderColor = "#dc3545";
        setTimeout(() => { inputCodigo.style.borderColor = ""; }, 2000);
      }
    });