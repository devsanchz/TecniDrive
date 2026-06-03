-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2026 a las 13:16:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tecnidrive_03`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificacion_taller`
--

CREATE TABLE `calificacion_taller` (
  `id_calificacion` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `puntuacion` int(11) NOT NULL CHECK (`puntuacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `taller_id_taller` int(11) NOT NULL,
  `propietarios_id_propietario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_has_propietario`
--

CREATE TABLE `categoria_has_propietario` (
  `categoria_licencia_id_categoria` int(11) NOT NULL,
  `propietarios_id_propietario` int(11) NOT NULL,
  `vigencia_lice` date NOT NULL,
  `estado_lice` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_licencia`
--

CREATE TABLE `categoria_licencia` (
  `id_categoria` int(11) NOT NULL,
  `tipo_categoria` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita_has_servicio`
--

CREATE TABLE `cita_has_servicio` (
  `cita_taller_id_cita` int(11) NOT NULL,
  `taller_id_taller` int(11) NOT NULL,
  `servicios_id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita_taller`
--

CREATE TABLE `cita_taller` (
  `id_cita` int(11) NOT NULL,
  `fecha_cita` datetime NOT NULL,
  `problema_contexto` varchar(255) DEFAULT NULL,
  `estado_cita` tinyint(1) NOT NULL,
  `taller_id_taller` int(11) NOT NULL,
  `vehiculo_placa` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre_especialidad` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_mantenimiento`
--

CREATE TABLE `gestion_mantenimiento` (
  `id_seguimiento` int(11) NOT NULL,
  `cita_taller_id_cita` int(11) NOT NULL,
  `observaciones_tecnico` text NOT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `garantia_vigencia` datetime DEFAULT NULL,
  `texto_garantia` text DEFAULT NULL,
  `estado_proceso` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestion_notificaciones`
--

CREATE TABLE `gestion_notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `titulo_notificacion` varchar(50) NOT NULL,
  `tipo_notificacion` varchar(20) NOT NULL,
  `mensaje_notifiacion` text NOT NULL,
  `estado_notificacion` tinyint(1) NOT NULL,
  `papeles_vehiculo_id_papel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_has_tecnico`
--

CREATE TABLE `mantenimiento_has_tecnico` (
  `gestion_mantenimiento_id_seguimiento` int(11) NOT NULL,
  `tecnicos_id_tecnico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id_marca` int(11) NOT NULL,
  `nombre_marca` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mecanicos`
--

CREATE TABLE `mecanicos` (
  `id_mecanico` int(11) NOT NULL,
  `telefono_mecanico` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelos`
--

CREATE TABLE `modelos` (
  `id_modelo` int(11) NOT NULL,
  `nombre_modelo` varchar(28) NOT NULL,
  `marcas_id_marca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `papeles_vehiculo`
--

CREATE TABLE `papeles_vehiculo` (
  `id_papel` int(11) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `estado_papel` tinyint(1) NOT NULL,
  `tipo_documento_id_documento` int(11) NOT NULL,
  `vehiculo_placa` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `primer_nombre` varchar(30) NOT NULL,
  `segundo_nombre` varchar(30) DEFAULT NULL,
  `primer_apellido` varchar(25) NOT NULL,
  `segundo_apellido` varchar(25) NOT NULL,
  `email` varchar(60) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `email`, `contrasena`, `fecha_registro`) VALUES
(1, 'mariana', NULL, 'patiño', 'tapia', 'marianaBD@gmail.co', 'mariana456', '2026-05-15 07:16:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietarios`
--

CREATE TABLE `propietarios` (
  `id_propietario` int(11) NOT NULL,
  `telefono_propietario` bigint(20) NOT NULL,
  `numero_licencia` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `texto_rol` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `texto_rol`) VALUES
(1, 'administrador'),
(2, 'mecanico'),
(3, 'propietario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_has_persona`
--

CREATE TABLE `roles_has_persona` (
  `roles_id_rol` int(11) NOT NULL,
  `personas_id_persona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_has_persona`
--

INSERT INTO `roles_has_persona` (`roles_id_rol`, `personas_id_persona`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `nombre_servicio` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_vehiculo`
--

CREATE TABLE `servicio_vehiculo` (
  `id_tipo_servicio` int(11) NOT NULL,
  `texto_servicio` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taller`
--

CREATE TABLE `taller` (
  `id_taller` int(11) NOT NULL,
  `foto_taller` varchar(255) DEFAULT NULL,
  `nombre_taller` varchar(80) NOT NULL,
  `descripcion_taller` varchar(100) NOT NULL,
  `direccion_taller` varchar(80) NOT NULL,
  `horario_taller` varchar(80) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_taller` tinyint(1) NOT NULL,
  `motivo_estado` varchar(100) NOT NULL DEFAULT 'Taller activo',
  `mecanicos_id_mecanico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taller_especialidad`
--

CREATE TABLE `taller_especialidad` (
  `taller_id_taller` int(11) NOT NULL,
  `especialidades_id_especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taller_has_servicios`
--

CREATE TABLE `taller_has_servicios` (
  `taller_id_taller` int(11) NOT NULL,
  `servicios_id_servicio` int(11) NOT NULL,
  `precio_servicio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnicos`
--

CREATE TABLE `tecnicos` (
  `id_tecnico` int(11) NOT NULL,
  `nombre_tecnico` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_vehiculo`
--

CREATE TABLE `tipos_vehiculo` (
  `id_tipo_vehi` int(11) NOT NULL,
  `texto_tipo_vehi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id_documento` int(11) NOT NULL,
  `nombre_documento` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `placa` varchar(6) NOT NULL,
  `year` varchar(4) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_vehi` tinyint(1) NOT NULL,
  `motivo_estado` varchar(100) DEFAULT NULL,
  `propietarios_id_propietario` int(11) NOT NULL,
  `tipos_vehiculo_id_tipo_vehi` int(11) NOT NULL,
  `modelos_id_modelo` int(11) NOT NULL,
  `servicio_vehiculo_id_tipo_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `calificacion_taller`
--
ALTER TABLE `calificacion_taller`
  ADD PRIMARY KEY (`id_calificacion`),
  ADD KEY `relacion_calificacion_taller` (`taller_id_taller`),
  ADD KEY `relacion_calificacion_propietario` (`propietarios_id_propietario`);

--
-- Indices de la tabla `categoria_has_propietario`
--
ALTER TABLE `categoria_has_propietario`
  ADD PRIMARY KEY (`categoria_licencia_id_categoria`,`propietarios_id_propietario`),
  ADD KEY `relacion_propietario_categoria` (`propietarios_id_propietario`);

--
-- Indices de la tabla `categoria_licencia`
--
ALTER TABLE `categoria_licencia`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cita_has_servicio`
--
ALTER TABLE `cita_has_servicio`
  ADD PRIMARY KEY (`cita_taller_id_cita`,`taller_id_taller`,`servicios_id_servicio`),
  ADD KEY `relacion_cita_taller_servicio` (`taller_id_taller`,`servicios_id_servicio`);

--
-- Indices de la tabla `cita_taller`
--
ALTER TABLE `cita_taller`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `relacion_vehiculo_cita` (`vehiculo_placa`),
  ADD KEY `relacion_taller_cita` (`taller_id_taller`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `gestion_mantenimiento`
--
ALTER TABLE `gestion_mantenimiento`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `relacion_seguimiento_cita` (`cita_taller_id_cita`);

--
-- Indices de la tabla `gestion_notificaciones`
--
ALTER TABLE `gestion_notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `relacion_notificacion_papel` (`papeles_vehiculo_id_papel`);

--
-- Indices de la tabla `mantenimiento_has_tecnico`
--
ALTER TABLE `mantenimiento_has_tecnico`
  ADD PRIMARY KEY (`gestion_mantenimiento_id_seguimiento`,`tecnicos_id_tecnico`),
  ADD KEY `relacion_tecnico_seguimiento` (`tecnicos_id_tecnico`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id_marca`);

--
-- Indices de la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD PRIMARY KEY (`id_mecanico`);

--
-- Indices de la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`id_modelo`),
  ADD KEY `relacion_marca_modelo` (`marcas_id_marca`);

--
-- Indices de la tabla `papeles_vehiculo`
--
ALTER TABLE `papeles_vehiculo`
  ADD PRIMARY KEY (`id_papel`),
  ADD KEY `relacion_papel_tipo` (`tipo_documento_id_documento`),
  ADD KEY `relacion_papel_vehiculo` (`vehiculo_placa`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `propietarios`
--
ALTER TABLE `propietarios`
  ADD PRIMARY KEY (`id_propietario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `roles_has_persona`
--
ALTER TABLE `roles_has_persona`
  ADD PRIMARY KEY (`roles_id_rol`,`personas_id_persona`),
  ADD KEY `relacion_person_rol` (`personas_id_persona`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `servicio_vehiculo`
--
ALTER TABLE `servicio_vehiculo`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- Indices de la tabla `taller`
--
ALTER TABLE `taller`
  ADD PRIMARY KEY (`id_taller`),
  ADD KEY `relacion_mecanico_taller` (`mecanicos_id_mecanico`);

--
-- Indices de la tabla `taller_especialidad`
--
ALTER TABLE `taller_especialidad`
  ADD PRIMARY KEY (`taller_id_taller`,`especialidades_id_especialidad`),
  ADD KEY `relacion_especialidad_taller` (`especialidades_id_especialidad`);

--
-- Indices de la tabla `taller_has_servicios`
--
ALTER TABLE `taller_has_servicios`
  ADD PRIMARY KEY (`taller_id_taller`,`servicios_id_servicio`),
  ADD KEY `relacion_servicios_taller` (`servicios_id_servicio`);

--
-- Indices de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  ADD PRIMARY KEY (`id_tecnico`);

--
-- Indices de la tabla `tipos_vehiculo`
--
ALTER TABLE `tipos_vehiculo`
  ADD PRIMARY KEY (`id_tipo_vehi`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id_documento`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`placa`),
  ADD KEY `relacion_servicio_vehiculo` (`servicio_vehiculo_id_tipo_servicio`),
  ADD KEY `relacion_tipo_vehiculo` (`tipos_vehiculo_id_tipo_vehi`),
  ADD KEY `relacion_vehiculo_modelo` (`modelos_id_modelo`),
  ADD KEY `relacion_propietario_vehiculo` (`propietarios_id_propietario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `calificacion_taller`
--
ALTER TABLE `calificacion_taller`
  MODIFY `id_calificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cita_taller`
--
ALTER TABLE `cita_taller`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gestion_mantenimiento`
--
ALTER TABLE `gestion_mantenimiento`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `gestion_notificaciones`
--
ALTER TABLE `gestion_notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `papeles_vehiculo`
--
ALTER TABLE `papeles_vehiculo`
  MODIFY `id_papel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `taller`
--
ALTER TABLE `taller`
  MODIFY `id_taller` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tecnicos`
--
ALTER TABLE `tecnicos`
  MODIFY `id_tecnico` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `calificacion_taller`
--
ALTER TABLE `calificacion_taller`
  ADD CONSTRAINT `relacion_calificacion_propietario` FOREIGN KEY (`propietarios_id_propietario`) REFERENCES `propietarios` (`id_propietario`),
  ADD CONSTRAINT `relacion_calificacion_taller` FOREIGN KEY (`taller_id_taller`) REFERENCES `taller` (`id_taller`);

--
-- Filtros para la tabla `categoria_has_propietario`
--
ALTER TABLE `categoria_has_propietario`
  ADD CONSTRAINT `relacion_categoria_propietario` FOREIGN KEY (`categoria_licencia_id_categoria`) REFERENCES `categoria_licencia` (`id_categoria`),
  ADD CONSTRAINT `relacion_propietario_categoria` FOREIGN KEY (`propietarios_id_propietario`) REFERENCES `propietarios` (`id_propietario`);

--
-- Filtros para la tabla `cita_has_servicio`
--
ALTER TABLE `cita_has_servicio`
  ADD CONSTRAINT `relacion_cita_taller_servicio` FOREIGN KEY (`taller_id_taller`,`servicios_id_servicio`) REFERENCES `taller_has_servicios` (`taller_id_taller`, `servicios_id_servicio`),
  ADD CONSTRAINT `relacion_taller_servicios_cita` FOREIGN KEY (`cita_taller_id_cita`) REFERENCES `cita_taller` (`id_cita`);

--
-- Filtros para la tabla `cita_taller`
--
ALTER TABLE `cita_taller`
  ADD CONSTRAINT `relacion_taller_cita` FOREIGN KEY (`taller_id_taller`) REFERENCES `taller` (`id_taller`),
  ADD CONSTRAINT `relacion_vehiculo_cita` FOREIGN KEY (`vehiculo_placa`) REFERENCES `vehiculo` (`placa`);

--
-- Filtros para la tabla `gestion_mantenimiento`
--
ALTER TABLE `gestion_mantenimiento`
  ADD CONSTRAINT `relacion_seguimiento_cita` FOREIGN KEY (`cita_taller_id_cita`) REFERENCES `cita_taller` (`id_cita`);

--
-- Filtros para la tabla `gestion_notificaciones`
--
ALTER TABLE `gestion_notificaciones`
  ADD CONSTRAINT `relacion_notificacion_papel` FOREIGN KEY (`papeles_vehiculo_id_papel`) REFERENCES `papeles_vehiculo` (`id_papel`);

--
-- Filtros para la tabla `mantenimiento_has_tecnico`
--
ALTER TABLE `mantenimiento_has_tecnico`
  ADD CONSTRAINT `relacion_seguimiento_tecnico` FOREIGN KEY (`gestion_mantenimiento_id_seguimiento`) REFERENCES `gestion_mantenimiento` (`id_seguimiento`),
  ADD CONSTRAINT `relacion_tecnico_seguimiento` FOREIGN KEY (`tecnicos_id_tecnico`) REFERENCES `tecnicos` (`id_tecnico`);

--
-- Filtros para la tabla `mecanicos`
--
ALTER TABLE `mecanicos`
  ADD CONSTRAINT `relacion_persona_mecanico` FOREIGN KEY (`id_mecanico`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `modelos`
--
ALTER TABLE `modelos`
  ADD CONSTRAINT `relacion_marca_modelo` FOREIGN KEY (`marcas_id_marca`) REFERENCES `marcas` (`id_marca`);

--
-- Filtros para la tabla `papeles_vehiculo`
--
ALTER TABLE `papeles_vehiculo`
  ADD CONSTRAINT `relacion_papel_tipo` FOREIGN KEY (`tipo_documento_id_documento`) REFERENCES `tipo_documento` (`id_documento`),
  ADD CONSTRAINT `relacion_papel_vehiculo` FOREIGN KEY (`vehiculo_placa`) REFERENCES `vehiculo` (`placa`);

--
-- Filtros para la tabla `propietarios`
--
ALTER TABLE `propietarios`
  ADD CONSTRAINT `relacion_persona_propietario` FOREIGN KEY (`id_propietario`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `roles_has_persona`
--
ALTER TABLE `roles_has_persona`
  ADD CONSTRAINT `relacion_person_rol` FOREIGN KEY (`personas_id_persona`) REFERENCES `personas` (`id_persona`),
  ADD CONSTRAINT `relacion_rol_person` FOREIGN KEY (`roles_id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `taller`
--
ALTER TABLE `taller`
  ADD CONSTRAINT `relacion_mecanico_taller` FOREIGN KEY (`mecanicos_id_mecanico`) REFERENCES `mecanicos` (`id_mecanico`);

--
-- Filtros para la tabla `taller_especialidad`
--
ALTER TABLE `taller_especialidad`
  ADD CONSTRAINT `relacion_especialidad_taller` FOREIGN KEY (`especialidades_id_especialidad`) REFERENCES `especialidades` (`id_especialidad`),
  ADD CONSTRAINT `relacion_taller_especialidad` FOREIGN KEY (`taller_id_taller`) REFERENCES `taller` (`id_taller`);

--
-- Filtros para la tabla `taller_has_servicios`
--
ALTER TABLE `taller_has_servicios`
  ADD CONSTRAINT `relacion_servicios_taller` FOREIGN KEY (`servicios_id_servicio`) REFERENCES `servicios` (`id_servicio`),
  ADD CONSTRAINT `relacion_taller_servicios` FOREIGN KEY (`taller_id_taller`) REFERENCES `taller` (`id_taller`);

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `relacion_propietario_vehiculo` FOREIGN KEY (`propietarios_id_propietario`) REFERENCES `propietarios` (`id_propietario`),
  ADD CONSTRAINT `relacion_servicio_vehiculo` FOREIGN KEY (`servicio_vehiculo_id_tipo_servicio`) REFERENCES `servicio_vehiculo` (`id_tipo_servicio`),
  ADD CONSTRAINT `relacion_tipo_vehiculo` FOREIGN KEY (`tipos_vehiculo_id_tipo_vehi`) REFERENCES `tipos_vehiculo` (`id_tipo_vehi`),
  ADD CONSTRAINT `relacion_vehiculo_modelo` FOREIGN KEY (`modelos_id_modelo`) REFERENCES `modelos` (`id_modelo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
