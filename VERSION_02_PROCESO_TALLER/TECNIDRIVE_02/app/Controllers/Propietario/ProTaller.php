<?php

namespace App\Controllers\Propietario;

use App\Controllers\BaseController;
use App\Models\TallerModel;
use App\Models\TallerServicioModel;
use App\Models\TallerEspecialidadModel;
use App\Models\CalificacionTallerModel;
use App\Models\VehiculoModel;
use App\Models\CitaTallerModel;
use App\Models\CitaHasServicioModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class ProTaller extends BaseController
{
    // =========================================================================
    // GET propietario/taller
    //
    // Sesión y rol (propietario = id_rol 2) ya verificados por AuthFilter
    // ('auth:2' en el grupo 'propietario' de Routes.php).
    // =========================================================================

    public function taller(): string
    {
        $idPropietario = (int) session()->get('id_propietario');

        $db = \Config\Database::connect();

        $talleres = $db->table('taller t')
            ->select('t.*, p.primer_nombre, p.primer_apellido')
            ->join('mecanicos m', 'm.id_mecanico = t.mecanicos_id_mecanico')
            ->join('personas p', 'p.id_persona = m.id_mecanico')
            ->where('t.estado_taller', 1)
            ->get()
            ->getResultArray();

        $tallerServicioModel     = new TallerServicioModel();
        $tallerEspecialidadModel = new TallerEspecialidadModel();
        $calificacionModel       = new CalificacionTallerModel();

        foreach ($talleres as &$taller) {
            $id = $taller['id_taller'];
            $taller['servicios']      = $tallerServicioModel->obtenerPorTaller($id);
            $taller['especialidades'] = $tallerEspecialidadModel->obtenerPorTaller($id);
            $taller['calificaciones'] = $calificacionModel->obtenerPorTaller($id);
            $taller['promedio']       = $calificacionModel->promedio($id);
            $taller['ya_califico']    = $calificacionModel->yaCalifico($id, $idPropietario);
            $taller['mi_calificacion'] = $calificacionModel->obtenerMiCalificacion($id, $idPropietario);

            // ── Rango de estrellas para los filtros del front (mismas franjas que el procedure) ──
            if ($taller['promedio'] >= 4.5) {
                $taller['rango'] = 'cinco';
            } elseif ($taller['promedio'] >= 2.5) {
                $taller['rango'] = 'medio';
            } elseif ($taller['promedio'] > 0) {
                $taller['rango'] = 'bajo';
            } else {
                $taller['rango'] = '';
            }
        }
        unset($taller);

        // Conteos por rango usando el stored procedure
        $spQuery      = $db->query('CALL sp_promedio_calificaciones(NULL)');
        $spResultados = $spQuery->getResultArray();

        // mysqli deja un resultado "pendiente" después de un CALL. Si no se limpia
        // aquí, la SIGUIENTE consulta en esta misma petición (la de abajo, o cualquier
        // otra que agregues más adelante) revienta con "Commands out of sync".
        if ($db->connID instanceof \mysqli) {
            while ($db->connID->more_results() && $db->connID->next_result()) {
                if ($extra = $db->connID->store_result()) {
                    $extra->free();
                }
            }
        }

        $conteos = ['cinco' => 0, 'medio' => 0, 'bajo' => 0];
        foreach ($spResultados as $fila) {
            $prom = (float) $fila['promedio_estrellas'];
            if ($prom >= 4.5) {
                $conteos['cinco']++;
            } elseif ($prom >= 2.5) {
                $conteos['medio']++;
            } elseif ($prom > 0) {
                $conteos['bajo']++;
            }
        }

        // Vehículos activos del propietario para el formulario de cita
        $misVehiculos = (new VehiculoModel())->obtenerPorPropietario($idPropietario);

        return view('Propietario/pro_taller', [
            'titulo_pagina' => 'Talleres Mecánicos',
            'talleres'      => $talleres,
            'total'         => count($talleres),
            'conteos'       => $conteos,
            'mis_vehiculos' => $misVehiculos,
        ]);
    }

    // =========================================================================
    // POST propietario/taller/calificar  — crear nueva calificación
    //
    // Guard AJAX de sesión/rol: aunque la ruta ya está protegida por
    // 'auth:2' en Routes.php, se conserva esta verificación para responder
    // con JSON limpio si la sesión expira a mitad de una llamada fetch()
    // (AuthFilter respondería con un redirect HTML, que rompería el JS).
    // =========================================================================

    public function calificar(): ResponseInterface
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'error' => 'Acceso no permitido.']);
        }
        if (! session()->get('logueado') || (int) session()->get('usuario_rol') !== 2) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Sesión inválida.']);
        }

        $datos         = $this->request->getJSON(true);
        $idTaller      = (int) ($datos['taller_id']  ?? 0);
        $puntuacion    = (int) ($datos['puntuacion']  ?? 0);
        $comentario    = trim($datos['comentario']    ?? '');
        $idPropietario = (int) session()->get('id_propietario');

        if ($puntuacion < 1 || $puntuacion > 5) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Selecciona entre 1 y 5 estrellas.']);
        }
        if ($idTaller <= 0) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Taller inválido.']);
        }

        $modelo = new CalificacionTallerModel();

        if ($modelo->yaCalifico($idTaller, $idPropietario)) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Ya calificaste este taller.']);
        }

        // Si trae comentario queda pendiente de aprobación; si solo son estrellas se publica directo
        $estado = $comentario !== '' ? 'pendiente' : 'aprobada';

        $modelo->insert([
            'puntuacion'                  => $puntuacion,
            'comentario'                  => $comentario !== '' ? $comentario : null,
            'estado'                      => $estado,
            'taller_id_taller'            => $idTaller,
            'propietarios_id_propietario' => $idPropietario,
        ]);

        return $this->response->setJSON([
            'ok'          => true,
            'estado'      => $estado,
            'promedio'    => $modelo->promedio($idTaller),
            'total'       => $modelo->totalAprobadas($idTaller),
            'nombre'      => session()->get('usuario_nombre'),
            'avatarcolor' => session()->get('usuario_avatar'),
            'puntuacion'  => $puntuacion,
            'comentario'  => $comentario,
            // CSRF en modo 'cookie' regenera el hash tras cada verificación
            // exitosa; sin devolverlo aquí, la SIGUIENTE petición fetch
            // (actualizar/eliminar) en la misma carga de página fallaría
            // con el mismo 403 que se está resolviendo ahora.
            'csrf_token_name'  => csrf_token(),
            'csrf_token_value' => csrf_hash(),
        ]);
    }

    // =========================================================================
    // POST propietario/taller/actualizar — editar calificación existente
    // =========================================================================

    public function actualizar(): ResponseInterface
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'error' => 'Acceso no permitido.']);
        }
        if (! session()->get('logueado') || (int) session()->get('usuario_rol') !== 2) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Sesión inválida.']);
        }

        $datos         = $this->request->getJSON(true);
        $idTaller      = (int) ($datos['taller_id']  ?? 0);
        $puntuacion    = (int) ($datos['puntuacion']  ?? 0);
        $comentario    = trim($datos['comentario']    ?? '');
        $idPropietario = (int) session()->get('id_propietario');

        if ($puntuacion < 1 || $puntuacion > 5) {
            return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'error' => 'Selecciona entre 1 y 5 estrellas.']);
        }

        $modelo = new CalificacionTallerModel();

        // Verificar que la calificación pertenece a este propietario (autorización, se conserva)
        $calificacion = $modelo
            ->where('taller_id_taller',            $idTaller)
            ->where('propietarios_id_propietario', $idPropietario)
            ->first();

        if (! $calificacion) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'error' => 'Calificación no encontrada.']);
        }

        // Actualizar solo los campos editables; si agrega comentario vuelve a quedar pendiente
        $estado = $comentario !== '' ? 'pendiente' : 'aprobada';

        $modelo->update($calificacion['id_calificacion'], [
            'puntuacion' => $puntuacion,
            'comentario' => $comentario !== '' ? $comentario : null,
            'estado'     => $estado,
        ]);

        return $this->response->setJSON([
            'ok'         => true,
            'estado'     => $estado,
            'promedio'   => $modelo->promedio($idTaller),
            'total'      => $modelo->totalAprobadas($idTaller),
            'puntuacion' => $puntuacion,
            'comentario' => $comentario,
            'csrf_token_name'  => csrf_token(),
            'csrf_token_value' => csrf_hash(),
        ]);
    }

    // =========================================================================
    // POST propietario/taller/eliminar — borrar calificación propia
    // =========================================================================

    public function eliminar(): ResponseInterface
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'error' => 'Acceso no permitido.']);
        }
        if (! session()->get('logueado') || (int) session()->get('usuario_rol') !== 2) {
            return $this->response->setStatusCode(401)->setJSON(['ok' => false, 'error' => 'Sesión inválida.']);
        }

        $datos         = $this->request->getJSON(true);
        $idTaller      = (int) ($datos['taller_id'] ?? 0);
        $idPropietario = (int) session()->get('id_propietario');

        $modelo = new CalificacionTallerModel();

        // Verificar que la calificación pertenece a este propietario (autorización, se conserva)
        $calificacion = $modelo
            ->where('taller_id_taller',            $idTaller)
            ->where('propietarios_id_propietario', $idPropietario)
            ->first();

        if (! $calificacion) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'error' => 'Calificación no encontrada.']);
        }

        $modelo->delete($calificacion['id_calificacion']);

        // Calcular nuevo promedio y total tras eliminar
        $nuevoTotal    = $modelo->totalAprobadas($idTaller);
        $nuevoPromedio = $modelo->promedio($idTaller);

        return $this->response->setJSON([
            'ok'       => true,
            'promedio' => $nuevoPromedio,
            'total'    => $nuevoTotal,
            'csrf_token_name'  => csrf_token(),
            'csrf_token_value' => csrf_hash(),
        ]);
    }

    // =========================================================================
    // POST propietario/taller/agendar-cita — registrar nueva cita
    //
    // Sesión y rol ya verificados por AuthFilter ('auth:2'). Se conservan
    // las verificaciones de propiedad del vehículo y de cita activa: son
    // autorización a nivel de recurso, no autenticación.
    // =========================================================================

    public function agendarCita(): RedirectResponse
    {
        $idPropietario = (int) session()->get('id_propietario');

        // ── Validación ────────────────────────────────────────────────────
        $rules = [
            'id_taller'  => 'required|is_natural_no_zero',
            'placa'      => 'required|max_length[6]',
            'fecha_cita' => 'required|valid_date[Y-m-d]',
            'hora_cita'  => 'required',
        ];

        $messages = [
            'id_taller'  => ['required' => 'El taller es obligatorio.'],
            'placa'      => ['required' => 'Selecciona un vehículo.'],
            'fecha_cita' => ['required' => 'La fecha es obligatoria.'],
            'hora_cita'  => ['required' => 'La hora es obligatoria.'],
        ];

        if (! $this->validate($rules, $messages)) {
            session()->setFlashdata('error_cita', implode(' ', array_values($this->validator->getErrors())));
            return redirect()->to(site_url('propietario/taller'));
        }

        $idTaller  = (int) $this->request->getPost('id_taller');
        $placa     = strtoupper(trim($this->request->getPost('placa')));
        $fechaCita = $this->request->getPost('fecha_cita');
        $horaCita  = $this->request->getPost('hora_cita');
        $problema  = trim($this->request->getPost('problema') ?? '');
        $servicios = $this->request->getPost('servicios') ?? [];

        // ── Verificar que el vehículo pertenece al propietario (autorización) ──
        $vehiculoModel = new VehiculoModel();

        $vehiculo = $vehiculoModel
            ->where('placa', $placa)
            ->where('propietarios_id_propietario', $idPropietario)
            ->first();

        if (! $vehiculo) {
            session()->setFlashdata('error_cita', 'El vehículo seleccionado no es válido.');
            return redirect()->to(site_url('propietario/taller'));
        }

        // ── Verificar que el vehículo no tenga cita activa ─────────────────
        if ($vehiculoModel->tienesCitaActiva($placa)) {
            session()->setFlashdata('error_cita', 'El vehículo ' . $placa . ' ya tiene una cita activa. Espera a que finalice antes de agendar otra.');
            return redirect()->to(site_url('propietario/taller'));
        }

        // ── Combinar fecha y hora en un solo datetime ──────────────────────
        $fechaHora = $fechaCita . ' ' . $horaCita . ':00';

        // ── Insertar cita ───────────────────────────────────────────────────
        $citaModel = new CitaTallerModel();
        $citaModel->insert([
            'fecha_cita'        => $fechaHora,
            'problema_contexto' => $problema !== '' ? $problema : null,
            'estado_cita'       => 'pendiente',
            'taller_id_taller'  => $idTaller,
            'vehiculo_placa'    => $placa,
        ]);

        $idCita = $citaModel->insertID();

        // ── Insertar servicios seleccionados ───────────────────────────────
        if (! empty($servicios)) {
            $citaHasServicioModel = new CitaHasServicioModel();

            foreach ($servicios as $idServicio) {
                $citaHasServicioModel->insert([
                    'cita_taller_id_cita'   => $idCita,
                    'taller_id_taller'      => $idTaller,
                    'servicios_id_servicio' => (int) $idServicio,
                ]);
            }
        }

        session()->setFlashdata('exito_cita', 'Cita agendada correctamente. Estado: pendiente de confirmación.');
        return redirect()->to(site_url('propietario/taller'));
    }
}