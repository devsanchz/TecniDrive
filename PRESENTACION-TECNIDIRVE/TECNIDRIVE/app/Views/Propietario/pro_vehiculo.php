 <?php echo $this->extend('Estructura/diseño');?>
 <?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pro_vehiculo.css') ?>">
<?= $this->endSection() ?>

 <?php echo $this->section('contenido')?> 
<?= $this->include('Estructura/menu_pro') ?>

<header class="titulos">
            <h1 class="titulo">Tus Vehículo</h1>
            <h5>Supervisa los vehículos registrados y su documentación vigente</h5>
        </header>


       
  <div class="tarjeta-autenticacion">
    
    <div class="panel-vacio" id="panelInicial">
        <i class="bi bi-person-vcard-fill principal"></i>
      <h1>Aún no tienes licencia ni vehículos registrados</h1>
      <p>Comienza configurando tu perfil registrando tu licencia de conducción.</p>
      <button type="button" class="btn-principal" onclick="irAFormulario()">Ingresar datos</button>
    </div>

    <form id="formLicencia" class="hidden" onsubmit="procesarFormulario(event)">
      <h1>Registro de Licencia</h1>
      
      <label>Número de licencia</label>
      <div class="grupo-input">
        <input type="text" name="nlicencia" placeholder="Ej: 10102345671" maxlength="11">
       
      </div>

      <div id="contenedorCategorias">
        
        <div class="categoria-item" id="categoria-1">
          <div class="fila-doble">
            <div class="campo-bloque">
              <label>Categoría</label>
              <input type="text" name="categoria[]" placeholder="Ej: A2, B1" maxlength="3" >
            </div>
            <div class="campo-bloque">
              <label>Fecha vigencia</label>
              <input type="date" name="fecha[]" >
            </div>
            <button type="button" class="btn-eliminar-fila invisible">
              <i class="bi bi-trash3-fill"></i>
            </button>
          </div>
        </div>

      </div>

      <button type="button" class="btn-accion-secundaria" onclick="agregarCategoria()">
        <i class="bi bi-plus-lg"></i> Agregar otra categoría
      </button>

      <button type="submit" class="btn-principal btn-guardar">Datos listos</button>
    </form>
  </div>


  <main class="dashboard-section hidden" id="dashboardvehiculos">
    <section class="controles">
      
      <div class="tarjeta-licencia">
        <div class="header-licencia">
          <p class="titulo-licencia"><i class="bi bi-person-vcard-fill"></i> Tu licencia</p>
          <button class="btn-desplegar" >
            <i class="bi bi-caret-down-fill"></i>
          </button>
        </div>
        
        <div class="info-licencia hidden">
          <div class="conten">
 <div class="cates">
 <strong>Categorías </strong>
  <ul class="lista-categorias">
            <li>php</li>
            <li>php</li>  
          </ul>
 </div>
 <div class="viges">
 <strong>Fecha de vigencia</strong>
 <p class="texto-detalle">php</p>
 <p class="texto-detalle">php</p>
 </div>
 </div>


 <div class="numer">
     <strong>Número de licencia</strong>
 <p class="texto-detalle si">12345678909</p>
 </div>
          
          <button type="button" class="btn-accion-secundaria hidden" id="btn-agregar-cate-edit" style="margin: 10px auto;">
            <i class="bi bi-plus-lg"></i> Agregar otra categoría
          </button>

          <button class="Licencia-editar">Editar</button>
        </div>
      </div>
        
      <div class="tarjeta-agregar-vehiculo"> 
        <div class="header-licencia">
          <p class="titulo-vehiculo">Agregar Vehículo</p>
          <button class="btn-desplegar btn-desplegar-form">
            <i class="bi bi-caret-down-fill"></i>
          </button>
        </div>

        <div class="formulario-vehiculo hidden">
          <h2>Agregar datos</h2>
          <input type="text" name="placa" placeholder="Placa"> 
          <input type="text" name="marca" placeholder="Marca">
          <input type="text" name="modelo" placeholder="Modelo">
          <input type="text" name="ano" placeholder="Año">
          <select name="tipo">
            <option value="">Tipo de vehículo</option>
            <option value="carro">Carro</option>
            <option value="moto">Moto</option>
          </select>
          <input type="text" placeholder="Servicio (Particular o Público)">
          <button type="submit" class="btn-guardar-vehiculo">Guardar vehículo</button>
        </div>
      </div>
    </section>


<section class="tabla-wrapper">
      <table class="tabla-calificaciones">
        <thead>
          <tr class="cabeza-tabla">
            <th>Datos básicos</th>
            <th>Estado del vehículo</th>
            <th>Documentos</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr class="fila-cuerpo">
            
            <td class="col-datos-basicos">
              <div class="bloque-vehiculo">
                <div class="badge-vehiculo">
                  <i class="fa-solid fa-car"></i>
                  <p class="texto-placa">php</p>
                </div>
                <div class="info-texto-vehiculo">
                  <strong class="nombre-vehiculo">php</strong>
                  <span class="tipo-servicio">Php</span>
                </div>
              </div>
            </td>

            <td class="col-estado-vehiculo">
              <span class="badge-estado activo">Activo</span>
            </td>

           <td class="col-documentos-vehiculo">
  <button class="btn-ver-docs">
    <span>sin documentos aun</span> <i class="bi bi-caret-down-fill"></i>
  </button>
  
  <div class="contenedor-documentos hidden">
    <div class="grupo-input-doc">
      <strong>SOAT</strong>
      <label>Vigencia: <input type="date"></label>
    </div>
    <div class="grupo-input-doc">
      <strong>Tecnomecánica</strong>
      <label>Vigencia: <input type="date"></label>
    </div>
    <button class="btn-guardar-docs">Guardar</button>
  </div>
</td>

            <td class="col-acciones-vehiculo" style="position: relative;">
              <div class="grupo-botones-accion">
                <button class="btn-accion btn-aprobar">Activar</button>
                <button class="btn-accion btn-rechazar">Desactivar</button>
                <form action="" class="hidden" id="form-motivo" style="position: absolute; z-index: 1050; background: #ffffff; border: 1px solid #cbd5e0; padding: 10px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); width: 180px; right: 10px; top: 10px;">
                  <input type="text" placeholder="Motivo" style="margin-bottom: 5px;">
                  <button type="button" class="btn-guardar-docs" style="margin: 0 auto; width: 100%;">Enviar</button>
                </form>
              
                       
              </div>
            </td>

          </tr>          
        </tbody>
      </table>
    </section>
  </main>



<?php echo $this->endSection()?>
 <?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/pro_vehiculo.js') ?>"></script>
<?= $this->endSection() ?>