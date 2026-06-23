create database TECNIDRIVE777;
use TECNIDRIVE777;


--PARTE DE AUTENTIFICAR PERSONAS Y ROLES
create table roles(
    id_rol int not null,
    texto_rol varchar(15) not null,
    primary key(id_rol)
);

--datos ya definidos como constants que no cambiaran
insert into roles VALUES
(1,'Administrador'),
(2,'Propietario'),
(3,'Mecanico');

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


--para futuro arreglo una persona podra tener dos roles premiun
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


--SELECCION DE LA PERSONA PROPIETARIO 
create table propietarios(
    id_propietario int not null,--es la llave que hereda de la persona es para el login correcto
    telefono_propietario bigint not null,
    numero_licencia varchar(11),--si es requerida pero al inicio no ya que genera error en sesion al ser requerido por eso es null
    primary key(id_propietario)
);

alter table propietarios
add constraint relacion_persona_propietario
foreign key(id_propietario)
references personas(id_persona);


--SECCION MODULO PARA LA CATEGORIA DE LA LICENCIA
create table categoria_licencia(
    id_categoria int not null,
    tipo_categoria varchar(3) not null,
    primary key(id_categoria)
);

--datos ya definidos como constants que no cambiaran
insert into categoria_licencia values
(1,'A1'),(2,'A2'),(3,'B1'),(4,'B2'),(5,'C1'),(6,'C2'),(7,'C3');

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


--SELECCION DE LA PERSONA MECANICO(DUEÑO O EL QUE REGISTRA EL TALLER)
create table mecanicos(
    id_mecanico int not null,--es la llave que hereda de la persona es para el login correcto
    telefono_mecanico bigint not null,
    primary key(id_mecanico)
);

alter table mecanicos
add constraint relacion_persona_mecanico
foreign key (id_mecanico)
references personas(id_persona);


--PARTE PRINCIPAL MODULO DE REGISTRO DEL TALLER 
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

--seleccionar servicios de acuerdo a cada taller para tener  precios diferentes cada uno
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


