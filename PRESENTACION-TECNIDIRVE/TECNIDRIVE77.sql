/*26 TABLAS EN TOTAL CON LA NUEVA FUNCION DE MANTENIMIENTO*/


create database TECNIDRIVE77;
use TECNIDRIVE77;



create table roles(
    id_rol int not null,
    texto_rol varchar(15) not null,
    primary key(id_rol)
);

create table personas(
   id_persona  int not null auto_increment, 
   primer_nombre varchar(30) not null,
   segundo_nombre varchar(30),
   primer_apellido varchar(25) not null,
   segundo_apellido varchar(25) not null,
   email varchar(60) not null unique,
   password_hash varchar(255) not null,
   codigo_recuperacion varchar(10),
   fecha_expiracion datetime,
   fecha_registro datetime not null default CURRENT_TIMESTAMP,
   avatarcolor varchar(15),
    primary key(id_persona)
);

create table roles_has_persona(
    roles_id_rol int not null, 
    personas_id_persona int not null,
    primary key (roles_id_rol, personas_id_persona)
);

alter table roles_has_persona
add constraint relacion_rol_person
foreign key (roles_id_rol)
references roles(id_rol);
 
alter table roles_has_persona
add constraint relacion_person_rol
foreign key (personas_id_persona)
references personas(id_persona);



create table propietarios(
    id_propietario int not null,
    telefono_propietario bigint not null,
    numero_licencia varchar(11) not,
    primary key(id_propietario)
);

alter table propietarios
add constraint relacion_persona_propietario
foreign key(id_propietario)
references personas(id_persona);

create table categoria_licencia(
    id_categoria int not null,
    tipo_categoria varchar(3) not null,
    primary key(id_categoria)
);

create table categoria_has_propietario(
    categoria_licencia_id_categoria int not null,
    propietarios_id_propietario int not null,
    vigencia_lice date not null,
    estado_lice boolean not null,
    primary key(categoria_licencia_id_categoria, propietarios_id_propietario)
);

alter table categoria_has_propietario
add constraint relacion_categoria_propietario
foreign key(categoria_licencia_id_categoria)
references categoria_licencia(id_categoria);

alter table categoria_has_propietario
add constraint relacion_propietario_categoria
foreign key(propietarios_id_propietario)
references propietarios(id_propietario);

create table mecanicos(
    id_mecanico int not null,
    telefono_mecanico bigint not null,
    primary key(id_mecanico)
);

alter table mecanicos
add constraint relacion_persona_mecanico
foreign key (id_mecanico)
references personas(id_persona);


create table taller(
    id_taller int not null auto_increment,
    foto_taller varchar(255),
    nombre_taller varchar(80) not null,
    descripcion_taller varchar(150) not null,
    direccion_taller varchar(80) not null,
    horario_taller varchar(150) not null,
    fecha_registro datetime not null default CURRENT_TIMESTAMP,
    estado_taller boolean not null,
    motivo_estado varchar(100),
    mecanicos_id_mecanico int not null,
    primary key(id_taller)
);

alter table taller
add constraint relacion_mecanico_taller
foreign key (mecanicos_id_mecanico)
references mecanicos(id_mecanico);

create table servicios(
    id_servicio int not null  auto_increment ,
    nombre_servicio varchar(80) not null,
    primary key(id_servicio)
);

create table taller_has_servicios(
    taller_id_taller int not null,
    servicios_id_servicio int not null,
    precio_servicio decimal(10,2) not null,
    primary key(taller_id_taller, servicios_id_servicio)
);

alter table taller_has_servicios
add constraint relacion_servicios_taller
foreign key(servicios_id_servicio)
references servicios(id_servicio);

alter table taller_has_servicios
add constraint relacion_taller_servicios
foreign key(taller_id_taller)
references taller(id_taller);

create table especialidades(
    id_especialidad int not null  auto_increment,
    nombre_especialidad varchar(70) not null,
    primary key(id_especialidad)
);

create table taller_especialidad(
    taller_id_taller int not null,
    especialidades_id_especialidad int not null,
    primary key(taller_id_taller, especialidades_id_especialidad)
);

alter table taller_especialidad
add constraint relacion_especialidad_taller
foreign key(especialidades_id_especialidad)
references especialidades(id_especialidad);

alter table taller_especialidad
add constraint relacion_taller_especialidad
foreign key(taller_id_taller)
references taller(id_taller);


/*SI ERA ESTO ERROR*/
create table calificacion_taller(
    id_calificacion int not null auto_increment,
    fecha_registro datetime not null default CURRENT_TIMESTAMP,
    puntuacion int not null,
    comentario text,
    taller_id_taller int not null,
    propietarios_id_propietario int not null,
    primary key(id_calificacion)
);

alter table calificacion_taller
add constraint relacion_calificacion_taller
foreign key(taller_id_taller)
references taller(id_taller);

