<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>propietario puntuacion</title>
   <link rel="stylesheet" href="pt_calificacion.css">
       <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!------------------------------------PPANEL DE ADMINISTRADOR-------------------------------------------------------------------------------------------------------->
<div class="sidebar">
  <ul>
    <li><a href=""><span class="icon"><i class="bi bi-list"></i></span><span class="text">Panel de usuario</span></a></li>
    <li><a href="panel_propietario.html"><span class="icon"><i class="bi bi-file-person-fill"></i></span><span class="text">Mi perfil</span></a></li>
    <li><a href="pt_vehiculo.html"><span class="icon"><i class="bi bi-car-front-fill"></i></span><span class="text">Vehículos</span></a></li>
    <li><a href="pt_taller.html"><span class="icon"><i class="bi bi-tools"></i></span><span class="text">Talleres</span></a></li>
      <li><a href="pt_citas.html"><span class="icon"><i class="bi bi-calendar2-plus"></i></span><span class="text">Cita Agendada</span></a></li>
        <li><a href="pt_calificacion.html"><span class="icon"><i class="bi bi-star-half"></i></span><span class="text">Mis puntuaciones</span></a></li>
    <li><a href="pt_notificaciones.html"><span class="icon"><i class="bi bi-bell-fill"></i></span><span class="text">Notificaciones</span></a></li>
    <li><a href="index.html"><span class="icon"><i class="bi bi-box-arrow-left"></i></span><span class="text">Cerrar sesión</span></a></li>
  </ul>
</div>


<!----------------parte del titulo de modo -->
<div class="titulos">
  <h1>Mis Puntuaciones</h1>
  <h5>Revisa las valoraciones que has dado a los talleres</h5>
</div>

<!-- para verlas de acuerdo a dias -->
  <div class="controles">
       <div class="buscador">
  <input type="text" placeholder="Buscar por fecha o taller">
    <i class="bi bi-search"></i>
  </div>
  </div>
 

 <!-- PUNTIACIONES -->
 <section>

    <!-- calificacion 1 -->
  <div class="casilla">

    <div class="taller">
      <p>Calificado el <samp>15/10/2026</samp></p>

      <i class="bi bi-eye"></i>

      <!-- ver -->
      <div class="ver oculto">
        <p>Ir a ver tu calificación en el taller</p>
        <button>Ir</button>
      </div>

      <i class="bi bi-pencil-square btnEditar"></i>
    </div>

    <div class="puntuacion">
      <h1>Maestro Mecánico</h1>

      <div class="calificacion">
        <i class="bi bi-star-fill star" data-value="1"></i>
        <i class="bi bi-star-fill star" data-value="2"></i>
        <i class="bi bi-star-fill star" data-value="3"></i>
        <i class="bi bi-star-fill star" data-value="4"></i>
        <i class="bi bi-star-fill star" data-value="5"></i>

        <span class="score">(5.0)</span>
      </div>
    </div>

    <div class="contenido">
      <p class="texto">
        Excelente servicio, muy rápido y confiable. Recomendado.
      </p>

      <textarea class="input-texto oculto"></textarea>

      <button class="btn-actualizar oculto">Actualizar</button>
    </div>

  </div>
 </section>
</body>
<script>

//  botón ver
document.querySelectorAll(".casilla").forEach(casilla => {
  const btnVer = casilla.querySelector(".bi-eye");
  const ver = casilla.querySelector(".ver");

  btnVer.addEventListener("click", () => {
    ver.classList.toggle("oculto");
  });
});


//  editar calificación
document.querySelectorAll(".casilla").forEach(casilla => {

  const btnEditar = casilla.querySelector(".btnEditar");
  const estrellas = casilla.querySelectorAll(".puntuacion .star");

  const texto = casilla.querySelector(".texto");
  const inputTexto = casilla.querySelector(".input-texto");
  const btnActualizar = casilla.querySelector(".btn-actualizar");
  const score = casilla.querySelector(".score");

  if (!btnEditar || !texto || !inputTexto || !btnActualizar || !score) return;

  let editando = false;
  let valorActual = 5;

  // activar edición
  btnEditar.addEventListener("click", () => {
    editando = !editando;

    if (editando) {
      inputTexto.value = texto.textContent;

      texto.classList.add("oculto");
      inputTexto.classList.remove("oculto");
      btnActualizar.classList.remove("oculto");

      estrellas.forEach(star => {
        star.style.cursor = "pointer";
        star.addEventListener("click", seleccionar);
      });

    } else {
      salirEdicion();
    }
  });

  // seleccionar estrellas
  function seleccionar(e) {
    valorActual = e.target.dataset.value;

    estrellas.forEach(s => {
      s.style.color = s.dataset.value <= valorActual ? "#f1c40f" : "#ccc";
    });

    //  actualizar número en tiempo real
    score.textContent = "(" + valorActual + ".0)";
  }

  // guardar cambios
  btnActualizar.addEventListener("click", () => {
    texto.textContent = inputTexto.value;

    //  asegurar que también actualiza al guardar
    score.textContent = "(" + valorActual + ".0)";

    salirEdicion();
  });

  // salir de edición
  function salirEdicion() {
    editando = false;

    texto.classList.remove("oculto");
    inputTexto.classList.add("oculto");
    btnActualizar.classList.add("oculto");

    estrellas.forEach(star => {
      star.style.cursor = "default";
      star.removeEventListener("click", seleccionar);
    });
  }

});

</script>
</html>