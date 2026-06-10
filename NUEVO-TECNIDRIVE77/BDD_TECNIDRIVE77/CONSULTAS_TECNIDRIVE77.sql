USE TECNIDRIVE77;


/* ================================================================
   1. AUTENTICACIÓN Y GESTIÓN DE USUARIOS
   ================================================================ */
-- 1.1 Todas las personas registradas con su rol asignado
--     Verifica: personas → roles_has_persona → roles
SELECT
    p.id_persona,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS nombre_completo,
    p.email,
    r.texto_rol                                      AS rol,
    p.fecha_registro
FROM personas p
JOIN roles_has_persona rhp ON rhp.personas_id_persona = p.id_persona
JOIN roles r               ON r.id_rol = rhp.roles_id_rol
ORDER BY p.fecha_registro DESC;


-- 1.2 Personas que tienen MÁS DE UN rol asignado
--     Útil para detectar usuarios con roles duplicados o combinados
SELECT
    p.id_persona,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS nombre_completo,
    p.email,
    COUNT(rhp.roles_id_rol)                          AS cantidad_roles,
    GROUP_CONCAT(r.texto_rol ORDER BY r.texto_rol SEPARATOR ', ') AS roles
FROM personas p
JOIN roles_has_persona rhp ON rhp.personas_id_persona = p.id_persona
JOIN roles r               ON r.id_rol = rhp.roles_id_rol
GROUP BY p.id_persona, p.primer_nombre, p.primer_apellido, p.email
HAVING COUNT(rhp.roles_id_rol) > 1;



/* ================================================================
   2. PROCESO DE PROPIETARIO
   ================================================================ */

-- 2.1 Propietarios con sus categorías de licencia y vigencias
--     Verifica: propietarios → categoria_has_propietario → categoria_licencia
SELECT
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    pr.numero_licencia,
    pr.telefono_propietario,
    cl.tipo_categoria                                AS categoria,
    chp.vigencia_lice                                AS vigencia,
    CASE WHEN chp.estado_lice = TRUE THEN 'Vigente'
         ELSE 'Vencida' END                          AS estado_licencia
FROM propietarios pr
JOIN personas p ON p.id_persona = pr.id_propietario
JOIN categoria_has_propietario chp
     ON chp.propietarios_id_propietario = pr.id_propietario
JOIN categoria_licencia cl
     ON cl.id_categoria = chp.categoria_licencia_id_categoria
ORDER BY propietario, cl.tipo_categoria;


-- 2.2 Vehículos registrados por cada propietario
--     Verifica: propietarios → vehiculo → modelos → marcas → tipos_vehiculo
SELECT
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    v.placa,
    m.nombre_marca                                   AS marca,
    mo.nombre_modelo                                 AS modelo,
    v.model_year                                     AS anio,
    tv.texto_tipo_vehi                               AS tipo_vehiculo,
    sv.texto_servicio                                AS tipo_combustible,
    CASE WHEN v.estado_vehi = TRUE THEN 'Activo'
         ELSE 'Inactivo' END                         AS estado
FROM propietarios pr
JOIN personas p       ON p.id_persona  = pr.id_propietario
JOIN vehiculo v       ON v.propietarios_id_propietario = pr.id_propietario
JOIN modelos mo       ON mo.id_modelo  = v.modelos_id_modelo
JOIN marcas m         ON m.id_marca    = mo.marcas_id_marca
JOIN tipos_vehiculo tv ON tv.id_tipo_vehi = v.tipos_vehiculo_id_tipo_vehi
JOIN servicio_vehiculo sv ON sv.id_tipo_servicio = v.servicio_vehiculo_id_tipo_servicio
ORDER BY propietario, v.placa;


/* ================================================================
   3. PROCESO DE VEHÍCULO Y DOCUMENTOS
   ================================================================ */

-- 3.1 Cada vehículo con todos sus documentos, estado y vencimiento
--     Verifica: vehiculo → papeles_vehiculo → tipo_documento
SELECT
    v.placa,
    CONCAT(m.nombre_marca, ' ', mo.nombre_modelo) AS vehiculo,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    td.nombre_documento AS tipo_documento,
    pv.fecha_vencimiento,

    CASE
        WHEN pv.fecha_vencimiento < CURDATE() THEN 'Vencido'
        ELSE 'Vigente'
    END AS estado_documento,

    DATEDIFF(pv.fecha_vencimiento, CURDATE()) AS dias_para_vencer

FROM vehiculo v
JOIN propietarios pr ON pr.id_propietario = v.propietarios_id_propietario
JOIN personas p ON p.id_persona = pr.id_propietario
JOIN modelos mo ON mo.id_modelo = v.modelos_id_modelo
JOIN marcas m ON m.id_marca = mo.marcas_id_marca
JOIN papeles_vehiculo pv ON pv.vehiculo_placa = v.placa
JOIN tipo_documento td ON td.id_documento = pv.tipo_documento_id_documento