alter table calificacion_taller
add constraint relacion_calificacion_propietario
foreign key(propietarios_id_propietario)
references propietarios(id_propietario);


create table servicio_vehiculo(
    id_tipo_servicio int not null,
    texto_servicio varchar(20) not null,
    primary key(id_tipo_servicio)
);

create table tipos_vehiculo(
    id_tipo_vehi int not null,
    texto_tipo_vehi varchar(20) not null,
    primary key(id_tipo_vehi)
);

create table marcas(
    id_marca int not null,
    nombre_marca varchar(25) not null,
    primary key(id_marca)
);

create table modelos(
    id_modelo int not null,
    nombre_modelo varchar(28) not null,
    marcas_id_marca int not null,
    primary key(id_modelo)
);

alter table modelos
add constraint relacion_marca_modelo
foreign key(marcas_id_marca)
references marcas(id_marca);

create table vehiculo(
    placa varchar(6) not null,
    model_year varchar(4) not null,
    fecha_registro datetime not null default CURRENT_TIMESTAMP,
    estado_vehi boolean not null,
    motivo_estado varchar(100),
    propietarios_id_propietario int not null,
    tipos_vehiculo_id_tipo_vehi int not null,
    modelos_id_modelo int not null,
    servicio_vehiculo_id_tipo_servicio int not null,
    primary key(placa)
);

alter table vehiculo
add constraint relacion_servicio_vehiculo
foreign key(servicio_vehiculo_id_tipo_servicio)
references servicio_vehiculo(id_tipo_servicio);

alter table vehiculo
add constraint relacion_tipo_vehiculo
foreign key(tipos_vehiculo_id_tipo_vehi)
references tipos_vehiculo(id_tipo_vehi);

alter table vehiculo
add constraint relacion_vehiculo_modelo
foreign key(modelos_id_modelo)
references modelos(id_modelo);

alter table vehiculo
add constraint relacion_propietario_vehiculo
foreign key(propietarios_id_propietario)
references propietarios(id_propietario);



create table cita_taller(
    id_cita int not null auto_increment,
    fecha_registro datetime not null default CURRENT_TIMESTAMP,
    fecha_cita datetime not null,
    problema_contexto varchar(255),
    estado_cita enum(
        'pendiente',
        'confirmada',
        'en_atencion',          
        'cancelada_propietario',
        'cancelada_mecanico'
    ) not null default 'pendiente',
    codigo_confirmacion varchar(10) unique,
    fecha_inicio_atencion date,
    codigo_entrega varchar(10) unique,
    cancelado_por enum('propietario', 'mecanico'),
    motivo_cancelacion varchar(100),  
    taller_id_taller int not null,
    vehiculo_placa varchar(6) not null,
    primary key(id_cita)
);

alter table cita_taller
add constraint relacion_vehiculo_cita
foreign key(vehiculo_placa)
references vehiculo(placa);

alter table cita_taller
add constraint relacion_taller_cita
foreign key(taller_id_taller)
references taller(id_taller);



create table cita_has_servicio(
    cita_taller_id_cita   int not null,
    taller_id_taller      int not null,
    servicios_id_servicio int not null,
    primary key(cita_taller_id_cita, taller_id_taller, servicios_id_servicio)
);

alter table cita_has_servicio
add constraint relacion_taller_servicios_cita
foreign key(cita_taller_id_cita)
references cita_taller(id_cita);

alter table cita_has_servicio
add constraint relacion_cita_taller_servicio
foreign key(taller_id_taller, servicios_id_servicio)
references taller_has_servicios(taller_id_taller, servicios_id_servicio);



create table gestion_mantenimiento(
    id_seguimiento int not null auto_increment,
    cita_taller_id_cita int not null unique,
    observaciones_tecnico text not null,
    precio_total decimal(10,2),
    garantia_vigencia datetime,
    texto_garantia text,
    estado_mantenimiento enum(        
        'en_atencion',
        'en_cierre',
        'finalizada'
    ) not null default 'en_atencion',
    fecha_cierre date,
    primary key(id_seguimiento)
);

alter table gestion_mantenimiento
add constraint relacion_seguimiento_cita
foreign key(cita_taller_id_cita)
references cita_taller(id_cita);

create table tecnicos(
    id_tecnico int not null auto_increment,
    nombre_tecnico varchar(100) not null,
    primary key(id_tecnico)
);

create table mantenimiento_has_tecnico(
    gestion_mantenimiento_id_seguimiento int not null,
    tecnicos_id_tecnico int not null,
    primary key(gestion_mantenimiento_id_seguimiento, tecnicos_id_tecnico)
);

alter table mantenimiento_has_tecnico
add constraint relacion_seguimiento_tecnico
foreign key(gestion_mantenimiento_id_seguimiento)
references gestion_mantenimiento(id_seguimiento);

