<?php
/**
 * API REST — Tecnidrive
 * Coloca este archivo en: C:/xampp/htdocs/api/api.php
 */

// esto permite la conexion con android :p
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// base de datos (no cambiar la contraseña amenos q sea necesario)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'TECNIDRIVE77');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Error BD: " . $conn->connect_error]);
        exit();
    }
    $conn->set_charset("utf8");
    return $conn;
}

function respuesta($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit();
}

//router (revisar el "MainActivity.kt" antes de tratar de solucionar errores de aqui a bajo)
$method   = $_SERVER['REQUEST_METHOD'];
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';
$body     = json_decode(file_get_contents("php://input"), true) ?? [];

switch ($endpoint) {

//revisa la base de datos, eso y que tmb tiene registro (manual claro) de la version de la api
//control de versiones para por si pasa algo
    case 'estado':
        $conn = getDB();
        $conn->close();
        respuesta([
            "success"   => true,
            "mensaje"   => "TECNIDRIVE77 API activa",
            "version"   => "1.0",
            "timestamp" => date('Y-m-d H:i:s')
        ]);

    // ────────────────────────────────────────
    // GET  ?endpoint=talleres
    // Lista todos los talleres activos con
    // su mecánico, servicios y calificación
    // ────────────────────────────────────────
    case 'talleres':
        $conn = getDB();
        $sql = "
            SELECT
                t.id_taller,
                t.nombre_taller,
                t.descripcion_taller,
                t.direccion_taller,
                t.horario_taller,
                t.foto_taller,
                CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS nombre_mecanico,
                ROUND(AVG(ct.puntuacion), 1)                    AS calificacion,
                COUNT(ct.id_calificacion)                       AS total_resenas
            FROM taller t
            JOIN mecanicos m  ON m.id_mecanico    = t.mecanicos_id_mecanico
            JOIN personas  p  ON p.id_persona     = m.id_mecanico
            LEFT JOIN calificacion_taller ct ON ct.taller_id_taller = t.id_taller
            WHERE t.estado_taller = TRUE
            GROUP BY t.id_taller
            ORDER BY calificacion DESC
        ";
        $res  = $conn->query($sql);
        $rows = [];
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        $conn->close();
        respuesta(["success" => true, "total" => count($rows), "talleres" => $rows]);

    // ────────────────────────────────────────
    // GET  ?endpoint=taller&id=1
    // Detalle completo de un taller
    // ────────────────────────────────────────
    case 'taller':
        $id = intval($_GET['id'] ?? 0);
        if (!$id) respuesta(["success" => false, "error" => "Parámetro ?id requerido"], 400);

        $conn = getDB();

        // Datos base del taller
        $sql = "
            SELECT t.*, CONCAT(p.primer_nombre,' ',p.primer_apellido) AS mecanico,
                   p.email AS email_mecanico, m.telefono_mecanico
            FROM taller t
            JOIN mecanicos m ON m.id_mecanico = t.mecanicos_id_mecanico
            JOIN personas p  ON p.id_persona  = m.id_mecanico
            WHERE t.id_taller = $id AND t.estado_taller = TRUE
        ";
        $row = $conn->query($sql)->fetch_assoc();
        if (!$row) { $conn->close(); respuesta(["success" => false, "error" => "Taller no encontrado"], 404); }

        // Servicios del taller
        $sqlServ = "
            SELECT s.nombre_servicio, ts.precio_servicio
            FROM taller_has_servicios ts
            JOIN servicios s ON s.id_servicio = ts.servicios_id_servicio
            WHERE ts.taller_id_taller = $id
        ";
        $resServ = $conn->query($sqlServ);
        $servicios = [];
        while ($s = $resServ->fetch_assoc()) $servicios[] = $s;

        // Especialidades del taller
        $sqlEsp = "
            SELECT e.nombre_especialidad
            FROM taller_especialidad te
            JOIN especialidades e ON e.id_especialidad = te.especialidades_id_especialidad
            WHERE te.taller_id_taller = $id
        ";
        $resEsp = $conn->query($sqlEsp);
        $especialidades = [];
        while ($e = $resEsp->fetch_assoc()) $especialidades[] = $e['nombre_especialidad'];

        // Últimas 5 reseñas
        $sqlRes = "
            SELECT ct.puntuacion, ct.comentario, ct.fecha_registro,
                   CONCAT(p.primer_nombre,' ',p.primer_apellido) AS cliente,
                   p.avatarcolor
            FROM calificacion_taller ct
            JOIN propietarios pr ON pr.id_propietario = ct.propietarios_id_propietario
            JOIN personas p      ON p.id_persona      = pr.id_propietario
            WHERE ct.taller_id_taller = $id
            ORDER BY ct.fecha_registro DESC
            LIMIT 5
        ";
        $resRew = $conn->query($sqlRes);
        $resenas = [];
        while ($r = $resRew->fetch_assoc()) $resenas[] = $r;

        $conn->close();
        respuesta([
            "success"       => true,
            "taller"        => $row,
            "servicios"     => $servicios,
            "especialidades"=> $especialidades,
            "resenas"       => $resenas
        ]);

    // ────────────────────────────────────────
    // GET  ?endpoint=citas&propietario=5
    // Citas de un propietario (por id_persona)
    // ────────────────────────────────────────
    case 'citas':
        $propId = intval($_GET['propietario'] ?? 0);
        if (!$propId) respuesta(["success" => false, "error" => "Parámetro ?propietario requerido"], 400);

        $conn = getDB();
        $sql = "
            SELECT
                c.id_cita,
                c.fecha_cita,
                c.estado_cita,
                c.problema_contexto,
                c.codigo_confirmacion,
                c.motivo_cancelacion,
                t.nombre_taller,
                t.direccion_taller,
                v.placa,
                CONCAT(mo.nombre_modelo,' ',ma.nombre_marca) AS vehiculo
            FROM cita_taller c
            JOIN taller  t  ON t.id_taller     = c.taller_id_taller
            JOIN vehiculo v ON v.placa          = c.vehiculo_placa
            JOIN modelos  mo ON mo.id_modelo    = v.modelos_id_modelo
            JOIN marcas   ma ON ma.id_marca     = mo.marcas_id_marca
            WHERE v.propietarios_id_propietario = $propId
            ORDER BY c.fecha_cita DESC
        ";
        $res  = $conn->query($sql);
        $rows = [];
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        $conn->close();
        respuesta(["success" => true, "total" => count($rows), "citas" => $rows]);

    // ────────────────────────────────────────
    // POST ?endpoint=nueva_cita
    // Body JSON: { taller_id, placa, fecha_cita, problema_contexto, servicios: [id,...] }
    // ────────────────────────────────────────
    case 'nueva_cita':
        if ($method !== 'POST') respuesta(["success" => false, "error" => "Método no permitido"], 405);

        $tallerId = intval($body['taller_id']        ?? 0);
        $placa    = $conn = null;
        $placa    = $body['placa']                   ?? '';
        $fecha    = $body['fecha_cita']              ?? '';
        $problema = $body['problema_contexto']       ?? '';
        $servicios= $body['servicios']               ?? [];

        if (!$tallerId || !$placa || !$fecha) {
            respuesta(["success" => false, "error" => "Campos requeridos: taller_id, placa, fecha_cita"], 400);
        }

        $conn  = getDB();
        $placa = $conn->real_escape_string($placa);
        $fecha = $conn->real_escape_string($fecha);
        $prob  = $conn->real_escape_string($problema);
        $codigo= strtoupper(substr(md5(uniqid()), 0, 8));

        $sql = "INSERT INTO cita_taller
                    (fecha_cita, problema_contexto, estado_cita, codigo_confirmacion, taller_id_taller, vehiculo_placa)
                VALUES ('$fecha', '$prob', 'pendiente', '$codigo', $tallerId, '$placa')";

        if (!$conn->query($sql)) {
            respuesta(["success" => false, "error" => $conn->error], 500);
        }
        $idCita = $conn->insert_id;

        // Insertar servicios seleccionados
        foreach ($servicios as $servId) {
            $servId = intval($servId);
            $conn->query("INSERT IGNORE INTO cita_has_servicio
                          (cita_taller_id_cita, taller_id_taller, servicios_id_servicio)
                          VALUES ($idCita, $tallerId, $servId)");
        }

        $conn->close();
        respuesta([
            "success"             => true,
            "mensaje"             => "Cita registrada exitosamente",
            "id_cita"             => $idCita,
            "codigo_confirmacion" => $codigo
        ]);

    // ────────────────────────────────────────
    // GET  ?endpoint=vehiculos&propietario=5
    // Vehículos registrados de un propietario
    // ────────────────────────────────────────
    case 'vehiculos':
        $propId = intval($_GET['propietario'] ?? 0);
        if (!$propId) respuesta(["success" => false, "error" => "Parámetro ?propietario requerido"], 400);

        $conn = getDB();
        $sql = "
            SELECT
                v.placa,
                v.model_year,
                v.estado_vehi,
                ma.nombre_marca,
                mo.nombre_modelo,
                tv.texto_tipo_vehi AS tipo,
                sv.texto_servicio  AS combustible
            FROM vehiculo v
            JOIN modelos mo              ON mo.id_modelo    = v.modelos_id_modelo
            JOIN marcas  ma              ON ma.id_marca     = mo.marcas_id_marca
            JOIN tipos_vehiculo tv       ON tv.id_tipo_vehi = v.tipos_vehiculo_id_tipo_vehi
            JOIN servicio_vehiculo sv    ON sv.id_tipo_servicio = v.servicio_vehiculo_id_tipo_servicio
            WHERE v.propietarios_id_propietario = $propId
        ";
        $res  = $conn->query($sql);
        $rows = [];
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        $conn->close();
        respuesta(["success" => true, "vehiculos" => $rows]);

    // ────────────────────────────────────────
    // GET  ?endpoint=notificaciones&propietario=5
    // Notificaciones activas de documentos vencidos
    // ────────────────────────────────────────
    case 'notificaciones':
        $propId = intval($_GET['propietario'] ?? 0);
        if (!$propId) respuesta(["success" => false, "error" => "Parámetro ?propietario requerido"], 400);

        $conn = getDB();
        $sql = "
            SELECT gn.id_notificacion, gn.titulo_notificacion,
                   gn.tipo_notificacion, gn.mensaje_notificacion,
                   gn.estado_notificacion, gn.fecha_registro,
                   pv.vehiculo_placa, td.nombre_documento
            FROM gestion_notificaciones gn
            JOIN papeles_vehiculo pv ON pv.id_papel   = gn.papeles_vehiculo_id_papel
            JOIN tipo_documento   td ON td.id_documento = pv.tipo_documento_id_documento
            JOIN vehiculo v          ON v.placa        = pv.vehiculo_placa
            WHERE v.propietarios_id_propietario = $propId
              AND gn.estado_notificacion = TRUE
            ORDER BY gn.fecha_registro DESC
        ";
        $res  = $conn->query($sql);
        $rows = [];
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        $conn->close();
        respuesta(["success" => true, "total" => count($rows), "notificaciones" => $rows]);

    // ────────────────────────────────────────
    // Endpoint no encontrado
    // ────────────────────────────────────────
    default:
        respuesta([
            "success"   => false,
            "error"     => "Endpoint '$endpoint' no encontrado",
            "endpoints" => [
                "GET  ?endpoint=estado"                          => "Verifica que la API funciona",
                "GET  ?endpoint=talleres"                        => "Lista de talleres activos",
                "GET  ?endpoint=taller&id=N"                     => "Detalle de un taller",
                "GET  ?endpoint=citas&propietario=N"             => "Citas de un propietario",
                "GET  ?endpoint=vehiculos&propietario=N"         => "Vehículos de un propietario",
                "GET  ?endpoint=notificaciones&propietario=N"    => "Notificaciones activas",
                "POST ?endpoint=nueva_cita"                      => "Registrar una nueva cita"
            ]
        ], 404);
}
?>
