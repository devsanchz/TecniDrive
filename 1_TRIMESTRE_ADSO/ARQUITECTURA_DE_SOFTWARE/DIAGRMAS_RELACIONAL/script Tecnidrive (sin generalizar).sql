-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`roles` (
  `idroles` INT NOT NULL,
  `rol` ENUM('administrador', 'mecanico', 'propietario') NOT NULL,
  PRIMARY KEY (`idroles`),
  UNIQUE INDEX `idroles_UNIQUE` (`idroles` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`tipos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`tipos` (
  `id_tipos` INT UNSIGNED NOT NULL,
  `tipos` ENUM('movilidad', 'vencimientos', 'mantenimietos') NOT NULL,
  PRIMARY KEY (`id_tipos`),
  UNIQUE INDEX `id_tipos_UNIQUE` (`id_tipos` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`gestion_notificaciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`gestion_notificaciones` (
  `id_notificaciones` INT UNSIGNED NOT NULL,
  `nombre_notificacion` VARCHAR(45) NOT NULL,
  `descripcion` VARCHAR(100) NOT NULL,
  `mensaje` VARCHAR(50) NOT NULL,
  `fecha_creacion` DATETIME NOT NULL,
  `estado` TINYINT NOT NULL,
  `tipos_id_tipos` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_notificaciones`, `tipos_id_tipos`),
  UNIQUE INDEX `id_notificaciones_UNIQUE` (`id_notificaciones` ASC) VISIBLE,
  INDEX `fk_gestion_notificaciones_tipos1_idx` (`tipos_id_tipos` ASC) VISIBLE,
  CONSTRAINT `fk_gestion_notificaciones_tipos1`
    FOREIGN KEY (`tipos_id_tipos`)
    REFERENCES `mydb`.`tipos` (`id_tipos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `id_usuario` INT NOT NULL,
  `nombres` VARCHAR(35) NOT NULL,
  `ape_1` VARCHAR(20) NOT NULL,
  `ape_2` VARCHAR(15) NULL,
  `telefono` BIGINT(10) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `contrasena` VARCHAR(30) NULL,
  `fecha_registro` DATETIME NOT NULL,
  `roles_idroles` INT NOT NULL,
  `gestion_notificaciones_id_notificaciones` INT UNSIGNED NOT NULL,
  `gestion_notificaciones_tipos_id_tipos` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_usuario`, `roles_idroles`, `gestion_notificaciones_id_notificaciones`, `gestion_notificaciones_tipos_id_tipos`),
  UNIQUE INDEX `id_usuario_UNIQUE` (`id_usuario` ASC) VISIBLE,
  UNIQUE INDEX `telefono_UNIQUE` (`telefono` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `contrasena_UNIQUE` (`contrasena` ASC) VISIBLE,
  INDEX `fk_user_roles_idx` (`roles_idroles` ASC) VISIBLE,
  INDEX `fk_user_gestion_notificaciones1_idx` (`gestion_notificaciones_id_notificaciones` ASC, `gestion_notificaciones_tipos_id_tipos` ASC) VISIBLE,
  CONSTRAINT `fk_user_roles`
    FOREIGN KEY (`roles_idroles`)
    REFERENCES `mydb`.`roles` (`idroles`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_gestion_notificaciones1`
    FOREIGN KEY (`gestion_notificaciones_id_notificaciones` , `gestion_notificaciones_tipos_id_tipos`)
    REFERENCES `mydb`.`gestion_notificaciones` (`id_notificaciones` , `tipos_id_tipos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`rec_cuenta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`rec_cuenta` (
  `id_cod` INT NOT NULL,
  `cod_verificacion` INT NOT NULL,
  `fecha_soli` DATETIME NOT NULL,
  `fecha_exp` DATETIME NULL,
  `user_id_usuario` INT NOT NULL,
  `user_roles_idroles` INT NOT NULL,
  PRIMARY KEY (`id_cod`, `user_id_usuario`, `user_roles_idroles`),
  UNIQUE INDEX `id_cod_UNIQUE` (`id_cod` ASC) VISIBLE,
  UNIQUE INDEX `cod_verificacion_UNIQUE` (`cod_verificacion` ASC) VISIBLE,
  INDEX `fk_rec_cuenta_user1_idx` (`user_id_usuario` ASC, `user_roles_idroles` ASC) VISIBLE,
  CONSTRAINT `fk_rec_cuenta_user1`
    FOREIGN KEY (`user_id_usuario` , `user_roles_idroles`)
    REFERENCES `mydb`.`user` (`id_usuario` , `roles_idroles`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`tipo_vechiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`tipo_vechiculo` (
  `id_tipo_vechiculo` INT NOT NULL,
  `tipo_vechiculo` ENUM('automovil', 'camioneta', 'camieoneta') NOT NULL,
  PRIMARY KEY (`id_tipo_vechiculo`),
  UNIQUE INDEX `id_tipo_vechiculo_UNIQUE` (`id_tipo_vechiculo` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`estado_vehiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`estado_vehiculo` (
  `cod_estado_v` INT NOT NULL,
  `estado_vehiculo` TINYINT NOT NULL,
  `motivo_desactivacion` VARCHAR(50) NULL,
  PRIMARY KEY (`cod_estado_v`),
  UNIQUE INDEX `cod_estado_v_UNIQUE` (`cod_estado_v` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`marca_vehiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`marca_vehiculo` (
  `id_marca_vehiculo` INT UNSIGNED NOT NULL,
  `marca_vehiculo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_marca_vehiculo`),
  UNIQUE INDEX `id_marca_vehiculo_UNIQUE` (`id_marca_vehiculo` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`modelo_vehiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`modelo_vehiculo` (
  `id_modelo_vehiculo` INT NOT NULL,
  `modelo_vehiculo` VARCHAR(35) NOT NULL,
  `marca_vehiculo_id_marca_vehiculo` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_modelo_vehiculo`, `marca_vehiculo_id_marca_vehiculo`),
  UNIQUE INDEX `id_modelo_vehiculo_UNIQUE` (`id_modelo_vehiculo` ASC) VISIBLE,
  INDEX `fk_modelo_vehiculo_marca_vehiculo1_idx` (`marca_vehiculo_id_marca_vehiculo` ASC) VISIBLE,
  CONSTRAINT `fk_modelo_vehiculo_marca_vehiculo1`
    FOREIGN KEY (`marca_vehiculo_id_marca_vehiculo`)
    REFERENCES `mydb`.`marca_vehiculo` (`id_marca_vehiculo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`vehiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`vehiculo` (
  `placa` INT NOT NULL,
  `chasis` VARCHAR(20) NOT NULL,
  `cod_motor` VARCHAR(35) NOT NULL,
  `Km_ actual` INT UNSIGNED NOT NULL,
  `año` VARCHAR(45) NOT NULL,
  `Fech_reg` DATE NOT NULL,
  `fech_cambios` DATE NULL,
  `tipo_vechiculo_id_tipo_vechiculo` INT NOT NULL,
  `estado_vehiculo_cod_estado_v` INT NOT NULL,
  `modelo_vehiculo_id_modelo_vehiculo` INT NOT NULL,
  `user_id_usuario` INT NOT NULL,
  `user_roles_idroles` INT NOT NULL,
  PRIMARY KEY (`placa`, `tipo_vechiculo_id_tipo_vechiculo`, `estado_vehiculo_cod_estado_v`, `modelo_vehiculo_id_modelo_vehiculo`, `user_id_usuario`, `user_roles_idroles`),
  UNIQUE INDEX `chasis_UNIQUE` (`chasis` ASC) VISIBLE,
  UNIQUE INDEX `cod_motor_UNIQUE` (`cod_motor` ASC) VISIBLE,
  INDEX `fk_vehiculo_tipo_vechiculo1_idx` (`tipo_vechiculo_id_tipo_vechiculo` ASC) VISIBLE,
  INDEX `fk_vehiculo_estado_vehiculo1_idx` (`estado_vehiculo_cod_estado_v` ASC) VISIBLE,
  INDEX `fk_vehiculo_modelo_vehiculo1_idx` (`modelo_vehiculo_id_modelo_vehiculo` ASC) VISIBLE,
  INDEX `fk_vehiculo_user1_idx` (`user_id_usuario` ASC, `user_roles_idroles` ASC) VISIBLE,
  CONSTRAINT `fk_vehiculo_tipo_vechiculo1`
    FOREIGN KEY (`tipo_vechiculo_id_tipo_vechiculo`)
    REFERENCES `mydb`.`tipo_vechiculo` (`id_tipo_vechiculo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehiculo_estado_vehiculo1`
    FOREIGN KEY (`estado_vehiculo_cod_estado_v`)
    REFERENCES `mydb`.`estado_vehiculo` (`cod_estado_v`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehiculo_modelo_vehiculo1`
    FOREIGN KEY (`modelo_vehiculo_id_modelo_vehiculo`)
    REFERENCES `mydb`.`modelo_vehiculo` (`id_modelo_vehiculo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehiculo_user1`
    FOREIGN KEY (`user_id_usuario` , `user_roles_idroles`)
    REFERENCES `mydb`.`user` (`id_usuario` , `roles_idroles`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`tecnomecanica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`tecnomecanica` (
  `id_tecnomecanica` INT UNSIGNED NOT NULL,
  `Entidad_certificamiento` VARCHAR(45) NOT NULL,
  `NIT` INT NOT NULL,
  `n_certificado_acreditacion` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id_tecnomecanica`),
  UNIQUE INDEX `NIT_UNIQUE` (`NIT` ASC) VISIBLE,
  UNIQUE INDEX `n_certificado_acreditacion_UNIQUE` (`n_certificado_acreditacion` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`SOAT`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`SOAT` (
  `id_SOAT` INT UNSIGNED NOT NULL,
  `cod_aseguradora` VARCHAR(20) NOT NULL,
  `cod_sucursal` SMALLINT(2) NOT NULL,
  `ciudad_residencia_tomador` VARCHAR(45) NOT NULL,
  `n_poliza` SMALLINT(8) NOT NULL,
  PRIMARY KEY (`id_SOAT`),
  UNIQUE INDEX `cod_aseguradora_UNIQUE` (`cod_aseguradora` ASC) VISIBLE,
  UNIQUE INDEX `n_poliza_UNIQUE` (`n_poliza` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`licencia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`licencia` (
  `id_pase` INT NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `RH` VARCHAR(2) NOT NULL,
  `restricciones` VARCHAR(100) NULL,
  `categoria` VARCHAR(3) NOT NULL,
  PRIMARY KEY (`id_pase`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`papel_vehiculo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`papel_vehiculo` (
  `documento` INT UNSIGNED NOT NULL,
  `tipo_doc` ENUM('CC', 'TI', 'PPT', 'CE') NOT NULL,
  `fecha_exp` DATE NOT NULL,
  `fecha_vigencia` DATE NOT NULL,
  `servicio` ENUM('particular', 'publico') NOT NULL,
  `estado` TINYINT NOT NULL,
  `tecnomecanica_id_tecnomecanica` INT UNSIGNED NOT NULL,
  `SOAT_id_SOAT` INT UNSIGNED NOT NULL,
  `licencia_id_pase` INT NOT NULL,
  `vehiculo_placa` INT NOT NULL,
  `vehiculo_tipo_vechiculo_id_tipo_vechiculo` INT NOT NULL,
  `vehiculo_estado_vehiculo_cod_estado_v` INT NOT NULL,
  `vehiculo_modelo_vehiculo_id_modelo_vehiculo` INT NOT NULL,
  `vehiculo_user_id_usuario` INT NOT NULL,
  `vehiculo_user_roles_idroles` INT NOT NULL,
  PRIMARY KEY (`documento`, `tecnomecanica_id_tecnomecanica`, `SOAT_id_SOAT`, `licencia_id_pase`, `vehiculo_placa`, `vehiculo_tipo_vechiculo_id_tipo_vechiculo`, `vehiculo_estado_vehiculo_cod_estado_v`, `vehiculo_modelo_vehiculo_id_modelo_vehiculo`, `vehiculo_user_id_usuario`, `vehiculo_user_roles_idroles`),
  UNIQUE INDEX `documento_UNIQUE` (`documento` ASC) VISIBLE,
  INDEX `fk_papel_vehiculo_tecnomecanica1_idx` (`tecnomecanica_id_tecnomecanica` ASC) VISIBLE,
  INDEX `fk_papel_vehiculo_SOAT1_idx` (`SOAT_id_SOAT` ASC) VISIBLE,
  INDEX `fk_papel_vehiculo_licencia1_idx` (`licencia_id_pase` ASC) VISIBLE,
  INDEX `fk_papel_vehiculo_vehiculo1_idx` (`vehiculo_placa` ASC, `vehiculo_tipo_vechiculo_id_tipo_vechiculo` ASC, `vehiculo_estado_vehiculo_cod_estado_v` ASC, `vehiculo_modelo_vehiculo_id_modelo_vehiculo` ASC, `vehiculo_user_id_usuario` ASC, `vehiculo_user_roles_idroles` ASC) VISIBLE,
  CONSTRAINT `fk_papel_vehiculo_tecnomecanica1`
    FOREIGN KEY (`tecnomecanica_id_tecnomecanica`)
    REFERENCES `mydb`.`tecnomecanica` (`id_tecnomecanica`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_papel_vehiculo_SOAT1`
    FOREIGN KEY (`SOAT_id_SOAT`)
    REFERENCES `mydb`.`SOAT` (`id_SOAT`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_papel_vehiculo_licencia1`
    FOREIGN KEY (`licencia_id_pase`)
    REFERENCES `mydb`.`licencia` (`id_pase`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_papel_vehiculo_vehiculo1`
    FOREIGN KEY (`vehiculo_placa` , `vehiculo_tipo_vechiculo_id_tipo_vechiculo` , `vehiculo_estado_vehiculo_cod_estado_v` , `vehiculo_modelo_vehiculo_id_modelo_vehiculo` , `vehiculo_user_id_usuario` , `vehiculo_user_roles_idroles`)
    REFERENCES `mydb`.`vehiculo` (`placa` , `tipo_vechiculo_id_tipo_vechiculo` , `estado_vehiculo_cod_estado_v` , `modelo_vehiculo_id_modelo_vehiculo` , `user_id_usuario` , `user_roles_idroles`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`especialidad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`especialidad` (
  `id_especialidad` INT UNSIGNED NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_especialidad`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`vehiculeria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`vehiculeria` (
  `id_vehiculeria` INT NOT NULL,
  `vehiculo_atendido` VARCHAR(20) NULL,
  PRIMARY KEY (`id_vehiculeria`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`taller`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`taller` (
  `id_taller` INT NOT NULL,
  `nombre_taller` VARCHAR(45) NOT NULL,
  `descripcion_taller` VARCHAR(50) NULL,
  `direccion` VARCHAR(35) NOT NULL,
  `horario` VARCHAR(45) NOT NULL,
  `estado` TINYINT NOT NULL,
  `fecha_registro` DATE NOT NULL,
  `especialidad_id_especialidad` INT UNSIGNED NOT NULL,
  `vehiculeria_id_vehiculeria` INT NOT NULL,
  PRIMARY KEY (`id_taller`, `especialidad_id_especialidad`, `vehiculeria_id_vehiculeria`),
  UNIQUE INDEX `id_taller_UNIQUE` (`id_taller` ASC) VISIBLE,
  INDEX `fk_taller_especialidad1_idx` (`especialidad_id_especialidad` ASC) VISIBLE,
  INDEX `fk_taller_vehiculeria1_idx` (`vehiculeria_id_vehiculeria` ASC) VISIBLE,
  CONSTRAINT `fk_taller_especialidad1`
    FOREIGN KEY (`especialidad_id_especialidad`)
    REFERENCES `mydb`.`especialidad` (`id_especialidad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_taller_vehiculeria1`
    FOREIGN KEY (`vehiculeria_id_vehiculeria`)
    REFERENCES `mydb`.`vehiculeria` (`id_vehiculeria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`servicios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`servicios` (
  `id_servicios` INT NOT NULL,
  `precio_servicio` INT UNSIGNED NOT NULL,
  `servicios` VARCHAR(45) NOT NULL,
  `descripcion_servicios` TEXT NOT NULL,
  PRIMARY KEY (`id_servicios`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`servicios_has_taller`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`servicios_has_taller` (
  `servicios_id_servicios` INT NOT NULL,
  `taller_id_taller` INT NOT NULL,
  `taller_especialidad_id_especialidad` INT UNSIGNED NOT NULL,
  `taller_vehiculeria_id_vehiculeria` INT NOT NULL,
  PRIMARY KEY (`servicios_id_servicios`, `taller_id_taller`, `taller_especialidad_id_especialidad`, `taller_vehiculeria_id_vehiculeria`),
  INDEX `fk_servicios_has_taller_taller1_idx` (`taller_id_taller` ASC, `taller_especialidad_id_especialidad` ASC, `taller_vehiculeria_id_vehiculeria` ASC) VISIBLE,
  INDEX `fk_servicios_has_taller_servicios1_idx` (`servicios_id_servicios` ASC) VISIBLE,
  CONSTRAINT `fk_servicios_has_taller_servicios1`
    FOREIGN KEY (`servicios_id_servicios`)
    REFERENCES `mydb`.`servicios` (`id_servicios`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicios_has_taller_taller1`
    FOREIGN KEY (`taller_id_taller` , `taller_especialidad_id_especialidad` , `taller_vehiculeria_id_vehiculeria`)
    REFERENCES `mydb`.`taller` (`id_taller` , `especialidad_id_especialidad` , `vehiculeria_id_vehiculeria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