alter table mantenimiento_has_tecnico
add constraint relacion_tecnico_seguimiento
foreign key(tecnicos_id_tecnico)
references tecnicos(id_tecnico);



create table tipo_documento(
    id_documento int not null,
    nombre_documento varchar(30) not null,
    primary key(id_documento)
);

create table papeles_vehiculo(
    id_papel int not null auto_increment,
    fecha_vencimiento date not null,
    estado_papel boolean not null,
    tipo_documento_id_documento int not null,
    vehiculo_placa varchar(6) not null,
    primary key(id_papel)
);

alter table papeles_vehiculo
add constraint relacion_papel_tipo
foreign key(tipo_documento_id_documento)
references tipo_documento(id_documento);

alter table papeles_vehiculo
add constraint relacion_papel_vehiculo
foreign key(vehiculo_placa)
references vehiculo(placa);



create table gestion_notificaciones(
    id_notificacion int not null auto_increment,
    titulo_notificacion varchar(50) not null,
    tipo_notificacion varchar(20) not null,
    mensaje_notificacion text not null,
    estado_notificacion boolean not null,
    fecha_registro datetime not null default CURRENT_TIMESTAMP,
    papeles_vehiculo_id_papel int not null,
    primary key(id_notificacion)
);

alter table gestion_notificaciones
add constraint relacion_notificacion_papel
foreign key(papeles_vehiculo_id_papel)
references papeles_vehiculo(id_papel);



/*👹👹👹PROCEDIMIENTOS BUENOS Y DISPARADORES👹👹👹👹
-- ============================================================
-- STORED PROCEDURE 1
-- Revisa papeles_vehiculo, marca vencidos e inserta
-- notificaciones para cada documento que venció hoy o antes.
-- Recomendado: ejecutar con un Event Scheduler diario.
-- ============================================================
*/

DELIMITER $$

CREATE PROCEDURE sp_revisar_vigencia_documentos()
BEGIN
    DECLARE v_id_papel        INT;
    DECLARE v_placa           VARCHAR(6);
    DECLARE v_nombre_doc      VARCHAR(30);
    DECLARE v_fecha_venc      DATE;
    DECLARE v_fin_cursor      INT DEFAULT 0;

    -- Cursor sobre documentos activos cuya fecha ya venció
    DECLARE cur_docs CURSOR FOR
        SELECT pv.id_papel,
               pv.vehiculo_placa,
               td.nombre_documento,
               pv.fecha_vencimiento
        FROM papeles_vehiculo pv
        JOIN tipo_documento td
          ON td.id_documento = pv.tipo_documento_id_documento
        WHERE pv.estado_papel = TRUE
          AND pv.fecha_vencimiento < CURDATE();

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_fin_cursor = 1;

    OPEN cur_docs;

    loop_docs: LOOP
        FETCH cur_docs INTO v_id_papel, v_placa, v_nombre_doc, v_fecha_venc;

        IF v_fin_cursor = 1 THEN
            LEAVE loop_docs;
        END IF;

        -- 1. Marcar el documento como inactivo (vencido)
        UPDATE papeles_vehiculo
           SET estado_papel = FALSE
         WHERE id_papel = v_id_papel;

        -- 2. Registrar notificación para el propietario del vehículo
        --    Solo si aún no existe una notificación activa para ese papel
        IF NOT EXISTS (
            SELECT 1
            FROM gestion_notificaciones
            WHERE papeles_vehiculo_id_papel = v_id_papel
              AND estado_notificacion = TRUE
        ) THEN
            INSERT INTO gestion_notificaciones (
                titulo_notificacion,
                tipo_notificacion,
                mensaje_notificacion,
                estado_notificacion,
                papeles_vehiculo_id_papel
            ) VALUES (
                CONCAT('Documento vencido: ', v_nombre_doc),
                'vencimiento',
                CONCAT(
                    'El documento "', v_nombre_doc,
                    '" del vehículo con placa ', v_placa,
                    ' venció el ', DATE_FORMAT(v_fecha_venc, '%d/%m/%Y'),
                    '. Por favor, renuévalo a la brevedad.'
                ),
                TRUE,
                v_id_papel
            );
        END IF;

    END LOOP;

    CLOSE cur_docs;
END$$

DELIMITER ;

/*-- Programar ejecución automática diaria a las 6:00 AM
-- (requiere tener el Event Scheduler activo: SET GLOBAL event_scheduler = ON)*/
CREATE EVENT IF NOT EXISTS evt_revisar_documentos
    ON SCHEDULE EVERY 1 DAY
    STARTS (CURRENT_DATE + INTERVAL 6 HOUR)
    DO CALL sp_revisar_vigencia_documentos();


