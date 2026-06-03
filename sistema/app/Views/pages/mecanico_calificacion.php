<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas del mecanico</title>
 <link rel="stylesheet" href="mecanico_calificacion.css">
       <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!------------------------------------PPANEL DE ADMINISTRADOR-------------------------------------------------------------------------------------------------------->
<div class="sidebar">
  <ul>
    <li><a href=""><span class="icon"><i class="bi bi-list"></i></span><span class="text">Panel de usuario</span></a></li>
    <li><a href="panel_mecanico.html"><span class="icon"><i class="bi bi-file-person-fill"></i></span><span class="text">Mi perfil</span></a></li>
    <li><a href="mecanico_taller.html"><span class="icon"><i class="bi bi-tools"></i></span><span class="text">Tu taller</span></a></li>
      <li><a href="mecanico_citas.html"><span class="icon"><i class="bi bi-calendar2-check"></i></span><span class="text">Agenda de citas</span></a></li>
      <li><a href="Mecanico_calificacion.html"><span class="icon"><i class="bi bi-star-half"></i></span><span class="text">Calificaciones</span></a></li>
    <li><a href="index.html"><span class="icon"><i class="bi bi-box-arrow-left"></i></span><span class="text">Cerrar sesión</span></a></li>
  </ul>
</div>


<!-- ENCABEZADO DEL PANEL -->
<div class="titulos">
  <h1 class="titulo">Calificaciones y reseñas</h1>
  <h4>Revisa las puntuaciones de los clientes sobre tu taller</h4>
</div>


<!-- MONITOREO -->
   <section>
    <header>
      <!-- ver cuantas hay y prometio -->
      <div class="numeros">
        <div class="cantidad">
          <span class="num">3.0</span><br>
          <span>Puntaje Promedio</span>
        </div>
        <div class="cantidad">
          <span class="num">2</span><br>
          <span>Nuevas Hoy</span>
        </div>
         <div class="cantidad">
          <span class="num">50%</span><br>
          <span>Recomendado</span>
        </div>
      </div>
    </header>
    <!-- buscar  -->
    <div class="controles">
       <div class="buscador">
  <input type="text" placeholder="Buscar por fecha o cliente  ">
    <i class="bi bi-search"></i>
  </div>

  <select name="" id="">
    <option value="">Todos</option>
    <option value="">Con comentario</option>
    <option value="">Solo puntajes</option>
    <option value="">Puntajes altos</option>
     <option value="">Puntajes bajos</option>
  </select>
    </div>
    <!-- TABLA TODDO-->
     <table>
      <!-- encabezado tabla -->
     <tr class="cabeza">
      <th>Cliente</th>
      <th>Puntuación</th>
      <th>Comentario</th>
      <th>Fecha</th>
      <th>Acciones</th>
     </tr>
   
<!-- Como se ven las casillas -->
     <tr class="cuerpo">
      <th class="texto"> <i class="bi bi-person-check"></i>  <span>Pedro Santiago Sanchez</span></th>

      

      <th class="puntuacion">
        <div class="calificacion">  
      <i class="bi bi-star-fill star" data-value="1"></i>
      <i class="bi bi-star-fill star" data-value="2"></i>
      <i class="bi bi-star-fill star" data-value="3"></i>
      <i class="bi bi-star-fill star" data-value="4"></i>
      <i class="bi bi-star-fill star" data-value="5"></i>
      <span id="score">(5.0)</span>
    </div></th>
  
      <th class="comentario">Excelente servicio, muy rápido y confiable. Recomendado.</th>

      <th class="fecha">15/10/2026</th>

     
      <th class="acto"><div class="acciones">
        <button class="rechazar">Vista completada</button>
</div>
</th>

     </tr>

    
<!--casilla 2 -->
     <tr class="cuerpo">
 <th class="texto"> 
     <i class="bi bi-person-check"></i>  <span>Samanta Baron Rodriguez</span>
</th>



<th class="puntuacion">
        <div class="calificacion">  
      <i class="bi bi-star-fill star" data-value="1"></i>
      <i class="bi bi-star-fill star" data-value="2"></i>
      <i class="bi bi-star-fill star" data-value="3"></i>
      <i class="bi bi-star-fill star" data-value="4"></i>
      <i class="bi bi-star-fill star" data-value="5"></i>
      <span id="score">(5.0)</span>
    </div>
</th>
  
<th class="comentario">
 
</th>

<th class="fecha">1/03/2026</th>

     
      <th class="acto">
        <div class="acciones">
        <button class="rechazar">Vista completada</button>   
      </div>
</th>
     </tr>

     </table>

      <!-- PAGINACIÓN -->
        <div class="stats-footer">
            <div>
                <strong>Mostrando 1-1 de 2 calificaciones</strong>
            </div>
            <div class="pagination">
                <button class="page-btn">«</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">»</button>
            </div>
        </div>
    </div>

   </section>
</body>
</html>