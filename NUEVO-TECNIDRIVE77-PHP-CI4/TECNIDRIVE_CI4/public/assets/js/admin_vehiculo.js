  const iconosOjo = document.querySelectorAll(".icono-ojo");
    iconosOjo.forEach(icono => {
        icono.addEventListener("click", (evento) => {
            const tooltipMotivo = evento.target.nextElementSibling;
            if (tooltipMotivo) {
                tooltipMotivo.classList.toggle("activo");
            }
        });
    });