ORDER BY v.placa, pv.fecha_vencimiento;


-- 3.2 Vehículos con al menos un documento VENCIDO
--     Útil para verificar que el SP de vigencia actualizó correctamente
SELECT
    v.placa,
    CONCAT(m.nombre_marca, ' ', mo.nombre_modelo) AS vehiculo,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    td.nombre_documento,
    pv.fecha_vencimiento
FROM vehiculo v
JOIN propietarios pr  ON pr.id_propietario = v.propietarios_id_propietario
JOIN personas p       ON p.id_persona      = pr.id_propietario
JOIN modelos mo       ON mo.id_modelo      = v.modelos_id_modelo
JOIN marcas m         ON m.id_marca        = mo.marcas_id_marca
JOIN papeles_vehiculo pv ON pv.vehiculo_placa  = v.placa
JOIN tipo_documento td   ON td.id_documento   = pv.tipo_documento_id_documento
WHERE pv.estado_papel = FALSE
ORDER BY pv.fecha_vencimiento;


/* ================================================================
   4. PROCESO DE REGISTRO DE TALLERES
   ================================================================ */

-- 4.1 Talleres con su mecánico responsable y estado
-- Verifica: taller → mecanicos → personas
-- Verifica: taller → taller_especialidad → especialidades
SELECT
    t.id_taller,
    t.nombre_taller,
    t.direccion_taller,
    t.horario_taller,

    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS mecanico_responsable,

    p.email AS contacto,

    GROUP_CONCAT(
        e.nombre_especialidad
        ORDER BY e.nombre_especialidad
        SEPARATOR ' | '
    ) AS especialidades,

    CASE
        WHEN t.estado_taller = TRUE THEN 'Activo'
        ELSE 'Inactivo'
    END AS estado,

    t.motivo_estado

FROM taller t

JOIN mecanicos me
    ON me.id_mecanico = t.mecanicos_id_mecanico

JOIN personas p
    ON p.id_persona = me.id_mecanico

LEFT JOIN taller_especialidad te
    ON te.taller_id_taller = t.id_taller

LEFT JOIN especialidades e
    ON e.id_especialidad = te.especialidades_id_especialidad

GROUP BY
    t.id_taller,
    t.nombre_taller,
    t.direccion_taller,
    t.horario_taller,
    p.primer_nombre,
    p.primer_apellido,
    p.email,
    t.estado_taller,
    t.motivo_estado

ORDER BY t.nombre_taller;


-- 4.3 Servicios y precios ofrecidos por cada taller
--     Verifica: taller → taller_has_servicios → servicios
SELECT
    t.nombre_taller,
    s.nombre_servicio,
    CONCAT('$', FORMAT(ths.precio_servicio, 2)) AS precio
FROM taller t
JOIN taller_has_servicios ths ON ths.taller_id_taller       = t.id_taller
JOIN servicios s              ON s.id_servicio = ths.servicios_id_servicio
ORDER BY t.nombre_taller, s.nombre_servicio;


/* ================================================================
   5. PROCESO DE CITAS
   ================================================================ */

-- 5.1 Citas con propietario, vehículo, taller y estado actual
--     Verifica: cita_taller → vehiculo → propietarios → taller
SELECT
    c.id_cita,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    v.placa,
    CONCAT(ma.nombre_marca, ' ', mo.nombre_modelo)  AS vehiculo,
    t.nombre_taller,
    c.fecha_cita,
    c.estado_cita,
    c.problema_contexto
FROM cita_taller c
JOIN vehiculo v    ON v.placa              = c.vehiculo_placa
JOIN propietarios pr ON pr.id_propietario = v.propietarios_id_propietario
JOIN personas p    ON p.id_persona        = pr.id_propietario
JOIN modelos mo    ON mo.id_modelo        = v.modelos_id_modelo
JOIN marcas ma     ON ma.id_marca         = mo.marcas_id_marca
JOIN taller t      ON t.id_taller         = c.taller_id_taller
ORDER BY c.fecha_cita DESC;

-- 5.3 Servicios seleccionados en cada cita con su precio
--     Verifica: cita_has_servicio → taller_has_servicios → servicios
SELECT
    c.id_cita,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    t.nombre_taller,
    c.fecha_cita,
    s.nombre_servicio,
    CONCAT('$', FORMAT(ths.precio_servicio, 2))     AS precio_servicio
FROM cita_taller c
JOIN cita_has_servicio chs
     ON chs.cita_taller_id_cita = c.id_cita
JOIN taller_has_servicios ths
     ON ths.taller_id_taller   = chs.taller_id_taller
    AND ths.servicios_id_servicio = chs.servicios_id_servicio
