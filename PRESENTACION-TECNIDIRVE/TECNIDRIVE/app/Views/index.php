 <?php echo $this->extend('Estructura/diseño');?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/principal.css') ?>">
<?= $this->endSection() ?>


 <?php echo $this->section('contenido')?> 
   <!-- ==============================
       BARRA DE NAVEGACIÓN (Sidebar)
  ============================== -->
  <header class="sidebar-menu">
    <ul class="navbar__links">
      <li><a href="#glue-grid"><i class="fas fa-home" aria-hidden="true"></i> Inicio</a></li>
      <li><a href="#video"><i class="bi bi-camera-video-fill" aria-hidden="true"></i> Vista</a></li>
      <li><a href="#funcionalidades"><i class="fas fa-cogs" aria-hidden="true"></i> Funciones</a></li>
      <li><a href="#beneficios"><i class="fas fa-chart-bar" aria-hidden="true"></i> Beneficios</a></li>
      <li><a href="#pie"><i class="fas fa-envelope" aria-hidden="true"></i> Contacto</a></li>
    </ul>
  </header>

  <!-- ==============================
       CONTENIDO PRINCIPAL
  ============================== -->

 
  

  <main class="main-content">

    <!-- HERO: Encabezado principal -->
    <div class="glue-grid" id="glue-grid">
      <div class="glue-grid__col glue-grid__col--span-1-md"></div>

      <div class="glue-grid__col glue-grid__col--span-10-md glue-grid__col--span-4-sm">
        <div class="ion-hero-2up">

          <!-- Texto del hero -->
          <!-- MODIFICADO: añadida clase hero-animate-text para la animación de entrada -->
          <div class="ion-hero-2up__copy-container hero-animate-text">
            <div class="hero__badge" aria-label="Plataforma verificada">
              <i class="bi bi-shield-check" aria-hidden="true"></i>
              Plataforma de gestión automotriz
            </div>

            <h1 class="plexi-hero__headline titulo">
              Gestiona talleres<br>y Vehículos con<br><span>total control</span>
            </h1>

            <p class="plexi-hero__description descripcion">
              <span class="tecni">Tecni </span><span class="drive">Drive</span>
              centraliza la administración de vehículos, talleres, citas, calificaciones
              y reportes en una sola plataforma clara y eficiente.
            </p>

            <div class="plexi-hero__cta">
              <div class="plexi-button__container">
                <a class="registro" href="<?= site_url('autentificar/registro') ?>">Registrate gratis</a>
              </div>
            </div>
          </div>

          <!-- Imagen del hero -->
          <!-- MODIFICADO: añadida clase hero-animate-image para la animación de entrada -->
          <div class="ion-hero-2up__image-container hero-animate-image">
            <picture>
