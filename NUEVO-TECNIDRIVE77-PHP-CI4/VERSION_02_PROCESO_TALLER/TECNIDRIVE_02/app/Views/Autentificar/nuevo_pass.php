<?= $this->extend('Estructura/diseño') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/restablecer.css') ?>">
<style>
    .barra-fortaleza {
        height: 5px;
        border-radius: 3px;
        margin-top: 6px;
        background: #e0e0e0;
        transition: background 0.3s, width 0.3s;
        width: 0;
    }
    .fortaleza-debil  { background: #e74c3c; width: 33%; }
    .fortaleza-media  { background: #f39c12; width: 66%; }
    .fortaleza-fuerte { background: #27ae60; width: 100%; }
    .texto-fortaleza  { font-size: 0.78rem; margin-top: 3px; color: #666; }
    .coincidencia-ok  { color: #27ae60; font-size: 0.82rem; }
    .coincidencia-mal { color: #c0392b; font-size: 0.82rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>

<div class="tarjeta-autenticacion">
    <h1>Nueva contraseña</h1>
    <p style="font-size:0.9rem; color:#666; margin-bottom:1.2rem;">
        Crea una contraseña segura de al menos 8 caracteres.
    </p>

    <form id="formNuevoPass" method="post" action="<?= site_url('autentificar/actualizar-pass') ?>">
        <?= csrf_field() ?>
        <!-- ── Error del servidor ── -->
        <?php if (session()->getFlashdata('error')): ?>
            <div style="color:#c0392b; font-size:0.88rem; margin-bottom:0.8rem;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- ── Nueva contraseña ── -->
        <div class="grupo">
            <input
                type="password"
                id="passwordInput"
                name="password"
                placeholder="Nueva contraseña"
                minlength="8"
                required
            >
            <div class="barra-fortaleza" id="barraFortaleza"></div>
            <span class="texto-fortaleza" id="textoFortaleza"></span>
        </div>

        <!-- ── Confirmar contraseña ── -->
        <div class="grupo">
            <input
                type="password"
                id="repasswordInput"
                name="repassword"
                placeholder="Confirmar contraseña"
                minlength="8"
                required
            >
            <span id="mensajeCoincidencia"></span>
        </div>

        <button type="submit">Guardar contraseña</button>
    </form>
</div>

<?= $this->endSection() ?>