const video        = document.getElementById('miVideo');
const progressBar  = document.getElementById('progressBar');
const playPauseBtn = document.getElementById('playPauseBtn');
const playPauseIcon = document.getElementById('playPauseIcon');

/* Actualizar la barra de progreso mientras el video avanza */
video.addEventListener('timeupdate', () => {
  const percent = (video.currentTime / video.duration) * 100;
  progressBar.value = percent || 0;
});

/* Mover el video al punto seleccionado en la barra */
progressBar.addEventListener('input', () => {
  video.currentTime = (progressBar.value / 100) * video.duration;
});

/* Alternar entre reproducir y pausar */
playPauseBtn.addEventListener('click', () => {
  if (video.paused) {
    video.play();
  } else {
    video.pause();
  }
});

/**
 * Actualiza el ícono según el estado del video.
 * @param {boolean} isPlaying - true si el video está reproduciéndose
 */
function updatePlayIcon(isPlaying) {
  playPauseIcon.className = isPlaying ? 'bi bi-pause-fill' : 'bi bi-play-fill';
}

/* Sincronizar el ícono con los eventos nativos del video */
video.addEventListener('play',  () => updatePlayIcon(true));
video.addEventListener('pause', () => updatePlayIcon(false));


/* ============================================================
   2. TABS DE ROL: Cambio entre Propietario y Mecánico
   Muestra los pasos correspondientes al rol seleccionado.
============================================================ */

const btnPropietario   = document.getElementById('btn-propietario');
const btnMecanico      = document.getElementById('btn-mecanico');
const pasosPropietario = document.getElementById('pasos-propietario');
const pasosMecanico    = document.getElementById('pasos-mecanico');

/**
 * Activa un rol y muestra sus pasos, ocultando el otro.
 * @param {'propietario'|'mecanico'} rol - El rol a activar
 */
function activarRol(rol) {
  const esPropietario = rol === 'propietario';

  // Actualizar estado activo de los botones
  btnPropietario.classList.toggle('active', esPropietario);
  btnMecanico.classList.toggle('active', !esPropietario);

  // Mostrar/ocultar los bloques de pasos
  pasosPropietario.classList.toggle('is-hidden', !esPropietario);
  pasosMecanico.classList.toggle('is-hidden', esPropietario);
}

btnPropietario.addEventListener('click', () => activarRol('propietario'));
btnMecanico.addEventListener('click',    () => activarRol('mecanico'));