/*-- ============================================================
-- STORED PROCEDURE 2
-- Calcula el promedio de calificaciones de todos los talleres
-- (o de uno específico si se pasa su id).
-- Parámetro: p_id_taller = NULL  →  devuelve todos los talleres
--            p_id_taller = N     →  devuelve solo ese taller
-- ============================================================*/

DELIMITER $$

CREATE PROCEDURE sp_promedio_calificaciones(
    IN p_id_taller INT   -- Pasar NULL para consultar todos
)
BEGIN
    SELECT
        t.id_taller,
        t.nombre_taller,
        COUNT(ct.id_calificacion)              AS total_calificaciones,
        ROUND(AVG(ct.puntuacion), 2)           AS promedio_estrellas,
        -- Convierte el promedio a un texto descriptivo util para el frontend
        CASE
            WHEN AVG(ct.puntuacion) >= 4.5 THEN 'Excelente'
            WHEN AVG(ct.puntuacion) >= 3.5 THEN 'Bueno'
            WHEN AVG(ct.puntuacion) >= 2.5 THEN 'Regular'
            ELSE                                 'Bajo'
        END                                    AS categoria_valoracion
    FROM taller t
    LEFT JOIN calificacion_taller ct
           ON ct.taller_id_taller = t.id_taller
    WHERE (p_id_taller IS NULL OR t.id_taller = p_id_taller)
      AND t.estado_taller = TRUE   -- Solo talleres activos
    GROUP BY t.id_taller, t.nombre_taller
    ORDER BY promedio_estrellas DESC;
END$$

DELIMITER ;

/*-- Ejemplos de uso:
-- CALL sp_promedio_calificaciones(NULL);   -- Todos los talleres
-- CALL sp_promedio_calificaciones(3);      -- Solo el taller con id = 3


-- ============================================================
-- TRIGGER 1
-- Cuando una cita cambia a estado cancelada_propietario
-- o cancelada_mecanico, registra automáticamente quién canceló
-- en el campo cancelado_por.
-- Se usa BEFORE UPDATE para modificar NEW directamente
-- sin necesidad de un segundo UPDATE.
-- ============================================================*/

DELIMITER $$

CREATE TRIGGER trg_registrar_cancelacion
BEFORE UPDATE ON cita_taller
FOR EACH ROW
BEGIN
    -- Solo actúa cuando el estado cambia hacia una cancelación
    IF NEW.estado_cita != OLD.estado_cita THEN

        IF NEW.estado_cita = 'cancelada_propietario' THEN
            SET NEW.cancelado_por = 'propietario';

        ELSEIF NEW.estado_cita = 'cancelada_mecanico' THEN
            SET NEW.cancelado_por = 'mecanico';

        END IF;

    END IF;

    -- Validación de integridad: si se cancela, debe venir un motivo
    IF NEW.estado_cita IN ('cancelada_propietario', 'cancelada_mecanico')
       AND (NEW.motivo_cancelacion IS NULL OR TRIM(NEW.motivo_cancelacion) = '')
    THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Para cancelar una cita debe registrarse un motivo de cancelación.';
    END IF;
END$$

DELIMITER ;


/*-- ============================================================
-- TRIGGER 2 (PROPUESTA ADICIONAL)
-- Cuando una cita pasa al estado "en_atencion", crea
-- automáticamente el registro en gestion_mantenimiento.
--
-- Por qué aporta valor:
--   • Garantiza que TODA cita en atención tenga su ficha
--     de seguimiento. Sin esto, el mecánico podría olvidar
--     crearla manualmente.
--   • Establece fecha_inicio_atencion en cita_taller a la vez.
--   • La transacción es atómica: si el INSERT falla,
--     el UPDATE también se revierte.
-- ============================================================*/

DELIMITER $$

CREATE TRIGGER trg_crear_gestion_mantenimiento
AFTER UPDATE ON cita_taller
FOR EACH ROW
BEGIN
    -- Solo dispara cuando el estado cambia exactamente a 'en_atencion'
    IF NEW.estado_cita = 'en_atencion'
       AND OLD.estado_cita != 'en_atencion'
    THEN
        -- Registrar la fecha de inicio de atención en la misma cita
        UPDATE cita_taller
           SET fecha_inicio_atencion = CURDATE()
         WHERE id_cita = NEW.id_cita;

        -- Crear la ficha de gestión si aún no existe
        -- (la columna cita_taller_id_cita es UNIQUE, así que
        --  el INSERT ignorado previene duplicados si el trigger
        --  se disparara dos veces por algún edge case)
        INSERT IGNORE INTO gestion_mantenimiento (
            cita_taller_id_cita,
            observaciones_tecnico,
            estado_mantenimiento
        ) VALUES (
            NEW.id_cita,
            'Atención iniciada. Pendiente de diagnóstico técnico.',
            'en_atencion'
        );
    END IF;
END$$

DELIMITER ;