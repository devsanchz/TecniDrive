<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;
use App\Models\TallerModel;
use App\Models\TallerServicioModel;
use App\Models\TallerEspecialidadModel;
use App\Models\CalificacionTallerModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class ProTaller extends BaseController
{
    public function taller(): string|RedirectResponse
    {
        if (! session()->get('logueado')) {
            return redirect()->to(site_url('autentificar/ingreso'));
        }

        $db = \Config\Database::connect();

        // Traer todos los talleres activos con el nombre del mecánico
        $talleres = $db->table('taller t')
            ->select('t.*, p.primer_nombre, p.primer_apellido')
            ->join('mecanicos m', 'm.id_mecanico = t.mecanicos_id_mecanico')
            ->join('personas p', 'p.id_persona = m.id_mecanico')
            ->where('t.estado_taller', 1)
            ->get()
            ->getResultArray();

        $tallerServicioModel      = new TallerServicioModel();
        $tallerEspecialidadModel  = new TallerEspecialidadModel();
        $calificacionModel        = new CalificacionTallerModel();
        $idPropietario            = (int) session()->get('usuario_id');

        foreach ($talleres as &$taller) {
            $id = $taller['id_taller'];
            $taller['servicios']      = $tallerServicioModel->obtenerPorTaller($id);
            $taller['especialidades'] = $tallerEspecialidadModel->obtenerPorTaller($id);
            // Calificaciones existentes y promedio para mostrar en la vista
            $taller['calificaciones'] = $calificacionModel->obtenerPorTaller($id);
            $taller['promedio']       = $calificacionModel->promedio($id);
            $taller['ya_califico']    = $calificacionModel->yaCalifico($id, $idPropietario);
        }
        unset($taller);

        return view('Propietario/pro_taller', [
            'titulo_pagina' => 'Talleres Mecánicos',
            'talleres'      => $talleres,
            'total'         => count($talleres),
        ]);
    }

    // =========================================================================
    // POST propietario/taller/calificar  (fetch desde JS)
    //
    // Recibe JSON: { taller_id, puntuacion, comentario }
    // Devuelve JSON: { ok: true, promedio, total } o { ok: false, error }
    // =========================================================================

    public function calificar(): ResponseInterface
    {
        // Guard AJAX
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)
                                  ->setJSON(['ok' => false, 'error' => 'Acceso no permitido.']);
        }

        if (! session()->get('logueado')) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['ok' => false, 'error' => 'Sesión inválida.']);
        }

        $datos         = $this->request->getJSON(true);
        $idTaller      = (int) ($datos['taller_id']   ?? 0);
        $puntuacion    = (int) ($datos['puntuacion']   ?? 0);
        $comentario    = trim($datos['comentario']     ?? '');
        $idPropietario = (int) session()->get('usuario_id');

        // Validar puntuación
        if ($puntuacion < 1 || $puntuacion > 5) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'error' => 'Selecciona entre 1 y 5 estrellas.']);
        }

        if ($idTaller <= 0) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'error' => 'Taller inválido.']);
        }

        $modelo = new CalificacionTallerModel();

        // Un propietario solo puede calificar una vez cada taller
        if ($modelo->yaCalifico($idTaller, $idPropietario)) {
            return $this->response->setStatusCode(422)
                                  ->setJSON(['ok' => false, 'error' => 'Ya calificaste este taller.']);
        }

        $modelo->insert([
            'puntuacion'                   => $puntuacion,
            'comentario'                   => $comentario !== '' ? $comentario : null,
            'taller_id_taller'             => $idTaller,
            'propietarios_id_propietario'  => $idPropietario,
        ]);

        // Devolver el promedio y total actualizados para refrescar la UI
        return $this->response->setJSON([
            'ok'        => true,
            'promedio'  => $modelo->promedio($idTaller),
            'total'     => $modelo->where('taller_id_taller', $idTaller)->countAllResults(),
            'nombre'    => session()->get('usuario_nombre'),
            'puntuacion'=> $puntuacion,
            'comentario'=> $comentario,
        ]);
    }
}