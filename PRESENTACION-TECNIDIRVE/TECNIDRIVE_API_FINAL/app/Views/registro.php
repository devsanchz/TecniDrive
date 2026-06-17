
<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/registro.css') ?>">
<style>
    /* ── Paso 2: selección de rol ── */
    .paso          { display: none; }
    .paso.activo   { display: block; }

    .roles         { display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem; }

    .card-rol {
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 1rem;
        cursor: pointer;
        transition: border-color .2s;
        display: flex;
        align-items: flex-start;
        gap: .75rem;
    }
    .card-rol input[type="radio"] { margin-top: 3px; accent-color: var(--color-primario, #1a73e8); }
    .card-rol:has(input:checked)  { border-color: var(--color-primario, #1a73e8); background: #f0f6ff; }
    .card-rol.deshabilitada       { opacity: .6; cursor: default; }

    .card-rol .icono { font-size: 1.4rem; }
    .card-rol h2     { margin: 0 0 .25rem; font-size: 1rem; }
    .card-rol p      { margin: 0; font-size: .85rem; color: #555; }
    .card-rol .precio{ font-size: .8rem; color: #888; margin-top: .25rem; }

    /* ── Spinner dentro del botón ── */
    .btn-spinner { display: none; }
    button.cargando .btn-texto  { display: none; }
    button.cargando .btn-spinner{ display: inline; }

    /* ── Alerta general ── */
    .alerta-error {
        background: #fdecea; color: #c0392b;
        border: 1px solid #f5c6cb;
        padding: .75rem 1rem; border-radius: 6px;
        margin-bottom: 1rem; font-size: .9rem;
    }
    .alerta-exito {
        background: #eafaf1; color: #1e8449;
        border: 1px solid #a9dfbf;
        padding: .75rem 1rem; border-radius: 6px;
        margin-bottom: 1rem; font-size: .9rem;
    }
</style>
<?= $this->endSection() ?>



<a class="Volver" id="btnVolver" title="Volver" href="<?= site_url('/') ?>">
    <i class="bi bi-chevron-left"></i>
</a>

<div class="tarjeta-autenticacion" id="registerCard">

    <!-- ── Alerta general (errores de API / éxito) ── -->
    <div id="alertaGeneral" role="alert" style="display:none;"></div>

    <!-- ══════════════════════════════════════════════
         PASO 1 — Datos personales
    ══════════════════════════════════════════════ -->
    <div class="paso activo" id="paso1">

        <h1>Crea tu cuenta</h1>

        <form id="formRegistro" novalidate>

            <!-- Nombres -->
            <label>Nombres</label>
            <div class="fila-doble">

                <div class="campo-bloque">
                    <input type="text" id="primer_nombre" name="primer_nombre"
                           placeholder="Primer nombre" required>
                    <span class="error-campo" id="err_primer_nombre" role="alert"></span>
                </div>

                <div class="campo-bloque">
                    <small class="etiqueta-opcional">Opcional</small>
                    <input type="text" id="segundo_nombre" name="segundo_nombre"
                           placeholder="Segundo nombre">
                </div>

            </div>

            <!-- Apellidos -->
            <label>Apellidos</label>
            <div class="fila-doble">

                <div class="campo-bloque">
                    <input type="text" id="primer_apellido" name="primer_apellido"
                           placeholder="Primer apellido" required>
                    <span class="error-campo" id="err_primer_apellido" role="alert"></span>
                </div>

                <div class="campo-bloque">
                    <input type="text" id="segundo_apellido" name="segundo_apellido"
                           placeholder="Segundo apellido" required>
                    <span class="error-campo" id="err_segundo_apellido" role="alert"></span>
                </div>

            </div>

            <!-- Teléfono -->
            <label>Teléfono</label>
            <div class="grupo-input">
                <input type="tel" id="telefono" name="telefono"
                       placeholder="300 123 4567" required>
                <i class="bi bi-telephone"></i>
            </div>
            <span class="error-campo" id="err_telefono" role="alert"></span>

            <!-- Email -->
            <label>E-mail</label>
            <div class="grupo-input">
                <input type="email" id="email" name="email"
                       placeholder="ejemplo@gmail.com" required>
                <i class="bi bi-envelope"></i>
            </div>
            <span class="error-campo" id="err_email" role="alert"></span>

            <!-- Contraseña -->
            <label>Contraseña</label>
            <div class="grupo-input">
                <input type="password" id="password" name="password"
                       placeholder="Mínimo 8 caracteres" required>
                <i class="bi bi-lock"></i>
            </div>
            <span class="error-campo" id="err_password" role="alert"></span>

            <!-- Botón: avanza al paso 2 (no llama a la API aún) -->
            <button type="submit">Continúa</button>

            <div class="links">
                <a href="<?= site_url('ingreso') ?>">
                    ¿Ya tienes cuenta? Iniciar sesión
                </a>
            </div>

        </form>
    </div><!-- /#paso1 -->

    <!-- ══════════════════════════════════════════════
         PASO 2 — Selección de rol
    ══════════════════════════════════════════════ -->
    <div class="paso" id="paso2">

        <div class="cabeza">
            <h1>¿Qué rol quieres usar en TecniDrive?</h1>
            <p class="subtitulo">Elige el tipo de cuenta que mejor se adapte a ti</p>
        </div>

        <span class="error-campo" id="err_rol" role="alert"></span>

        <div class="roles">

            <!-- Propietario — rol 2 -->
            <label class="card-rol">
                <input type="radio" name="rol" value="2">
                <div>
                    <div class="icono"><i class="bi bi-car-front-fill"></i></div>
                    <h2>Propietario</h2>
                    <p>Gestiona tus vehículos, recuerda documentos, busca talleres y agenda citas.</p>
                </div>
            </label>

            <!-- Mecánico — rol 3 -->
            <label class="card-rol">
                <input type="radio" name="rol" value="3">
                <div>
                    <div class="icono"><i class="bi bi-tools"></i></div>
                    <h2>Mecánico / Taller</h2>
                    <p>Publica tu taller, administra citas y llega a más clientes.</p>
                </div>
            </label>

            <!-- Propietario + Taller — solo visual, abre modal -->
            <label class="card-rol deshabilitada" id="cardPremium">
                <input type="radio" name="rol" value="" disabled>
                <div>
                    <div class="icono"><i class="bi bi-stars"></i></div>
                    <h2>Propietario + Taller</h2>
                    <p>Usa los dos roles al mismo tiempo.</p>
                    <p class="precio">Plan con costo mensual</p>
                </div>
            </label>

        </div>

        <!-- Botón: llama a la API con todos los datos -->
        <button id="btnFinalizar">
            <span class="btn-texto">Finalizar</span>
            <span class="btn-spinner"><i class="bi bi-arrow-repeat"></i> Creando cuenta…</span>
        </button>

    </div><!-- /#paso2 -->

</div><!-- /.tarjeta-autenticacion -->

<!-- Modal Plan Premium -->
<div class="modal" id="modalPlanes">
    <div class="modal-contenido">
        <h2>Plan Propietario + Taller</h2>
        <p>Obtén acceso a los dos roles en una sola cuenta.</p>
        <div class="plan">
            <h3>Plan mensual</h3>
            <div class="valor">$19.900</div>
            <span>Pago mensual</span>
        </div>
        <div class="botones-modal">
            <button class="cerrar" id="btnCerrarModal">Cancelar</button>
            <button>Elegir plan</button>
        </div>
    </div>
</div>


<script>
/* ==========================================================
   registro.js — inlineado para mantener todo en un archivo
   ========================================================== */

// ── URL base de la API ─────────────────────────────────────────────────
const API_URL = '<?= base_url('api/v1') ?>';

// ── Referencias al DOM ─────────────────────────────────────────────────
const paso1        = document.getElementById('paso1');
const paso2        = document.getElementById('paso2');
const formRegistro = document.getElementById('formRegistro');
const btnFinalizar = document.getElementById('btnFinalizar');
const btnVolver    = document.getElementById('btnVolver');
const alertaGen    = document.getElementById('alertaGeneral');
const cardPremium  = document.getElementById('cardPremium');
const modal        = document.getElementById('modalPlanes');

// ── Objeto temporal que guarda los datos del paso 1 ───────────────────
let datosPaso1 = {};

/* ------------------------------------------------------------------
   UTILIDADES
------------------------------------------------------------------ */

/** Muestra un error inline bajo el campo indicado */
function mostrarError(id, mensaje) {
    const el = document.getElementById('err_' + id);
    if (el) el.textContent = mensaje;
}

/** Limpia todos los errores inline */
function limpiarErrores() {
    document.querySelectorAll('.error-campo').forEach(el => el.textContent = '');
}

/** Muestra alerta general (tipo: 'error' | 'exito') */
function mostrarAlerta(mensaje, tipo = 'error') {
    alertaGen.className = tipo === 'exito' ? 'alerta-exito' : 'alerta-error';
    alertaGen.textContent = mensaje;
    alertaGen.style.display = 'block';
}

function ocultarAlerta() {
    alertaGen.style.display = 'none';
}

/* ------------------------------------------------------------------
   VALIDACIÓN PASO 1 (cliente) — espeja las reglas del servidor
------------------------------------------------------------------ */
function validarPaso1(datos) {
    let valido = true;
    limpiarErrores();

    if (!datos.primer_nombre || datos.primer_nombre.length < 2) {
        mostrarError('primer_nombre', 'El primer nombre es obligatorio (mín. 2 caracteres)');
        valido = false;
    }
    if (!datos.primer_apellido || datos.primer_apellido.length < 2) {
        mostrarError('primer_apellido', 'El primer apellido es obligatorio');
        valido = false;
    }
    if (!datos.segundo_apellido || datos.segundo_apellido.length < 2) {
        mostrarError('segundo_apellido', 'El segundo apellido es obligatorio');
        valido = false;
    }
    if (!datos.telefono || !/^\d{7,10}$/.test(datos.telefono)) {
        mostrarError('telefono', 'Teléfono inválido (7-10 dígitos, sin prefijo)');
        valido = false;
    }
    if (!datos.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(datos.email)) {
        mostrarError('email', 'Ingresa un email válido');
        valido = false;
    }
    if (!datos.password || datos.password.length < 8) {
        mostrarError('password', 'La contraseña debe tener mínimo 8 caracteres');
        valido = false;
    }

    return valido;
}

/* ------------------------------------------------------------------
   PASO 1 → PASO 2: guardar datos y mostrar selección de rol
------------------------------------------------------------------ */
formRegistro.addEventListener('submit', function (e) {
    e.preventDefault();
    ocultarAlerta();

    // Recoger valores del formulario
    datosPaso1 = {
        primer_nombre   : document.getElementById('primer_nombre').value.trim(),
        segundo_nombre  : document.getElementById('segundo_nombre').value.trim(),
        primer_apellido : document.getElementById('primer_apellido').value.trim(),
        segundo_apellido: document.getElementById('segundo_apellido').value.trim(),
        telefono        : document.getElementById('telefono').value.replace(/\s/g, ''),
        email           : document.getElementById('email').value.trim(),
        password        : document.getElementById('password').value,
    };

    if (!validarPaso1(datosPaso1)) return;

    // Avanzar al paso 2
    paso1.classList.remove('activo');
    paso2.classList.add('activo');

    // Botón volver retrocede al paso 1 en vez de salir
    btnVolver.href = '#';
    btnVolver.addEventListener('click', volverPaso1, { once: true });
});

function volverPaso1(e) {
    e.preventDefault();
    paso2.classList.remove('activo');
    paso1.classList.add('activo');
    btnVolver.href = '<?= site_url('/') ?>';
    limpiarErrores();
    ocultarAlerta();
}

/* ------------------------------------------------------------------
   PASO 2 → API: enviar registro completo
------------------------------------------------------------------ */
btnFinalizar.addEventListener('click', async function () {
    ocultarAlerta();

    // Validar que se seleccionó un rol
    const rolSeleccionado = document.querySelector('input[name="rol"]:not([disabled]):checked');
    if (!rolSeleccionado) {
        document.getElementById('err_rol').textContent = 'Debes elegir un rol para continuar';
        return;
    }

    // Armar payload completo
    const payload = {
        ...datosPaso1,
        rol     : parseInt(rolSeleccionado.value),
        telefono: parseInt(datosPaso1.telefono),
    };

    // Estado de carga
    btnFinalizar.classList.add('cargando');
    btnFinalizar.disabled = true;

    try {
        const res  = await fetch(`${API_URL}/registro`, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body   : JSON.stringify(payload),
        });

        const json = await res.json();

        if (res.status === 201) {
            // ── Éxito: guardar token y redirigir ──────────────────────
            localStorage.setItem('td_token',  json.token);
            localStorage.setItem('td_usuario', JSON.stringify(json.usuario));

            mostrarAlerta('¡Cuenta creada! Redirigiendo…', 'exito');

            // Redirigir según rol
            setTimeout(() => {
                const destino = json.usuario.rol === 3
                    ? '<?= site_url('taller/dashboard') ?>'
                    : '<?= site_url('propietario/dashboard') ?>';
                window.location.href = destino;
            }, 1200);

        } else {
            // ── Error de la API: mostrar mensajes de campo o general ──
            const errores = json.messages?.error ?? json.messages ?? {};

            if (typeof errores === 'object') {
                // Errores por campo → mostrar en paso 1 y volver
                const tieneErroresCampo = Object.keys(errores).some(k =>
                    document.getElementById('err_' + k)
                );

                if (tieneErroresCampo) {
                    volverPaso1({ preventDefault: () => {} });
                    Object.entries(errores).forEach(([campo, msg]) => {
                        mostrarError(campo, msg);
                    });
                } else {
                    // Error general (ej: email duplicado)
                    mostrarAlerta(
                        errores.email ?? JSON.stringify(errores)
                    );
                }
            } else {
                mostrarAlerta(errores);
            }
        }

    } catch (err) {
        // Error de red o servidor caído
        mostrarAlerta('No se pudo conectar con el servidor. Verifica tu conexión.');
    } finally {
        btnFinalizar.classList.remove('cargando');
        btnFinalizar.disabled = false;
    }
});

/* ------------------------------------------------------------------
   MODAL plan premium
------------------------------------------------------------------ */
cardPremium.addEventListener('click', () => modal.style.display = 'flex');
document.getElementById('btnCerrarModal').addEventListener('click', () => {
    modal.style.display = 'none';
});
</script>
