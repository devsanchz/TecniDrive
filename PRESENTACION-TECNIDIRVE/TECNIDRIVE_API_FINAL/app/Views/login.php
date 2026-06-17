<?php echo $this->extend('Diseño/plantilla'); ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/registro.css') ?>">
<?= $this->endSection() ?>
<style>
    .alerta-error {
        background: #fdecea; color: #c0392b;
        border: 1px solid #f5c6cb;
        padding: .75rem 1rem; border-radius: 6px;
        margin-bottom: 1rem; font-size: .9rem;
    }
    .btn-spinner { display: none; }
    button.cargando .btn-texto  { display: none; }
    button.cargando .btn-spinner{ display: inline; }
</style>



<?php echo $this->section('contenido') ?>
<a class="Volver" title="Volver a principal" href="<?= site_url('/') ?>">
    <i class="bi bi-chevron-left"></i>
</a>

<div class="tarjeta-autenticacion">

    <h1>Iniciar sesión</h1>

    <!-- Alerta general -->
    <div id="alertaLogin" role="alert" style="display:none;"></div>

    <form id="formLogin" novalidate>

        <!-- Email -->
        <label>E-mail</label>
        <div class="grupo-input">
            <input type="email" id="email" placeholder="ejemplo@gmail.com" required>
            <i class="bi bi-envelope"></i>
        </div>
        <span class="error-campo" id="err_email" role="alert"></span>

        <!-- Contraseña -->
        <label>Contraseña</label>
        <div class="grupo-input">
            <input type="password" id="password" placeholder="Tu contraseña" required>
            <i class="bi bi-lock"></i>
        </div>
        <span class="error-campo" id="err_password" role="alert"></span>

        <button type="submit" id="btnLogin">
            <span class="btn-texto">Entrar</span>
            <span class="btn-spinner"><i class="bi bi-arrow-repeat"></i> Verificando…</span>
        </button>

        <div class="links">
            <a href="<?= site_url('registro') ?>">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>

    </form>
</div>

<?php echo $this->endSection('') ?>


<script>
/* ==========================================================
   login.js — inlineado
   ========================================================== */

const API_URL   = '<?= base_url('api/v1') ?>';
const formLogin = document.getElementById('formLogin');
const btnLogin  = document.getElementById('btnLogin');
const alerta    = document.getElementById('alertaLogin');

// ── Utilidades ─────────────────────────────────────────────────────────
function mostrarError(id, msg) {
    const el = document.getElementById('err_' + id);
    if (el) el.textContent = msg;
}
function limpiarErrores() {
    document.querySelectorAll('.error-campo').forEach(el => el.textContent = '');
}
function mostrarAlerta(msg) {
    alerta.className    = 'alerta-error';
    alerta.textContent  = msg;
    alerta.style.display = 'block';
}

/* ------------------------------------------------------------------
   SUBMIT → llama a POST /api/v1/login
------------------------------------------------------------------ */
formLogin.addEventListener('submit', async function (e) {
    e.preventDefault();
    limpiarErrores();
    alerta.style.display = 'none';

    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    // Validación mínima en cliente
    let valido = true;
    if (!email) { mostrarError('email', 'El email es obligatorio'); valido = false; }
    if (!password) { mostrarError('password', 'La contraseña es obligatoria'); valido = false; }
    if (!valido) return;

    btnLogin.classList.add('cargando');
    btnLogin.disabled = true;

    try {
        const res  = await fetch(`${API_URL}/login`, {
            method : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body   : JSON.stringify({ email, password }),
        });

        const json = await res.json();

        if (res.ok) {
            // ── Guardar token y datos de sesión ────────────────────────
            localStorage.setItem('td_token',   json.token);
            localStorage.setItem('td_usuario', JSON.stringify(json.usuario));

            // ── Redirigir según rol ────────────────────────────────────
            const destino = json.usuario.rol === 3
                ? '<?= site_url('taller/dashboard') ?>'
                : '<?= site_url('propietario/dashboard') ?>';

            window.location.href = destino;

        } else {
            // Error 401 u otros — mensaje genérico por seguridad
            mostrarAlerta(
                json.messages?.error ?? 'Credenciales incorrectas'
            );
        }

    } catch (err) {
        mostrarAlerta('No se pudo conectar con el servidor.');
    } finally {
        btnLogin.classList.remove('cargando');
        btnLogin.disabled = false;
    }
});
</script>