<img src="<?= base_url('assets/img/icono.png') ?>" alt="Icono">
            </picture>
          </div>

        </div>
      </div>

      <div class="glue-grid__col glue-grid__col--span-1-md">
     
      </div>
    </div>


    <!-- ==============================
         VIDEO PROMOCIONAL
    ============================== -->
    <section class="video-section" id="video">
      <div class="container">

        <div class="how__head">
          <span class="section-label">Video demostrativo</span>
          <h2 class="section-title">Conoce nuestra plataforma</h2>
          <p class="section-sub">
            Descubre cómo gestionar tus vehículos, recibir recordatorios importantes
            y encontrar talleres de manera rápida y sencilla.
            "Tu vehículo al día, tu mente en la vía".
          </p>
        </div>

        <!-- AÑADIDO: wrapper decorativo que enmarca el video con más presencia visual -->
        <div class="video-frame">
          <div class="x_wd_video_homepage">
            <video id="miVideo" autoplay loop muted playsinline>
               <source src="<?= base_url('assets/videos/introduccion.mp4') ?>" type="video/mp4">
            </video>

            <div class="video-controls">
              <button id="playPauseBtn" class="x_wd_btn_play_pause" aria-label="Pausar o reproducir video">
                <i id="playPauseIcon" class="bi bi-pause-fill" aria-hidden="true"></i>
              </button>
              <input
                id="progressBar"
                class="x_wd_progress_bar"
                type="range"
                min="0"
                max="100"
                value="0"
                aria-label="Progreso del video"
              />
            </div>
          </div>
        </div>

      </div>
    </section>


    <!-- ==============================
         FUNCIONALIDADES
    ============================== -->
    <section class="features" id="funcionalidades">
      <div class="container">

        <div class="how__head">
          <span class="section-label">Lo que puedes hacer</span>
          <h2 class="section-title">Funcionalidades principales</h2>
          <p class="section-sub">Administra recordatorios vehiculares y promociona tus talleres desde una sola plataforma.</p>
        </div>

        <div class="features__grid">

          <article class="feat-card">
            <div class="feat-icon" aria-hidden="true"><i class="bi bi-person-vcard"></i></div>
            <h3>Gestión de vehículos</h3>
            <p>Registra y administra los datos de tus vehículos de forma centralizada y segura.</p>
          </article>

          <article class="feat-card">
            <div class="feat-icon orange" aria-hidden="true"><i class="bi bi-shop-window"></i></div>
            <h3>Administración de talleres</h3>
            <p>Impulsa tu taller gestionando citas, servicios e información de tu negocio desde un solo lugar.</p>
          </article>

          <article class="feat-card">
            <div class="feat-icon" aria-hidden="true"><i class="bi bi-bell-fill"></i></div>
            <h3>Recordatorios y notificaciones</h3>
            <p>Mantente al día con las fechas de vencimiento de licencias, SOAT y revisión tecnomecánica mediante alertas oportunas.</p>
          </article>

          <article class="feat-card">
            <div class="feat-icon orange" aria-hidden="true"><i class="fa-solid fa-screwdriver-wrench"></i></div>
            <h3>Búsqueda de talleres</h3>
            <p>Encuentra talleres según los servicios que necesita tu vehículo y accede fácilmente a contacto, horarios y calificaciones.</p>
          </article>

          <article class="feat-card">
            <div class="feat-icon" aria-hidden="true"><i class="bi bi-bar-chart-line"></i></div>
            <h3>Reportes y seguimiento</h3>
            <p>Consulta estadísticas e indicadores del sistema para analizar la actividad de propietarios, talleres y vehículos registrados.</p>
          </article>

        </div>
      </div>
    </section>


    <!-- ==============================
         BENEFICIOS
    ============================== -->
    <section class="benefits" id="beneficios">
      <div class="container">
        <div class="benefits__inner">

          <!-- Panel visual de métricas -->
          <div class="benefits__visual" role="img" aria-label="Métricas de eficiencia de TECNIDRIVE">
            <div class="bv-title">Eficiencia operativa</div>

            <div class="bv-row">
              <span class="bv-label">Tiempo ahorrado</span>
              <div class="bv-bar-wrap"><div class="bv-bar-fill" style="width: 82%"></div></div>
              <span class="bv-value">82%</span>
            </div>

            <div class="bv-row">
              <span class="bv-label">Datos centralizados</span>
              <div class="bv-bar-wrap"><div class="bv-bar-fill" style="width: 95%"></div></div>
              <span class="bv-value">95%</span>
            </div>

            <div class="bv-row">
              <span class="bv-label">Satisfacción del usuario</span>
              <div class="bv-bar-wrap"><div class="bv-bar-fill" style="width: 91%"></div></div>
              <span class="bv-value">80%</span>
            </div>

            <!-- Plataformas soportadas -->
            <div class="bv-platforms">
              <div class="bv-platforms__label">Plataformas soportadas</div>
              <div class="bv-platforms__icons">
                <span><i class="bi bi-display" aria-hidden="true"></i> Desktop</span>
                <span><i class="bi bi-tablet" aria-hidden="true"></i> Tablet</span>
                <span><i class="bi bi-phone" aria-hidden="true"></i> Móvil</span>
              </div>
            </div>
          </div>

          <!-- Lista de beneficios -->
          <div class="benefits__list-container">
            <span class="section-label">Por qué elegirnos</span>
            <h2 class="section-title">Beneficios que marcan la diferencia</h2>

            <div class="benefits__list">

              <div class="benefit-item">
                <div class="benefit-icon" aria-hidden="true"><i class="bi bi-database-check"></i></div>
                <div class="benefit-text">
                  <h4>Información centralizada</h4>
                  <p>Todos los datos de vehículos, talleres y citas en un solo panel accesible desde cualquier dispositivo.</p>
                </div>
              </div>

              <div class="benefit-item">
                <div class="benefit-icon or" aria-hidden="true"><i class="bi bi-lightning-charge"></i></div>
                <div class="benefit-text">
                  <h4>Fácil administración</h4>
                  <p>Interfaz intuitiva diseñada para que cualquier usuario pueda operar sin capacitación extensa.</p>
                </div>
              </div>

              <div class="benefit-item">
                <div class="benefit-icon" aria-hidden="true"><i class="bi bi-diagram-3"></i></div>
                <div class="benefit-text">
                  <h4>Seguimiento organizado</h4>
                  <p>Historial completo de citas y servicios por propietario y taller para un seguimiento preciso.</p>
                </div>
              </div>

              <div class="benefit-item">
                <div class="benefit-icon or" aria-hidden="true"><i class="bi bi-clock-history"></i></div>
                <div class="benefit-text">
                  <h4>Ahorro de tiempo</h4>
                  <p>Automatiza tareas repetitivas y reduce el tiempo de gestión administrativa hasta en un 80%.</p>
                </div>
              </div>

              <div class="benefit-item">
                <div class="benefit-icon" aria-hidden="true"><i class="bi bi-file-earmark-bar-graph"></i></div>
                <div class="benefit-text">
                  <h4>Reportes rápidos</h4>
                  <p>Genera informes en segundos con datos actualizados para tomar mejores decisiones.</p>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </section>


    <!-- ==============================
         CÓMO FUNCIONA
    ============================== -->
    <section class="how" id="como-funciona">
      <div class="container">

        <div class="how__head">
          <span class="section-label">Proceso simple</span>
          <h2 class="section-title">¿Cómo funciona?</h2>
          <p class="section-sub">Descubre cómo propietarios y talleres aprovechan la plataforma en solo tres sencillos pasos.</p>

          <!-- Selector de rol -->
          <div class="rol" role="group" aria-label="Seleccionar tipo de usuario">
            <button id="btn-propietario" class="propietario active">Propietario</button>
            <button id="btn-mecanico" class="mecanico">Mecánico</button>
          </div>
        </div>

        <!-- Pasos para Propietario -->
        <div class="how__steps" id="pasos-propietario">
          <div class="step-card">
            <div class="step-num" aria-label="Paso 1">01</div>
            <h3>Regístrate y agrega tu vehículo</h3>
            <p>Crea tu cuenta, registra los datos de tu vehículo y mantén actualizada la información de licencias, SOAT y revisión tecnomecánica.</p>
          </div>
          <div class="step-card">
            <div class="step-num" aria-label="Paso 2">02</div>
            <h3>Encuentra y agenda servicios</h3>
            <p>Busca talleres según las necesidades de tu vehículo, consulta sus servicios y horarios, y solicita citas de manera rápida.</p>
          </div>
          <div class="step-card">
            <div class="step-num" aria-label="Paso 3">03</div>
            <h3>Recibe recordatorios y consulta historial</h3>
            <p>Obtén notificaciones sobre próximos vencimientos y revisa el historial de servicios realizados a cada vehículo.</p>
          </div>
        </div>

        <!-- Pasos para Mecánico (oculto por defecto) -->
        <div class="how__steps is-hidden" id="pasos-mecanico">
          <div class="step-card">
            <div class="step-num meca" aria-label="Paso 1">01</div>
            <h3>Regístrate y registra tu taller</h3>
            <p>Crea el perfil de tu taller, agrega su ubicación, horarios de atención y los servicios que ofreces a los clientes.</p>
          </div>
          <div class="step-card">
            <div class="step-num meca" aria-label="Paso 2">02</div>
            <h3>Gestiona citas y servicios</h3>
            <p>Recibe solicitudes de reserva, organiza las citas y lleva el seguimiento de los servicios realizados a cada vehículo.</p>
          </div>
          <div class="step-card">
            <div class="step-num meca" aria-label="Paso 3">03</div>
            <h3>Da seguimiento a los servicios</h3>
            <p>Consulta el historial de atenciones, actualiza el estado de los trabajos y recibe valoraciones de tus clientes.</p>
          </div>
        </div>

      </div>
    </section>


    <!-- ==============================
         ESTADÍSTICAS
    ============================== -->
    <section class="stats" id="estadisticas" aria-label="Estadísticas de la plataforma">
      <div class="container">
        <div class="stats__grid">

          <div class="stat-item" aria-label="Propietarios registrados: más de 3400">
            <div class="stat-item__num"> php <span>+</span></div>
            <div class="stat-item__label">Propietarios registrados</div>
          </div>

          <div class="stat-item" aria-label="Talleres activos: 87">
            <div class="stat-item__num" >php<span>+</span></div>
            <div class="stat-item__label">Talleres activos</div>
          </div>

          <div class="stat-item" aria-label="Citas realizadas: más de 12400">
            <div class="stat-item__num" >php<span>+</span></div>
            <div class="stat-item__label">Citas realizadas</div>
          </div>

          <div class="stat-item" aria-label="Reportes generados: 520">
            <div class="stat-item__num">php<span>+</span></div>
            <div class="stat-item__label">Reportes de calidad</div>
          </div>

        </div>
      </div>
    </section>


    <!-- ==============================
         MISIÓN Y VISIÓN
    ============================== -->
    <section class="mission" id="mision">
      <div class="container">

        <div class="mission__head">
          <span class="section-label">Nuestros principios</span>
          <h2 class="section-title">Misión y Visión</h2>
          <p class="section-sub">Los valores que guían el desarrollo y evolución de TECNIDRIVE.</p>
        </div>

        <div class="mission__grid">

          <div class="mv-card mv-card--blue">
            <div class="mv-card__stripe" aria-hidden="true"></div>
            <div class="mv-card__icon" aria-hidden="true"><i class="bi bi-bullseye"></i></div>
            <h3>Nuestra Misión</h3>
            <p>
              Brindar a propietarios de vehículos y talleres automotrices una plataforma digital
              que permita gestionar información vehicular, recibir recordatorios oportunos de documentos
              y mantenimientos, llevar un historial de servicios y facilitar la interacción entre
              clientes y talleres de manera eficiente y segura.
            </p>
          </div>

          <div class="mv-card mv-card--orange">
            <div class="mv-card__stripe" aria-hidden="true"></div>
            <div class="mv-card__icon" aria-hidden="true"><i class="bi bi-eye"></i></div>
            <h3>Nuestra Visión</h3>
            <p>
              Ser la plataforma líder en gestión automotriz de América Latina para 2030,
              reconocida por su innovación tecnológica, facilidad de uso y el impacto positivo
              que genera en la digitalización del sector de talleres mecánicos y de servicios
              vehiculares en la región.
            </p>
          </div>

        </div>
      </div>
    </section>

  </main>


  <!-- ==============================
       PIE DE PÁGINA
  ============================== -->
  <footer>
    <div class="container" id="pie">
      <div class="footer__grid">

        <!-- Marca -->
        <div class="footer__brand">
          <div class="navbar__logo">
            <span class="logo-text">TECNI<span>DRIVE</span></span>
          </div>
          <p>Plataforma de gestión automotriz para propietarios y talleres. Centraliza, administra y optimiza tu operación.</p>
        </div>

        <!-- Plataforma -->
        <div class="footer__col">
          <h4>Plataforma</h4>
          <ul>
            <li><a href="#funcionalidades">Funcionalidades</a></li>
            <li><a href="#beneficios">Beneficios</a></li>
            <li><a href="#como-funciona">Cómo funciona</a></li>
            <li><a href="#estadisticas">Estadísticas</a></li>
          </ul>
        </div>

        <!-- Empresa -->
        <div class="footer__col">
          <h4>Empresa</h4>
          <ul>
            <li><a href="#mision">Misión y Visión</a></li>
            <li><a href="#contacto">Contacto</a></li>
            <li><a href="#">Política de privacidad</a></li>
            <li><a href="#">Términos de uso</a></li>
          </ul>
        </div>

        <!-- Soporte -->
        <div class="footer__col">
          <h4>Soporte</h4>
          <ul>
            <li><a href="#">Centro de ayuda</a></li>
            <li><a href="#">Documentación</a></li>
            <li><a href="#">Estado del sistema</a></li>
            <li><a href="#">Reportar un problema</a></li>
          </ul>
        </div>

      </div>

      <!-- Pie inferior -->
      <div class="footer__bottom">
        <p>&copy; 2026 TecniDrive. Todos los derechos reservados.</p>
        <div class="footer__socials" aria-label="Redes sociales">
          <a href="#" aria-label="Facebook de TECNIDRIVE"><i class="bi bi-facebook" aria-hidden="true"></i></a>
          <a href="#" aria-label="Instagram de TECNIDRIVE"><i class="bi bi-instagram" aria-hidden="true"></i></a>
          <a href="#" aria-label="LinkedIn de TECNIDRIVE"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
          <a href="#" aria-label="Twitter de TECNIDRIVE"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
        </div>
      </div>

    </div>
  </footer>
<?php echo $this->endSection()?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/principal.js') ?>"></script>
<?= $this->endSection() ?>