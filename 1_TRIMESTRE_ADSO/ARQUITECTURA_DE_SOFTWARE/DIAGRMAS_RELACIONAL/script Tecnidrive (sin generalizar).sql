CREATE DATABASE tecnidrive;
USE tecnidrive;

-- ======================
-- TABLA ROLES
-- ======================

CREATE TABLE roles (
 id_rol INT AUTO_INCREMENT PRIMARY KEY,
 nombre_rol ENUM('administrador','mecanico','propietario') NOT NULL
);

-- ======================
-- TABLA USUARIOS
-- ======================

CREATE TABLE user (
 id_usuario INT AUTO_INCREMENT PRIMARY KEY,
 nombres VARCHAR(35) NOT NULL,
 ape_1 VARCHAR(20) NOT NULL,
 ape_2 VARCHAR(20),
 telefono BIGINT UNIQUE NOT NULL,
 email VARCHAR(50) UNIQUE NOT NULL,
 contrasena VARCHAR(100) NOT NULL,
 fecha_registro DATETIME NOT NULL,
 id_rol INT NOT NULL,
 
 FOREIGN KEY (id_rol) REFERENCES roles(id_rol)
);

-- ======================
-- RECUPERAR CUENTA
-- ======================

CREATE TABLE rec_cuenta (
 id_cod INT AUTO_INCREMENT PRIMARY KEY,
 cod_verificacion INT NOT NULL,
 fecha_soli DATETIME NOT NULL,
 fecha_exp DATETIME,
 id_usuario INT,
 
 FOREIGN KEY (id_usuario) REFERENCES user(id_usuario)
);

-- ======================
-- TIPOS DE NOTIFICACION
-- ======================

CREATE TABLE tipos (
 id_tipo INT AUTO_INCREMENT PRIMARY KEY,
 tipo ENUM('movilidad','vencimientos','mantenimientos')
);

-- ======================
-- NOTIFICACIONES
-- ======================

CREATE TABLE gestion_notificaciones (
 id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
 nombre_notificacion VARCHAR(45) NOT NULL,
 descripcion VARCHAR(100),
 mensaje VARCHAR(100),
 fecha_creacion DATETIME,
 estado TINYINT,
 id_tipo INT,
 
 FOREIGN KEY (id_tipo) REFERENCES tipos(id_tipo)
);

-- ======================
-- TIPO VEHICULO
-- ======================

CREATE TABLE tipo_vehiculo (
 id_tipo_vehiculo INT AUTO_INCREMENT PRIMARY KEY,
 tipo_vehiculo ENUM('automovil','camioneta','moto')
);

-- ======================
-- ESTADO VEHICULO
-- ======================

CREATE TABLE estado_vehiculo (
 id_estado INT AUTO_INCREMENT PRIMARY KEY,
 estado TINYINT,
 motivo_desactivacion VARCHAR(50)
);

-- ======================
-- MARCA VEHICULO
-- ======================

CREATE TABLE marca_vehiculo (
 id_marca INT AUTO_INCREMENT PRIMARY KEY,
 nombre_marca VARCHAR(45)
);

-- ======================
-- MODELO VEHICULO
-- ======================

CREATE TABLE modelo_vehiculo (
 id_modelo INT AUTO_INCREMENT PRIMARY KEY,
 nombre_modelo VARCHAR(35),
 id_marca INT,
 
 FOREIGN KEY (id_marca) REFERENCES marca_vehiculo(id_marca)
);

-- ======================
-- VEHICULO
-- ======================

CREATE TABLE vehiculo (
 placa VARCHAR(10) PRIMARY KEY,
 chasis VARCHAR(20) UNIQUE,
 cod_motor VARCHAR(35) UNIQUE,
 km_actual INT,
 anio INT,
 fecha_registro DATE,
 fecha_cambios DATE,
 id_tipo_vehiculo INT,
 id_estado INT,
 id_modelo INT,
 id_usuario INT,
 
 FOREIGN KEY (id_tipo_vehiculo) REFERENCES tipo_vehiculo(id_tipo_vehiculo),
 FOREIGN KEY (id_estado) REFERENCES estado_vehiculo(id_estado),
 FOREIGN KEY (id_modelo) REFERENCES modelo_vehiculo(id_modelo),
 FOREIGN KEY (id_usuario) REFERENCES user(id_usuario)
);

-- ======================
-- TECNOMECANICA
-- ======================

CREATE TABLE tecnomecanica (
 id_tecnomecanica INT AUTO_INCREMENT PRIMARY KEY,
 entidad_certificacion VARCHAR(45),
 NIT VARCHAR(20) UNIQUE,
 certificado VARCHAR(20) UNIQUE
);

-- ======================
-- SOAT
-- ======================

CREATE TABLE soat (
 id_soat INT AUTO_INCREMENT PRIMARY KEY,
 aseguradora VARCHAR(45),
 ciudad VARCHAR(45),
 numero_poliza VARCHAR(20) UNIQUE
);

-- ======================
-- LICENCIA
-- ======================

CREATE TABLE licencia (
 id_licencia INT AUTO_INCREMENT PRIMARY KEY,
 fecha_nacimiento DATE,
 RH VARCHAR(5),
 restricciones VARCHAR(100),
 categoria VARCHAR(5)
);

-- ======================
-- DOCUMENTOS VEHICULO
-- ======================

CREATE TABLE papel_vehiculo (
 id_documento INT AUTO_INCREMENT PRIMARY KEY,
 tipo_doc ENUM('CC','TI','PPT','CE'),
 fecha_exp DATE,
 fecha_vigencia DATE,
 servicio ENUM('particular','publico'),
 estado TINYINT,
 
 id_tecnomecanica INT,
 id_soat INT,
 id_licencia INT,
 placa VARCHAR(10),
 
 FOREIGN KEY (id_tecnomecanica) REFERENCES tecnomecanica(id_tecnomecanica),
 FOREIGN KEY (id_soat) REFERENCES soat(id_soat),
 FOREIGN KEY (id_licencia) REFERENCES licencia(id_licencia),
 FOREIGN KEY (placa) REFERENCES vehiculo(placa)
);

-- ======================
-- ESPECIALIDAD
-- ======================

CREATE TABLE especialidad (
 id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(45)
);

-- ======================
-- TALLER
-- ======================

CREATE TABLE taller (
 id_taller INT AUTO_INCREMENT PRIMARY KEY,
 nombre_taller VARCHAR(45),
 descripcion VARCHAR(50),
 direccion VARCHAR(35),
 horario VARCHAR(45),
 estado TINYINT,
 fecha_registro DATE,
 id_especialidad INT,
 
 FOREIGN KEY (id_especialidad) REFERENCES especialidad(id_especialidad)
);

-- ======================
-- SERVICIOS
-- ======================

CREATE TABLE servicios (
 id_servicio INT AUTO_INCREMENT PRIMARY KEY,
 precio INT,
 nombre_servicio VARCHAR(45),
 descripcion TEXT
);

-- ======================
-- RELACION SERVICIOS Y TALLER
-- ======================

CREATE TABLE servicios_taller (
 id_servicio INT,
 id_taller INT,
 PRIMARY KEY(id_servicio,id_taller),
 
 FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio),
 FOREIGN KEY (id_taller) REFERENCES taller(id_taller)
);
Se
