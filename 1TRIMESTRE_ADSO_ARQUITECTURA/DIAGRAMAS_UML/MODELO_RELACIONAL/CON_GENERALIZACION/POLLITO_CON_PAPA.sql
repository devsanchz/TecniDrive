
create database tecnidrive_03;
use tecnidrive_03;



/*PARTE DE AUTENTIFCAR*/
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
   contrasena varchar(255) not null,
   fecha_registro datetime not null default CURRENT_TIMESTAMP,
    primary key(id_persona)
);

create table roles_has_persona(
    roles_id_rol int not null, 
    personas_id_persona int not null,
    primary key (roles_id_rol,personas_id_persona)
);

alter table roles_has_persona
add constraint relacion_rol_person
foreign key (roles_id_rol)
references roles(id_rol);
 
alter table roles_has_persona
add constraint relacion_person_rol
foreign key (personas_id_persona)
references personas(id_persona);


/*PARTE DEL PROPIETARIO*/
create table propietarios(
    id_propietario int not null,
    telefono_propietario bigint not null,
    numero_licencia bigint not null,
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

/*==========================
PARTE DEL MECANICO Y TALLER
============================*/
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
    descripcion_taller varchar(100) not null,
    direccion_taller varchar(80) not null,
    horario_taller varchar(80) not null,
     fecha_registro datetime not null default CURRENT_TIMESTAMP,
    estado_taller boolean not null,
    motivo_estado varchar(100) not null default 'Taller activo',
    mecanicos_id_mecanico int not null,
    primary key(id_taller)
);

alter table taller
add constraint relacion_mecanico_taller
foreign key (mecanicos_id_mecanico)
references mecanicos(id_mecanico);

create table servicios(
    id_servicio int not null,
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
    id_especialidad int not null,
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

create table calificacion_taller(
    id_calificacion int not null auto_increment,
 fecha_registro datetime not null default CURRENT_TIMESTAMP,
    puntuacion int not null CHECK (puntuacion BETWEEN 1 AND 5),
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




/*PARTE DEL VEHICULO*/
create table vehiculo(
    placa varchar(6) not null,
    year varchar(4) not null,
    fecha_registro datetime not null  default CURRENT_TIMESTAMP,
    estado_vehi boolean not null,
    motivo_estado varchar(100),
    propietarios_id_propietario int not null,
    tipos_vehiculo_id_tipo_vehi int not null,
    modelos_id_modelo int not null,
    servicio_vehiculo_id_tipo_servicio int not null,
    primary key(placa)
);

create table servicio_vehiculo(
    id_tipo_servicio int not null,
    texto_servicio varchar(20) not null,
    primary key(id_tipo_servicio)
);

alter table vehiculo
add constraint relacion_servicio_vehiculo
foreign key(servicio_vehiculo_id_tipo_servicio)
references servicio_vehiculo(id_tipo_servicio);

create table tipos_vehiculo(
id_tipo_vehi int not null,
texto_tipo_vehi varchar(20) not null,
primary key(id_tipo_vehi)
);

alter table vehiculo
add constraint relacion_tipo_vehiculo
foreign key( tipos_vehiculo_id_tipo_vehi)
references tipos_vehiculo(id_tipo_vehi);

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

alter table vehiculo
add constraint relacion_vehiculo_modelo
foreign key(modelos_id_modelo)
references modelos(id_modelo);

alter table vehiculo
add constraint relacion_propietario_vehiculo
foreign key(propietarios_id_propietario)
references propietarios(id_propietario);



/*PARTE DEL MATENIMIENTO Y SEGUIMIENTO DE VEHICULO*/
create table cita_taller(
    id_cita int not null auto_increment,
    fecha_cita datetime not null,
    problema_contexto varchar(255),
    estado_cita boolean not null,
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


/* SERVICIOS OPCIONALES SELECCIONADOS AL AGENDAR LA CITA */
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

/*NUEVA PARTE DE MANTENIMIENTO*/
create table gestion_mantenimiento(
    id_seguimiento int  not null auto_increment,
    cita_taller_id_cita int not null,
    observaciones_tecnico text not null,
    precio_total decimal(10,2) not null,
   garantia_vigencia datetime,
    texto_garantia text,
    estado_proceso boolean not null,
    primary key(id_seguimiento)
);

alter table gestion_mantenimiento
add constraint relacion_seguimiento_cita
foreign key(cita_taller_id_cita)
references cita_taller(id_cita);

 create table tecnicos(
    id_tecnico int not null auto_increment,
    nombre_tecnico varchar (100) not null,

    primary key(id_tecnico)
 );

 create table mantenimiento_has_tecnico(
   gestion_mantenimiento_id_seguimiento int not null,
    tecnicos_id_tecnico int not null,
    primary key(gestion_mantenimiento_id_seguimiento, tecnicos_id_tecnico )
    );

 alter table mantenimiento_has_tecnico
add constraint relacion_seguimiento_tecnico
foreign key(  gestion_mantenimiento_id_seguimiento )
references gestion_mantenimiento(id_seguimiento);

 alter table mantenimiento_has_tecnico
add constraint relacion_tecnico_seguimiento
foreign key( tecnicos_id_tecnico )
references tecnicos(id_tecnico);



/*PARTE DE PAPELES VEHICULO*/
create table papeles_vehiculo(
    id_papel int not null auto_increment,
    fecha_vencimiento date not null,
    estado_papel boolean not null,
    tipo_documento_id_documento int not null,
    vehiculo_placa varchar(6) not null,
    primary key(id_papel)
);
create table tipo_documento(
    id_documento int not null,
    nombre_documento varchar(30) not null,
    primary key(id_documento)
);

alter table papeles_vehiculo
add constraint relacion_papel_tipo
foreign key(  tipo_documento_id_documento)
references tipo_documento(id_documento);

alter table papeles_vehiculo
add constraint relacion_papel_vehiculo
foreign key(vehiculo_placa)
references vehiculo(placa);

/*PARTE DE GESTIONAR NOTIFIACIONES SISTEMA*/
create table gestion_notificaciones(
id_notificacion int not null auto_increment,
titulo_notificacion varchar(50) not null,
tipo_notificacion varchar(20) not null,
mensaje_notifiacion text not null,
estado_notificacion boolean not null,
papeles_vehiculo_id_papel int not null,
primary key(id_notificacion)
);

alter table gestion_notificaciones
add constraint relacion_notificacion_papel
foreign key(papeles_vehiculo_id_papel)
references papeles_vehiculo(id_papel);



