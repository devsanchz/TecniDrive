// --- ELEMENTOS CONTENEDORES DE VISTAS ---
const panelNotificaciones = document.getElementById("panelNotificaciones");
const panelConfiguracion = document.getElementById("panelConfiguracion");

// --- ELEMENTOS DE INTERACCIÓN DE NOTIFICACIONES ---
const casillasNoti = document.querySelectorAll(".casilla-noti:not(.completa)"); // Selecciona todas las notificaciones normales
const notiCompleta = document.getElementById("notiCompleta");
const btnOcultarDetalle = document.querySelector(".btn-ocultar-detalle");
const menusContextuales = document.querySelectorAll(".opciones-boton");
const btnTresPuntos = document.querySelectorAll(".btn-menu-contextual");

// --- ELEMENTOS DE CAMBIO DE PANEL ---
const botonesAbrirConfig = document.querySelectorAll(".abrir-config");
const btnVolverNotificaciones = document.getElementById("volverNotis");

// --- COMPONENTES COLAPSABLES (ACORDEÓN CONFIGURACIÓN) ---
const itemsConfiguracion = document.querySelectorAll(".notification-item");


/* ==========================================================================
   1. MANEJO DE MENÚS CONTEXTUALES (TRES PUNTOS) - CORREGIDO
   ========================================================================== */

btnTresPuntos.forEach((boton) => {
    boton.addEventListener("click", (evento) => {
        evento.stopPropagation(); // Evita abrir la notificación completa al dar clic a los puntos
        
        // CORREGIDO: Busca el menú de opciones dentro del mismo contenedor del botón de forma segura
        const contenedorBotones = boton.closest(".botones");
        const menuActual = contenedorBotones ? contenedorBotones.querySelector(".opciones-boton") : null;
        
        menusContextuales.forEach((menu) => {
            if (menu !== menuActual) menu.classList.remove("activo");
        });

        if (menuActual) {
            menuActual.classList.toggle("activo");
        }
    });
});

menusContextuales.forEach((menu) => {
    menu.addEventListener("click", (evento) => {
        evento.stopPropagation();
    });
});

document.addEventListener("click", () => {
    menusContextuales.forEach((menu) => menu.classList.remove("activo"));
});


/* ==========================================================================
   2. FLUJO DE DETALLE DE NOTIFICACIÓN (EXPANDIR / COLAPSAR) - CORREGIDO
   ========================================================================== */

// CORREGIDO: Ahora funciona para CUALQUIER casilla de notificación normal, no solo la primera
casillasNoti.forEach((casilla) => {
    if (notiCompleta) {
        casilla.addEventListener("click", () => {
            // Ocultamos la lista completa de notificaciones y abrimos el detalle detallado
            casilla.classList.add("hidden");
            notiCompleta.style.display = "flex";
        });
    }
});

if (notiCompleta && btnOcultarDetalle) {
    btnOcultarDetalle.addEventListener("click", (evento) => {
        evento.stopPropagation(); 
        notiCompleta.style.display = "none";
        
        // Removemos el estado oculto de todas las casillas para que vuelvan a aparecer en la lista
        casillasNoti.forEach(casilla => casilla.classList.remove("hidden"));
    });
}


/* ==========================================================================
   3. INTERCAMBIO DE PANELES PRINCIPALES (NOTIFICACIONES <-> CONFIGURACIÓN)
   ========================================================================== */

botonesAbrirConfig.forEach((enlace) => {
    enlace.addEventListener("click", (evento) => {
        evento.stopPropagation(); // Evita que se disparen eventos de las tarjetas padre
        if (panelNotificaciones) panelNotificaciones.classList.add("hidden");
        if (panelConfiguracion) panelConfiguracion.classList.remove("hidden");
    });
});

if (btnVolverNotificaciones) {
    btnVolverNotificaciones.addEventListener("click", () => {
        if (panelConfiguracion) panelConfiguracion.classList.add("hidden");
        if (panelNotificaciones) panelNotificaciones.classList.remove("hidden");
    });
}


/* ==========================================================================
   4. COMPORTAMIENTO DE ACORDEÓN (CONFIGURACIÓN DE ALERTAS)
   ========================================================================== */

itemsConfiguracion.forEach((item) => {
    const botonDesplegar = item.querySelector(".toggle-button");
    const contenidoInterno = item.querySelector(".notification-content");
    const iconoFlecha = botonDesplegar ? botonDesplegar.querySelector("i") : null;

    if (botonDesplegar && contenidoInterno) {
        botonDesplegar.addEventListener("click", () => {
            const tieneClaseHidden = contenidoInterno.classList.toggle("hidden");
            
            if (iconoFlecha) {
                if (tieneClaseHidden) {
                    iconoFlecha.className = "bi bi-caret-down-fill";
                } else {
                    iconoFlecha.className = "bi bi-caret-up-fill";
                }
            }
        });
    }
});