JOIN servicios s  ON s.id_servicio = ths.servicios_id_servicio
JOIN taller t     ON t.id_taller   = c.taller_id_taller
JOIN vehiculo v   ON v.placa       = c.vehiculo_placa
JOIN propietarios pr ON pr.id_propietario = v.propietarios_id_propietario
JOIN personas p   ON p.id_persona  = pr.id_propietario
ORDER BY c.id_cita, s.nombre_servicio;



/* ================================================================
   6. PROCESO DE MANTENIMIENTO
   ================================================================ */

-- 6.1 Relación completa: cita → mantenimiento → técnicos asignados
--     Verifica: gestion_mantenimiento → cita_taller → mantenimiento_has_tecnico → tecnicos
--     También verifica que el trigger trg_crear_gestion_mantenimiento
--     creó el registro automáticamente al pasar la cita a 'en_atencion'
SELECT
    gm.id_seguimiento,
    c.id_cita,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    v.placa,
    t.nombre_taller,
    c.fecha_inicio_atencion,
    gm.estado_mantenimiento,
    GROUP_CONCAT(
        tec.nombre_tecnico ORDER BY tec.nombre_tecnico SEPARATOR ', '
    )                                                AS tecnicos_asignados,
    gm.observaciones_tecnico
FROM gestion_mantenimiento gm
JOIN cita_taller c   ON c.id_cita    = gm.cita_taller_id_cita
JOIN taller t        ON t.id_taller  = c.taller_id_taller
JOIN vehiculo v      ON v.placa      = c.vehiculo_placa
JOIN propietarios pr ON pr.id_propietario = v.propietarios_id_propietario
JOIN personas p      ON p.id_persona = pr.id_propietario
LEFT JOIN mantenimiento_has_tecnico mht
          ON mht.gestion_mantenimiento_id_seguimiento = gm.id_seguimiento
LEFT JOIN tecnicos tec ON tec.id_tecnico = mht.tecnicos_id_tecnico
GROUP BY
    gm.id_seguimiento, c.id_cita,
    p.primer_nombre, p.primer_apellido,
    v.placa, t.nombre_taller,
    c.fecha_inicio_atencion,
    gm.estado_mantenimiento,
    gm.observaciones_tecnico
ORDER BY gm.id_seguimiento DESC;


-- 6.2 Mantenimientos finalizados con información de garantía
--     Solo muestra registros que tienen garantía registrada
SELECT
    gm.id_seguimiento,
    c.id_cita,
    v.placa,
    CONCAT(ma.nombre_marca, ' ', mo.nombre_modelo) AS vehiculo,
    t.nombre_taller,
    gm.fecha_cierre,
    CONCAT('$', FORMAT(gm.precio_total, 2))        AS precio_total,
    gm.garantia_vigencia,
    gm.texto_garantia,
    CASE
        WHEN gm.garantia_vigencia > NOW() THEN 'Garantía vigente'
        ELSE 'Garantía vencida'
    END                                             AS estado_garantia
FROM gestion_mantenimiento gm
JOIN cita_taller c ON c.id_cita   = gm.cita_taller_id_cita
JOIN taller t      ON t.id_taller = c.taller_id_taller
JOIN vehiculo v    ON v.placa     = c.vehiculo_placa
JOIN modelos mo    ON mo.id_modelo = v.modelos_id_modelo
JOIN marcas ma     ON ma.id_marca  = mo.marcas_id_marca
WHERE gm.estado_mantenimiento = 'finalizada'
  AND gm.garantia_vigencia IS NOT NULL
ORDER BY gm.garantia_vigencia DESC;

/* ================================================================
   7. PROCESO DE NOTIFICACIONES
   ================================================================ */

-- 7.1 Notificaciones con el documento y vehículo relacionado
--     Verifica: gestion_notificaciones → papeles_vehiculo → vehiculo → tipo_documento
SELECT
    gn.id_notificacion,
    gn.titulo_notificacion,
    gn.tipo_notificacion,
    gn.mensaje_notificacion,
    td.nombre_documento                             AS documento,
    pv.fecha_vencimiento,
    v.placa,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS propietario,
    CASE WHEN gn.estado_notificacion = TRUE THEN 'Activa'
         ELSE 'Resuelta' END                        AS estado_notificacion,
    gn.fecha_registro                               AS fecha_generacion
FROM gestion_notificaciones gn
JOIN papeles_vehiculo pv ON pv.id_papel        = gn.papeles_vehiculo_id_papel
JOIN tipo_documento td   ON td.id_documento    = pv.tipo_documento_id_documento
JOIN vehiculo v          ON v.placa            = pv.vehiculo_placa
JOIN propietarios pr     ON pr.id_propietario  = v.propietarios_id_propietario
JOIN personas p          ON p.id_persona       = pr.id_propietario
ORDER BY gn.fecha_registro DESC;
                                                                                                                                                               /
