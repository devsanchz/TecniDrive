<?php

namespace App\Controllers\Administrador;

use App\Controllers\BaseController;
use App\Models\TallerModel;
use App\Models\VehiculoModel;
use CodeIgniter\HTTP\RedirectResponse;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminReporte extends BaseController
{
    // =========================================================================
    // Constantes de roles — evita "números mágicos" dispersos en el código
    // =========================================================================

    private const ID_PROPIETARIO = 2;
    private const ID_MECANICO    = 3;

    // =========================================================================
    // GET — Vista principal de reportes
    // =========================================================================

    public function reporte(): string|RedirectResponse
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        return view('Administrador/admin_reporte', [
            'titulo_pagina' => 'ADMIN-REPORTES',
            'mensaje'       => session()->getFlashdata('mensaje') ?? null,
            'tipo_mensaje'  => session()->getFlashdata('tipo_mensaje') ?? 'info',
        ]);
    }

    // =========================================================================
    // POST — Enrutador principal: delega según la sección recibida
    //
    // REGLA: agregar un nuevo reporte = añadir un case aquí + un método privado.
    // No tocar nada más en este método.
    // =========================================================================

    public function generarReporte(): mixed
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $seccion = $this->request->getPost('seccion');

        if (! $seccion) {
            return $this->redireccionConMensaje(
                'Debes seleccionar al menos una sección para generar el reporte.',
                'error'
            );
        }

        return match($seccion) {
            'usuarios'  => $this->generarPdfUsuarios(),
            'vehiculos' => $this->generarPdfVehiculos(),
            'talleres'  => $this->generarPdfTalleres(),
            default     => $this->redireccionConMensaje('Sección no reconocida.', 'error'),
        };
    }

    // =========================================================================
    // PRIVADO — Helpers compartidos
    // =========================================================================

    /**
     * Redirige a la vista de reportes con un mensaje flash.
     * Centraliza la lógica de redirección para no repetirla en cada método.
     */
    private function redireccionConMensaje(string $mensaje, string $tipo = 'info'): RedirectResponse
    {
        session()->setFlashdata('mensaje',      $mensaje);
        session()->setFlashdata('tipo_mensaje', $tipo);
        return redirect()->to(site_url('administrador/reporte'));
    }

    /**
     * Instancia y configura Dompdf con las opciones base del proyecto.
     * Si en el futuro se necesitan fuentes o ajustes distintos, se cambia aquí.
     */
    private function crearDompdf(): Dompdf
    {
        $opciones = new Options();
        $opciones->set('isHtml5ParserEnabled', true);
        $opciones->set('isRemoteEnabled',      false);
        $opciones->set('defaultFont',          'Arial');

        return new Dompdf($opciones);
    }

    /**
     * Entrega el PDF generado al navegador (inline = lo abre; attachment = descarga).
     * Todos los métodos de generación terminan llamando a este helper.
     */
    private function streamPdf(string $html, string $nombreArchivo, string $orientacion = 'portrait'): never
    {
        $pdf = $this->crearDompdf();
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4', $orientacion);
        $pdf->render();
        $pdf->stream($nombreArchivo, ['Attachment' => false]);
        exit;
    }

    /**
     * Convierte el valor del radio "periodo" en fecha de corte y texto legible.
     * Compartido por los tres reportes — un solo lugar para cambiar fechas.
     *
     * @return array{fechaCorte: string, periodoTexto: string}
     */
    private function resolverPeriodo(string $periodo): array
    {
        return match($periodo) {
            'mes'      => ['fechaCorte' => date('Y-m-d', strtotime('-1 month')),  'periodoTexto' => 'Este mes'],
            'semestre' => ['fechaCorte' => date('Y-m-d', strtotime('-6 months')), 'periodoTexto' => 'Últimos 6 meses'],
            'año'      => ['fechaCorte' => date('Y-m-d', strtotime('-1 year')),   'periodoTexto' => 'Último año'],
            default    => ['fechaCorte' => date('Y-m-d', strtotime('-7 days')),   'periodoTexto' => 'Esta semana'],
            // 'semana' cae en default para no repetir la misma expresión
        };
    }

    // =========================================================================
    // PRIVADO — Reporte de Usuarios (sin cambios — ya funciona en Postman)
    // =========================================================================

    private function generarPdfUsuarios(): mixed
    {
        $roles = $this->request->getPost('roles_usuarios') ?? [];

        if (empty($roles)) {
            return $this->redireccionConMensaje(
                'Selecciona al menos un tipo de usuario (Propietarios o Mecánicos).',
                'error'
            );
        }

        $periodo = $this->request->getPost('periodo_usuarios') ?? 'semana';
        ['fechaCorte' => $fechaCorte, 'periodoTexto' => $periodoTexto] = $this->resolverPeriodo($periodo);

        $idsRol = [];
        if (in_array('propietarios', $roles)) $idsRol[] = self::ID_PROPIETARIO;
        if (in_array('mecanicos',    $roles)) $idsRol[] = self::ID_MECANICO;

        $db = \Config\Database::connect();

        $usuarios = $db->table('personas p')
            ->select('p.primer_nombre, p.primer_apellido, p.email, p.fecha_registro, r.texto_rol')
            ->join('roles_has_persona rp', 'rp.personas_id_persona = p.id_persona')
            ->join('roles r',              'r.id_rol = rp.roles_id_rol')
            ->whereIn('rp.roles_id_rol', $idsRol)
            ->where('p.fecha_registro >=', $fechaCorte)
            ->orderBy('p.fecha_registro', 'DESC')
            ->get()
            ->getResultArray();

        $totalPropietarios = 0;
        $totalMecanicos    = 0;

        foreach ($usuarios as $u) {
            if (strtolower($u['texto_rol']) === 'propietario') $totalPropietarios++;
            if (strtolower($u['texto_rol']) === 'mecanico')    $totalMecanicos++;
        }

        $html = view('reportes/usuarios_pdf', [
            'usuarios'          => $usuarios,
            'totalPropietarios' => $totalPropietarios,
            'totalMecanicos'    => $totalMecanicos,
            'periodoTexto'      => $periodoTexto,
            'fechaGeneracion'   => date('d/m/Y H:i'),
        ]);

        $this->streamPdf($html, 'reporte_usuarios_' . date('Ymd_His') . '.pdf');
    }

    // =========================================================================
    // PRIVADO — Reporte de Vehículos (CORREGIDO — usa VehiculoModel)
    // =========================================================================

    private function generarPdfVehiculos(): mixed
    {
        $tipos = $this->request->getPost('tipos_vehiculos') ?? [];

        if (empty($tipos)) {
            return $this->redireccionConMensaje(
                'Selecciona al menos un tipo de vehículo (Automóviles o Motocicletas).',
                'error'
            );
        }

        $periodo = $this->request->getPost('periodo_vehiculos') ?? 'semana';
        ['fechaCorte' => $fechaCorte, 'periodoTexto' => $periodoTexto] = $this->resolverPeriodo($periodo);

        // El formulario envía texto ('automovil'/'motocicleta'), pero la tabla
        // tipos_vehiculo usa ID numérico: 1 = Carro, 2 = Moto (según el INSERT original).
        $mapaIdTipo = [
            'automovil'   => 1,
            'motocicleta' => 2,
        ];

        $idsTipo = [];
        foreach ($tipos as $tipo) {
            if (isset($mapaIdTipo[$tipo])) {
                $idsTipo[] = $mapaIdTipo[$tipo];
            }
        }

        $vehiculoModel = new VehiculoModel();
        $vehiculosRaw  = $vehiculoModel->obtenerParaReporte($idsTipo, $fechaCorte);

        // Traducir el ID de vuelta al texto que YA usan la vista y el CSS
        // (badge-automovil / badge-motocicleta) — así no se toca vehiculos_pdf.php.
        $mapaTextoTipo = array_flip($mapaIdTipo);

        $vehiculos = array_map(function ($v) use ($mapaTextoTipo) {
            $v['tipo_vehiculo'] = $mapaTextoTipo[$v['tipos_vehiculo_id_tipo_vehi']] ?? 'desconocido';
            return $v;
        }, $vehiculosRaw);

        $totalAutomoviles  = count(array_filter($vehiculos, fn($v) => $v['tipo_vehiculo'] === 'automovil'));
        $totalMotocicletas = count(array_filter($vehiculos, fn($v) => $v['tipo_vehiculo'] === 'motocicleta'));

        $html = view('reportes/vehiculos_pdf', [
            'vehiculos'         => $vehiculos,
            'totalAutomoviles'  => $totalAutomoviles,
            'totalMotocicletas' => $totalMotocicletas,
            'periodoTexto'      => $periodoTexto,
            'fechaGeneracion'   => date('d/m/Y H:i'),
        ]);

        $this->streamPdf($html, 'reporte_vehiculos_' . date('Ymd_His') . '.pdf');
    }

    // =========================================================================
    // PRIVADO — Reporte de Talleres (CORREGIDO — usa TallerModel)
    // =========================================================================

    private function generarPdfTalleres(): mixed
    {
        $filtroEstado  = $this->request->getPost('estado_talleres')  ?? 'todos';
        $filtroRanking = $this->request->getPost('ranking_talleres') ?? null;
        $periodo       = $this->request->getPost('periodo_talleres') ?? 'semana';

        ['fechaCorte' => $fechaCorte, 'periodoTexto' => $periodoTexto] = $this->resolverPeriodo($periodo);

        // estado_taller es BOOLEAN en la base de datos (no texto) — se traduce aquí.
        $estadoBooleano = match($filtroEstado) {
            'activo'      => true,
            'desactivado' => false,
            default       => null, // 'todos' → sin filtro
        };

        // En modo ranking no se filtra por fecha (se asume el listado completo).
        $fechaParaConsulta = $filtroRanking ? null : $fechaCorte;

        $tallerModel = new TallerModel();
        $talleresRaw = $tallerModel->obtenerParaReporte($estadoBooleano, $fechaParaConsulta);

        // ── Ordenamiento por ranking ───────────────────────────────────────
        // PENDIENTE: 'puntuacion_promedio' y 'total_citas' requieren la tabla
        // de calificaciones y confirmar la relación de cita_taller con taller,
        // que aún no han sido compartidas. Mientras tanto se ordena por fecha
        // para que el reporte no se rompa.
        $ordenTexto = 'Fecha de registro';
        if ($filtroRanking) {
            $ordenTexto = 'Fecha de registro (ranking pendiente de datos)';
        }

        // Traducir estado_taller (boolean) y direccion_taller al texto/clave
        // que ya espera la vista talleres_pdf.php.
        $talleres = array_map(function ($t) {
            $t['estado']              = $t['estado_taller'] ? 'activo' : 'inactivo';
            $t['direccion']            = $t['direccion_taller'];
            // Placeholders explícitos — pendientes de tabla real (ver nota arriba).
            $t['puntuacion_promedio'] = 0;
            $t['total_citas']         = 0;
            return $t;
        }, $talleresRaw);

        $totalActivos      = count(array_filter($talleres, fn($t) => $t['estado'] === 'activo'));
        $totalDesactivados = count($talleres) - $totalActivos;

        $html = view('reportes/talleres_pdf', [
            'talleres'          => $talleres,
            'totalActivos'      => $totalActivos,
            'totalDesactivados' => $totalDesactivados,
            'periodoTexto'      => $periodoTexto,
            'ordenTexto'        => $ordenTexto,
            'modoRanking'       => (bool) $filtroRanking,
            'fechaGeneracion'   => date('d/m/Y H:i'),
        ]);

        $this->streamPdf($html, 'reporte_talleres_' . date('Ymd_His') . '.pdf', 'landscape');
    }
}