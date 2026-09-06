  //PARTE DE OCULTAR Y APARECERR LAS DOS  COSAS LICENCIA Y TABLA DE VEHICULOS
    function procesarFormulario(event) {

  event.preventDefault();

  // ocultar formulario completo
  document.querySelector(".tarjeta-autenticacion").style.display = "none";

  // mostrar dashboard
  document.getElementById("dashboardvehiculos")
    .classList.remove("hidden");

}

//PARTE DE CATEGORIAS DE LICENCIA PONER
    let contadorCategorias = 1;

    // Pasar del panel de bienvenida al formulario
    function irAFormulario() {
      document.getElementById('panelInicial').classList.add('hidden');
      document.getElementById('formLicencia').classList.remove('hidden');
    }

    // Agregar dinámicamente filas de categorías perfectamente alineadas
    function agregarCategoria() {
      contadorCategorias++;
      const contenedor = document.getElementById('contenedorCategorias');
      const div = document.createElement('div');
      div.className = 'categoria-item';
      div.id = 'categoria-' + contadorCategorias;
      
      div.innerHTML = `
        <div class="fila-doble">
          <div class="campo-bloque">
            <label>Categoría</label>
            <input type="text" name="categoria[]" placeholder="Ej: B2" maxlength="3" required>
          </div>
          <div class="campo-bloque">
            <label>Fecha vigencia</label>
            <input type="date" name="fecha[]" required>
          </div>
          <button type="button" class="btn-eliminar-fila" onclick="eliminarCategoria(${contadorCategorias})" title="Eliminar fila">
            <i class="bi bi-trash3-fill"></i>
          </button>
        </div>
      `;
      contenedor.appendChild(div);
    }

    // Eliminar la fila seleccionada
    function eliminarCategoria(id) {
      const item = document.getElementById('categoria-' + id);
      if (item) item.remove();
    }

   
    //PARTE DE LA TABLA DE VEHICULOS LERO LERO
    document.addEventListener("DOMContentLoaded", () => {
      // Desplegar Licencia
      const btnLicencia = document.querySelector(".btn-desplegar");
      const infoLicencia = document.querySelector(".info-licencia");
      if(btnLicencia && infoLicencia) {
        btnLicencia.addEventListener("click", () => infoLicencia.classList.toggle("hidden"));
      }

      // Desplegar Formulario Vehículo
      const btnForm = document.querySelector(".btn-desplegar-form");
      const formVehiculo = document.querySelector(".formulario-vehiculo");
      if(btnForm && formVehiculo) {
        btnForm.addEventListener("click", () => formVehiculo.classList.toggle("hidden"));
      }

      // Desplegar Documentos en la Tabla
      const btnDocs = document.querySelector(".btn-ver-docs");
      const contenedorDocs = document.querySelector(".contenedor-documentos");
      if(btnDocs && contenedorDocs) {
        btnDocs.addEventListener("click", () => contenedorDocs.classList.toggle("hidden"));
      }

      // --- LÓGICA DE EDICIÓN DE LA LICENCIA EN TIEMPO REAL ---
      let enModoEdicionLicencia = false;
      const btnEditarLicencia = document.querySelector('.Licencia-editar');
      const btnAgregarC = document.getElementById('btn-agregar-cate-edit');

      if(btnEditarLicencia) {
        btnEditarLicencia.addEventListener('click', function() {
          const listaCates = document.querySelector('.lista-categorias');
          const divViges = document.querySelector('.viges');
          const pNumer = document.querySelector('.numer .si');

          if (!enModoEdicionLicencia) {
            // Activar modo edición
            enModoEdicionLicencia = true;
            btnEditarLicencia.textContent = 'Guardar';
            if (btnAgregarC) btnAgregarC.classList.remove('hidden');

            // Convertir categorías a inputs inline
            listaCates.querySelectorAll('li').forEach(li => {
              if (!li.querySelector('input')) {
                const txt = li.textContent.trim();
                li.innerHTML = `<input type="text" value="${txt}" style="width:100%; height:100%; background:transparent; border:none; color:white; text-align:center; font-family:inherit; font-size:inherit; font-weight:inherit; outline:none;">`;
              }
            });

            // Convertir vigencias a inputs inline de tipo DATE
            divViges.querySelectorAll('.texto-detalle').forEach(p => {
              if (!p.querySelector('input')) {
                const txt = p.textContent.trim();
                const valorFecha = (txt === 'php') ? '' : txt;
                p.innerHTML = `<input type="date" value="${valorFecha}" style="width:100%; height:100%; background:transparent; border:none; color:inherit; text-align:center; font-family:inherit; font-size:11px; outline:none;">`;
              }
            });

            // Convertir número de licencia a input inline
            if (pNumer && !pNumer.querySelector('input')) {
              const txt = pNumer.textContent.trim();
              pNumer.innerHTML = `<input type="text" value="${txt}" style="width:100%; height:100%; background:transparent; border:none; color:inherit; text-align:center; font-family:inherit; font-size:inherit; outline:none;">`;
            }
          } else {
            // Simulación visual de guardado
            enModoEdicionLicencia = false;
            btnEditarLicencia.textContent = 'Editar';
            if (btnAgregarC) btnAgregarC.classList.add('hidden');

            listaCates.querySelectorAll('li').forEach(li => {
              const input = li.querySelector('input');
              if (input) li.textContent = input.value || 'php';
            });

            divViges.querySelectorAll('.texto-detalle').forEach(p => {
              const input = p.querySelector('input');
              if (input) p.textContent = input.value || 'php';
            });

            if (pNumer) {
              const input = pNumer.querySelector('input');
              if (input) pNumer.textContent = input.value;
            }
          }
        });
      }

      // Añadir nueva casilla visual en modo edición (Categoría + input date)
      if(btnAgregarC) {
        btnAgregarC.addEventListener('click', function() {
          const listaCates = document.querySelector('.lista-categorias');
          const divViges = document.querySelector('.viges');

          const nuevoLi = document.createElement('li');
          nuevoLi.innerHTML = `<input type="text" value="" placeholder="A2" style="width:100%; height:100%; background:transparent; border:none; color:white; text-align:center; font-family:inherit; font-size:inherit; font-weight:inherit; outline:none;">`;
          listaCates.appendChild(nuevoLi);

          const nuevoP = document.createElement('p');
          nuevoP.className = 'texto-detalle';
          nuevoP.innerHTML = `<input type="date" value="" style="width:100%; height:100%; background:transparent; border:none; color:inherit; text-align:center; font-family:inherit; font-size:11px; outline:none;">`;
          divViges.appendChild(nuevoP);
        });
      }

      // --- LÓGICA DE TEXTO DINÁMICO (GUARDAR / EDITAR) EN DOCUMENTOS ---
      const btnGuardarDocs = document.querySelector(".btn-guardar-docs");
      if(btnGuardarDocs) {
        btnGuardarDocs.addEventListener("click", () => {
          if (btnGuardarDocs.textContent === "Guardar") {
            btnGuardarDocs.textContent = "Editar";
          } else {
            btnGuardarDocs.textContent = "Guardar";
          }
        });
      }

      // --- LÓGICA FORMULARIO MOTIVO DESACTIVAR ---
      const btnRechazar = document.querySelector(".btn-rechazar");
      const btnAprobar = document.querySelector(".btn-aprobar");
      const formMotivo = document.getElementById("form-motivo");
      
      if(btnRechazar && formMotivo) {
        btnRechazar.addEventListener("click", () => {
          formMotivo.classList.toggle("hidden");
        });
      }
      
      if(btnAprobar && formMotivo) {
        formMotivo.classList.add("hidden");
      }

      // Cerrar formulario visual de motivo al presionar Enviar
      if(formMotivo) {
        const btnEnviarMotivo = formMotivo.querySelector("button");
        if(btnEnviarMotivo) {
          btnEnviarMotivo.addEventListener("click", () => {
            formMotivo.classList.add("hidden");
          });
        }
      }
    });