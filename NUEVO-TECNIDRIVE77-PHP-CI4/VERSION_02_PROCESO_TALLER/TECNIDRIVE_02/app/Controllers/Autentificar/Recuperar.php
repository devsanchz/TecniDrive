<?php

namespace App\Controllers\Autentificar;

use App\Controllers\BaseController;
use App\Models\PersonaModel;
use CodeIgniter\HTTP\RedirectResponse; // ← NUEVO import

class Recuperar extends BaseController
{
    // ══════════════════════════════════════════════════════════════════════
    // PASO 0 — Mostrar formulario de email
    // ══════════════════════════════════════════════════════════════════════
    public function restablecer(): string
    {
        return view('Autentificar/recuperar', [
            'titulo_pagina' => 'RECUPERAR CONTRASEÑA',
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // PASO 1 — Recibir email, generar código y enviar correo
    // ══════════════════════════════════════════════════════════════════════
    public function enviarCodigo(): RedirectResponse
    {
        $rules = ['email' => 'required|max_length[60]|valid_email'];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Ingresa un correo electrónico válido.');
            return redirect()->to(site_url('autentificar/recuperar'));
        }

        $email        = $this->request->getPost('email');
        $personaModel = new PersonaModel();
        $persona      = $personaModel->buscarPorEmail($email);

        // Respuesta genérica aunque no exista: no revelar si el correo está registrado
        if (!$persona) {
            session()->setFlashdata('error', 'Si el correo está registrado, recibirás el código en breve.');
            return redirect()->to(site_url('autentificar/recuperar'));
        }

        // ── Generar código y guardar en BD ────────────────────────────────
        $codigo          = strval(random_int(100000, 999999));
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $personaModel->update($persona['id_persona'], [
            'codigo_recuperacion' => $codigo,
            'fecha_expiracion'    => $fechaExpiracion,
        ]);

        // ── Enviar correo ─────────────────────────────────────────────────
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Código de recuperación — TECNIDRIVE');

        $body  = '<p>Hola, <strong>' . esc($persona['primer_nombre']) . '</strong></p>';
        $body .= '<p>Tu código para restablecer tu contraseña es:</p>';
        $body .= '<h2 style="letter-spacing:6px;">' . $codigo . '</h2>';
        $body .= '<p>Válido por <strong>5 minutos</strong>.</p>';
        $body .= '<p>Si no solicitaste este cambio, ignora este mensaje.</p>';
        $body .= '<p>— Equipo TECNIDRIVE</p>';

        $emailService->setMessage($body);
        $emailService->send();

        // ── Pasar el email al paso 2 por flashdata ────────────────────────
        session()->setFlashdata('email_recuperacion', $email);
        return redirect()->to(site_url('autentificar/verificar-codigo'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // PASO 2 — Mostrar formulario de código
    // ══════════════════════════════════════════════════════════════════════
    public function mostrarVerificar(): string|RedirectResponse // ← CORREGIDO
    {
        // Si no hay email en sesión, volver al inicio
        if (!session()->getFlashdata('email_recuperacion')) {
            return redirect()->to(site_url('autentificar/recuperar'));
        }

        // Mantener el email disponible para el form
        session()->setFlashdata('email_recuperacion', session()->getFlashdata('email_recuperacion'));

        return view('Autentificar/verificar_codigo', [
            'titulo_pagina' => 'VERIFICAR CÓDIGO',
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // PASO 3 — Verificar el código
    // ══════════════════════════════════════════════════════════════════════
    public function verificarCodigo(): RedirectResponse
    {
        $email       = $this->request->getPost('email');
        $codigoInput = $this->request->getPost('codigo');

        $personaModel = new PersonaModel();
        $persona      = $personaModel->buscarPorEmail($email);

        if (!$persona) {
            session()->setFlashdata('error', 'Código incorrecto o expirado.');
            session()->setFlashdata('email_recuperacion', $email);
            return redirect()->to(site_url('autentificar/verificar-codigo'));
        }

        // ── Verificar expiración ──────────────────────────────────────────
        if ($persona['fecha_expiracion'] < date('Y-m-d H:i:s')) {
            session()->setFlashdata('error', 'El código ha expirado. Solicita uno nuevo.');
            return redirect()->to(site_url('autentificar/recuperar'));
        }

        // ── Verificar código ──────────────────────────────────────────────
        if ($persona['codigo_recuperacion'] !== $codigoInput) {
            session()->setFlashdata('error', 'Código incorrecto. Verifica e intenta de nuevo.');
            session()->setFlashdata('email_recuperacion', $email);
            return redirect()->to(site_url('autentificar/verificar-codigo'));
        }

        // ── Código válido: limpiar BD y guardar sesión ────────────────────
        $personaModel->update($persona['id_persona'], [
            'codigo_recuperacion' => null,
            'fecha_expiracion'    => null,
        ]);

        session()->set('recuperacion_id', $persona['id_persona']);
        return redirect()->to(site_url('autentificar/nuevo-pass'));
    }

    // ══════════════════════════════════════════════════════════════════════
    // PASO 4 — Mostrar formulario de nueva contraseña
    // ══════════════════════════════════════════════════════════════════════
    public function nuevoPass(): string|RedirectResponse // ← CORREGIDO
    {
        if (!session()->get('recuperacion_id')) {
            return redirect()->to(site_url('autentificar/recuperar'));
        }

        return view('Autentificar/nuevo_pass', [
            'titulo_pagina' => 'NUEVA CONTRASEÑA',
        ]);
    }

    // ══════════════════════════════════════════════════════════════════════
    // PASO 5 — Guardar nueva contraseña
    // ══════════════════════════════════════════════════════════════════════
    public function actualizarPass(): RedirectResponse
    {
        $idPersona = session()->get('recuperacion_id');

        if (!$idPersona) {
            session()->setFlashdata('error', 'Sesión expirada. Inicia el proceso de nuevo.');
            return redirect()->to(site_url('autentificar/recuperar'));
        }

        $rules = [
            'password'   => 'required|min_length[8]|max_length[255]',
            'repassword' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', implode(' ', array_values($this->validator->getErrors())));
            return redirect()->to(site_url('autentificar/nuevo-pass'));
        }

        $personaModel = new PersonaModel();
        $personaModel->update($idPersona, [
            'password_hash' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
        ]);

        session()->remove('recuperacion_id');
        return redirect()->to(site_url('autentificar/ingreso'));
    }
}