--SECCION MODULO PARA PROPIETARIO Y MECANICO PARA LA CALIFIACION DEL TALLER
create table calificacion_taller(
    id_calificacion int not null auto_increment,
    fecha_registro datetime not null default CURRENT_TIMESTAMP,
    puntuacion int not null,
    comentario text,
    estado ENUM('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'aprobada',--SECCION PARA LA RESEÑA Y QUE EL ADMIN VALIDE SI ES APTO
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


--SECCION MODULO DEL VEHICULO REGISTRO DE EL CON SUS DEMAS DATOS
create table servicio_vehiculo(
    id_tipo_servicio int not null,
    texto_servicio varchar(20) not null,
    primary key(id_tipo_servicio)
);
insert into servicio_vehiculo values
(1, 'Particular'), (2, 'Público');

create table tipos_vehiculo(
    id_tipo_vehi int not null,
    texto_tipo_vehi varchar(20) not null,
    primary key(id_tipo_vehi)
);
insert into tipos_vehiculo
 values(1, 'Carro'), (2, 'Moto');

create table marcas(
    id_marca int not null AUTO_INCREMENT,
    nombre_marca varchar(25) not null,
    primary key(id_marca)
);

create table modelos(
    id_modelo int not null AUTO_INCREMENT,
    nombre_modelo varchar(28) not null,
    marcas_id_marca int not null,
    primary key(id_modelo)
);

alter table modelos
add constraint relacion_marca_modelo
foreign key(marcas_id_marca)
references marcas(id_marca);

--registro del vehiculo(se quitaron unos datos que no usaremos en caso de usarlos en el futuro se agregaran)
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

--relacion vehiculo y el servicio(particular o publico)
alter table vehiculo
add constraint relacion_servicio_vehiculo
foreign key(servicio_vehiculo_id_tipo_servicio)
references servicio_vehiculo(id_tipo_servicio);

--relacion tipo de vehiculo(carro o moto) con vehiculo
alter table vehiculo
add constraint relacion_tipo_vehiculo
foreign key(tipos_vehiculo_id_tipo_vehi)
references tipos_vehiculo(id_tipo_vehi);

--relacion vehiculo con modelo
alter table vehiculo
add constraint relacion_vehiculo_modelo
foreign key(modelos_id_modelo)
references modelos(id_modelo);

--relacion propietario vehiculo
alter table vehiculo
add constraint relacion_propietario_vehiculo
foreign key(propietarios_id_propietario)
references propietarios(id_propietario);


--=================================================================================================================================
--PRINCIPAL SESSION TENER CUIDADO POSIBLES CAMBIOS REGISTRO DE CITA(SOLICITUD DE RESERVA PARA EL TALLER DE REPARACION DEL VEHICULO)
--==================================================================================================================================

--SECCION INICIAL PARA LA CITA Y DONDE EL PROPIETARO PODRA VER SUS DATOS
create table cita_taller(
    id_cita int not null auto_increment,
    fecha_registro datetime not null default CURRENT_TIMESTAMP,
    fecha_cita datetime not null, --el horario que se solicita comenzar 
    problema_contexto varchar(255),-- si no sabe los servicios que necesita
    estado_cita enum(
        'pendiente',
        'confirmada',
        'en_atencion',          
        'cancelada_propietario',
        'cancelada_mecanico'
    ) not null default 'pendiente',-- inportante no es 1 o 2 ya que solo seria activar y desactivar el proceso, entonces se cambio a enum ya que va por etapas y estas son las iniciales
    codigo_confirmacion varchar(10) unique,
    fecha_inicio_atencion date,--cuando se verifica el codigo del propietario al llegar se registra para comenzar la reparacion
    cancelado_por enum('propietario', 'mecanico'), --cuando alguno la cancela para saber quien fue
    motivo_cancelacion varchar(100),  
    taller_id_taller int not null,
    vehiculo_placa varchar(6) not null,--para unir el vehiculo y que el mecanico sepa mejor los datos por el momento placa y modelmar
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

--seccion nueva para saber que servicios a elegido el propietario para su cita
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
add constraint relacion_cita_taller_servicios
foreign key(taller_id_taller, servicios_id_servicio)
references taller_has_servicios(taller_id_taller, servicios_id_servicio);

--=====================================================================================================
--PARTE DOS DE LA GESTION DE LA CITA EL HISTORIA Y PARTE FINAL PARA GUARDAR BIEN LOS DATOS DEL PROCESO
--======================================================================================================
--el control de las citas
create table gestion_mantenimiento(
    id_seguimiento int not null auto_increment,
    cita_taller_id_cita int not null unique,
    observaciones_tecnico text,--lo que los trabajadores de la reparacion vieron al arreglarlo
    precio_total decimal(10,2),--por el momneto solo se escribe pero con los datos de los servicios quizas se estime bien algo asi
    garantia_vigencia datetime,--la fecha si aplica la garantia hasta donde va
    texto_garantia text,--en que hay garantia de la reparacion
    estado_mantenimiento enum(        
        'en_atencion',
        'en_cierre',
        'finalizada'
    ) not null default 'en_atencion', --importante estados finales para la gestion de la cita 
      codigo_entrega varchar(10) unique,--al momento de llenar los datos del proceso de cierre cambiara a estado en cierre y mandara el codigo al propietario
    fecha_cierre date,--la fecha donde se entega el vehiculo ya reparado atra ves del codigo de verificacion
    primary key(id_seguimiento)
);

alter table gestion_mantenimiento
add constraint relacion_seguimiento_cita
foreign key(cita_taller_id_cita)
references cita_taller(id_cita);


--los tecnicos(los trabajadores de ese taller que ayudaron a la reparacion del vehiculo)
create table tecnicos(
    id_tecnico int not null auto_increment,
    taller_id_taller INT NOT NULL,--relacion importante para que los tecnicos agregados corespondan al daller quien los agrega y no sea de todos con todos
    nombre_tecnico varchar(100),--solo nombre como dato ya que este no interactua con el sistema es solo un atributo para describir
    primary key(id_tecnico)
);

--relacion de trabajadores con el taller
ALTER TABLE tecnicos
    ADD CONSTRAINT relacion_tecnico_taller
    FOREIGN KEY (taller_id_taller)
    REFERENCES taller(id_taller);

--relacion de los tecnicos con la cita que se finaliza
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


--ULTIMAS SECCIONES DEL PROPIETARIO PAPELES Y NOTIFIACIONES

--tabla solo con el nombre de los documentos
create table tipo_documento(
    id_documento int not null,
    nombre_documento varchar(30) not null,
    primary key(id_documento)
);
insert into tipo_documento values(1, 'SOAT'), (2, 'Tecnomecánica');

--los datos de esos documentos
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


--PARTE AUN NO REALIZADA EN PHP DE NOTIFIACACIONES PARA EL PROPIETARIO(POSIBLE CAMBIO PARA TEMA DE CITAS)
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



--==================================================================
--==================================================================
--==================================================================



--SECCION FINAL PROCEDIMIENTOS UTILIZADOS Y POSIBLES A UTILIZAR
--PROCEDIMIENTO 1 para calcular promedio de califiaciones utilizado para encontrar talleres de 1,2,3,4,5 estrellas en el protaller
DELIMITER $$

CREATE PROCEDURE sp_promedio_calificaciones(
    IN p_id_taller INT
)
BEGIN
    SELECT
        t.id_taller,
        t.nombre_taller,
        COUNT(ct.id_calificacion)              AS total_calificaciones,
        ROUND(AVG(ct.puntuacion), 2)           AS promedio_estrellas,
        CASE
            WHEN AVG(ct.puntuacion) >= 4.5 THEN 'Excelente'
            WHEN AVG(ct.puntuacion) >= 3.5 THEN 'Bueno'
            WHEN AVG(ct.puntuacion) >= 2.5 THEN 'Regular'
            ELSE                                 'Bajo'
        END                                    AS categoria_valoracion
    FROM taller t
    LEFT JOIN calificacion_taller ct
           ON ct.taller_id_taller = t.id_taller
          AND ct.estado = 'aprobada'
    WHERE (p_id_taller IS NULL OR t.id_taller = p_id_taller)
      AND t.estado_taller = TRUE
    GROUP BY t.id_taller, t.nombre_taller
    ORDER BY promedio_estrellas DESC;
END$$

DELIMITER ;



--DISPARADOR (TIGGER) AYUDA AL CONTROL DE CITAS A PASAR LA CITA NORMAL QUE ESTA EN ESTADO DE EN ATENCION AL CONTROL DE CITAS O EL HISTORIAL(GESTION MATENIMINETO)
DELIMITER $$

CREATE TRIGGER trg_crear_gestion_mantenimiento
AFTER UPDATE ON cita_taller
FOR EACH ROW
BEGIN
    -- Solo dispara cuando el estado cambia exactamente a 'en_atencion'
    IF NEW.estado_cita = 'en_atencion'
       AND OLD.estado_cita != 'en_atencion'
    THEN
        -- Crear la ficha de gestión si aún no existe.
        -- INSERT IGNORE previene duplicados si la cita ya tuviera ficha.
        -- fecha_inicio_atencion la maneja PHP en el UPDATE de cita_taller,
        -- no se toca aquí para evitar el error 1442.
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
