/* 20/02/2017 */

-- CREATE DATABASE IF NOT EXISTS magentoe_IncidenciasEVG;
-- USE  magentoe_IncidenciasEVG;

/**
  Tablas de Incidencias:
*/

/* TABLA 1 - PROFESORES*/
CREATE TABLE IF NOT EXISTS profesores(
  idUsuario TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  usuario VARCHAR(20) NOT NULL UNIQUE,
  correo VARCHAR(60) NOT NULL,
  nombre VARCHAR(50) NOT NULL,
  pass VARCHAR(255) NOT NULL,
  profesor BOOLEAN NOT NULL DEFAULT TRUE ,
  gestor BOOLEAN NOT NULL DEFAULT FALSE ,
  tutor BOOLEAN NOT NULL DEFAULT FALSE ,
  coordinador BOOLEAN NOT NULL DEFAULT FALSE ,
  baja_temporal BOOLEAN NOT NULL DEFAULT FALSE
);

/* TABLA 2 - ETAPAS*/
CREATE TABLE IF NOT EXISTS etapas(
  idEtapa TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  codEtapa CHAR(5) NOT NULL UNIQUE,
  nombre VARCHAR(40) NOT NULL UNIQUE,
  coordinador TINYINT UNSIGNED DEFAULT NULL,

  CONSTRAINT PK_idEtapa PRIMARY KEY(idEtapa),

  CONSTRAINT fk_etapas_profesores FOREIGN KEY (coordinador) REFERENCES profesores(idUsuario)
);

/* TABLA 3 - SECCIONES*/
CREATE TABLE IF NOT EXISTS secciones(
  idSeccion CHAR(6) NOT NULL PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL,
  tutor TINYINT UNSIGNED DEFAULT NULL,
  codEtapa CHAR(5) NOT NULL,
  codCurso tinyint(3) UNSIGNED NULL,
  CONSTRAINT fk_tutores_secciones FOREIGN KEY (tutor) REFERENCES profesores(idUsuario),
  CONSTRAINT fk_etapas_secciones FOREIGN KEY (codEtapa) REFERENCES etapas(codEtapa)
);


/* TABLA 4-ALUMNOS */
CREATE TABLE IF NOT EXISTS alumnos(
  idAlumno INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nia CHAR (7) NOT NULL UNIQUE,
  nombreCompleto VARCHAR(50) NOT NULL,
  telefono VARCHAR(9) NOT NULL,
  sexo CHAR(1) NOT NULL,
  idSeccion CHAR(6) NOT NULL,
  numPartes tinyint(4) DEFAULT NULL,

  CONSTRAINT PK_idAlumno PRIMARY KEY(idAlumno),

  CONSTRAINT fk_secciones_alumnos FOREIGN KEY (idSeccion) REFERENCES secciones(idSeccion)
);

/*TABLA 5 - PROFESORES-SECCION*/
CREATE TABLE IF NOT EXISTS profesores_seccion(
  idSeccion CHAR(5),
  profesor TINYINT UNSIGNED,
  PRIMARY KEY (profesor,idSeccion),
  CONSTRAINT fk_profesor_prof_secc FOREIGN KEY (profesor) REFERENCES profesores(idUsuario),
  CONSTRAINT fk_seccion_prof_secc FOREIGN KEY (idSeccion) REFERENCES secciones(idSeccion)
);

/*Tabla 6- tipos_incidencias*/
CREATE TABLE IF NOT EXISTS tipo_Incidencias(
  idTipo tinyint UNSIGNED PRIMARY KEY,
  nombre varchar(30) NOT NULL,
  codEtapa CHAR(5) NOT NULL,
  gestiona CHAR(1) NOT NULL,
  CONSTRAINT fk_tipos_incidencias FOREIGN KEY (codEtapa) REFERENCES etapas(codEtapa)
);

/*TABLA 9 - HORAS*/
CREATE TABLE IF NOT EXISTS horas(
  idHora TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(20) NOT NULL
);

/*TABLA 10 - INCIDENCIAS*/
CREATE TABLE IF NOT EXISTS incidencias(
  idIncidencia SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nia CHAR(7) NOT NULL,
  idTipo TINYINT UNSIGNED  NOT NULL,
  usuario TINYINT UNSIGNED NOT NULL,
  codAsignatura VARCHAR(30) NULL,
  idHora TINYINT UNSIGNED NOT NULL,
  fecha_ocurrencia DATE NOT NULL,
  fecha_registro DATETIME NOT NULL,
  descripcion VARCHAR(300) NOT NULL,
  leidaT BOOLEAN NOT NULL DEFAULT FALSE ,
  leidaC BOOLEAN NOT NULL DEFAULT FALSE ,
  archivadaT BOOLEAN NOT NULL DEFAULT FALSE ,
  archivadaC BOOLEAN NOT NULL DEFAULT FALSE ,
  CONSTRAINT fk_incidencias_alumno FOREIGN KEY (nia) REFERENCES alumnos(nia) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_incidencias_tipo FOREIGN KEY (idTipo) REFERENCES  tipo_Incidencias(idTipo),
  CONSTRAINT fk_incidencias_profesor FOREIGN KEY (usuario) REFERENCES profesores(idUsuario),
  CONSTRAINT fk_incidencias_hora FOREIGN KEY (idHora) REFERENCES horas(idHora)
);

/*Tabla 11-tipos_Anotaciones*/
CREATE TABLE IF NOT EXISTS tipos_Anotaciones(
  tipoAnotacion TINYINT PRIMARY KEY,
  nombre varchar(40) NOT NULL,
  codEtapa CHAR(5) NOT NULL,
  CONSTRAINT fk_tipos_Anotaciones FOREIGN KEY (codEtapa) REFERENCES etapas(codEtapa)
);

/* TABLA 13-ANOTACIONES */
CREATE TABLE IF NOT EXISTS anotaciones(
  numAnotacion SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  tipoAnotacion TINYINT NOT NULL,
  nia CHAR(7) NOT NULL ,
  hora_Registro DATETIME NOT NULL,
  userCreacion CHAR(1) NOT NULL ,
  leida BOOLEAN NOT NULL DEFAULT FALSE ,
  verProfesores BOOLEAN NULL DEFAULT FALSE ,
  Descripcion VARCHAR(300) NOT NULL,
  CONSTRAINT anotaciones_1 FOREIGN KEY (tipoAnotacion)
  REFERENCES tipos_Anotaciones (tipoAnotacion),
  CONSTRAINT anotaciones_2 FOREIGN KEY (nia) REFERENCES alumnos(nia) ON DELETE CASCADE ON UPDATE CASCADE
);

/* TABLA 14 - TIPO_SANCION*/
CREATE TABLE IF NOT EXISTS tipo_sancion(
  tipoSancion TINYINT UNSIGNED NOT NULL PRIMARY KEY,
  nombre VARCHAR(20) NOT NULL
);

/* TABLA 18-MOTIVO*/
CREATE TABLE IF NOT EXISTS motivo(
  idMotivo TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  motivo VARCHAR(20) NOT NULL
);

/* TABLA 15 - SANCIONES*/
CREATE TABLE IF NOT EXISTS sanciones(
  idSancion SMALLINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  idIncidencia SMALLINT UNSIGNED NULL,
  nia CHAR(7) NULL,
  tipoSancion TINYINT  UNSIGNED NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE DEFAULT NULL ,
  observacion VARCHAR(300) NOT NULL,
  idMotivo TINYINT UNSIGNED NOT NULL,
  ultima_sancion TINYINT UNSIGNED NOT NULL,
  CONSTRAINT fk_incidencias_sanciones FOREIGN KEY (idIncidencia) REFERENCES incidencias(idIncidencia) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_tipo_sancion_sanciones FOREIGN KEY (tipoSancion) REFERENCES tipo_sancion(tipoSancion),
  CONSTRAINT fk_motivo_sancion FOREIGN KEY (idMotivo) REFERENCES motivo(idMotivo)
);

/* TABLA 16 - TIPO_SANCION_INCIDENCIAS*/
CREATE TABLE IF NOT EXISTS tipo_sancion_incidencias(
  tipoSancion TINYINT UNSIGNED NOT NULL,
  idTipo TINYINT  UNSIGNED NOT NULL,
  PRIMARY KEY (tipoSancion,idTipo),
  CONSTRAINT fk_tipo_sancion_incidencias_1 FOREIGN KEY (tipoSancion) REFERENCES tipo_sancion(tipoSancion),
  CONSTRAINT fk_tipo_sancion_incidencias_2 FOREIGN KEY (idTipo) REFERENCES tipo_Incidencias(idTipo)
);

/* TABLA 17-GESTION */
CREATE TABLE IF NOT EXISTS gestion(
  idUsuario TINYINT UNSIGNED PRIMARY KEY,
  nombre VARCHAR(20) NOT NULL,
  pass VARCHAR(255) NOT NULL
);

/**
--  ========================== /!\============================= 
--                    __...--~~~~~-._   _.-~~~~~--...__
--                //               `V'               \\ 
--              //                 |                 \\ 
--              //__...--~~~~~~-._  |  _.-~~~~~~--...__\\ 
--            //__.....----~~~~._\ | /_.~~~~----.....__\\
--            ====================\\|//====================
--                                `---`
--                  *** TABLAS DE ACTIVIDADES ***
--  ========================== /!\=============================
*/

/**
	Fecha: 28/04/2022
*/

CREATE TABLE IF NOT EXISTS ACT_Momentos (
	idMomento TINYINT unsigned NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(60) NOT NULL UNIQUE,
	ultimoCelebrado CHAR(5),
	fechaInicio_Inscripcion TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	fechaFin_Inscripcion TIMESTAMP NOT NULL DEFAULT current_timestamp(),
	CONSTRAINT PK_momentos_idMomento PRIMARY KEY (`idMomento`)
);

CREATE TABLE IF NOT EXISTS `ACT_Actividades` (
	`idActividad` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`sexo` CHAR(2) NULL default NULL check(sexo = 'M' OR sexo = 'F' OR sexo = 'MX'),
	`nombre` VARCHAR(60) NOT NULL,
	`esIndividual` BIT NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`numMaxParticipantes` TINYINT unsigned NULL,
	`fechaInicio_Inscripcion` TIMESTAMP NULL DEFAULT current_timestamp(),
	`fechaFin_Inscripcion` TIMESTAMP NULL DEFAULT current_timestamp(),
	`created_at` TIMESTAMP NOT NULL default now(),
	`updated_at` TIMESTAMP NOT NULL default now(),
	`material` VARCHAR(100) NULL,
	`descripcion` VARCHAR(200) NULL,
	`idResponsable` TINYINT unsigned NOT NULL,
	`tipo_Participacion` CHAR(1) NOT NULL check(tipo_Participacion = 'C' OR tipo_Participacion = 'G'),

	CONSTRAINT PK_actividades_idActividad PRIMARY KEY (`idActividad`),

	CONSTRAINT fk_ACT_Actividades_idMomento FOREIGN KEY (idMomento) REFERENCES ACT_Momentos(idMomento) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Actividades_idResponsable FOREIGN KEY (idResponsable) REFERENCES profesores(idUsuario) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `ACT_Estadisticas_Actividad` (
	`idEstadisticaAlumno` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`idActividad` TINYINT unsigned NOT NULL,
	`idEtapa` TINYINT unsigned NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`created_at` TIMESTAMP NOT NULL default now(),
	`updated_at` TIMESTAMP NOT NULL default now(),
	`anioEscolar` CHAR(5) NULL,
	`total_Inscripciones` SMALLINT unsigned NOT NULL,
	CONSTRAINT PK_estadisticasActividad PRIMARY KEY (`idEstadisticaAlumno`),
	CONSTRAINT fk_ACT_Estadisticas_Actividades_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Estadisticas_Actividades_idEtapa FOREIGN KEY (idEtapa) REFERENCES etapas(idEtapa) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Estadisticas_Actividades_idMomento FOREIGN KEY (idMomento) REFERENCES ACT_Momentos(idMomento) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS `ACT_Estadisticas_Totales` (
	`idEstadisticaTotal` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`idEtapa` TINYINT unsigned NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`created_at` TIMESTAMP NOT NULL default now(),
	`updated_at` TIMESTAMP NOT NULL default now(),
	`anioEscolar` CHAR(5) NULL,
	`total_Alumnos` SMALLINT unsigned NOT NULL,
	CONSTRAINT PK_ACT_idEstadisticaTotal PRIMARY KEY (`idEstadisticaTotal`),
	CONSTRAINT fk_ACT_Estadisticas_Totales_idActividad FOREIGN KEY (idMomento) REFERENCES ACT_Estadisticas_Actividad(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Estadisticas_Totales_idEtapa FOREIGN KEY (idEtapa) REFERENCES etapas(idEtapa) ON DELETE CASCADE ON UPDATE CASCADE
);



CREATE TABLE IF NOT EXISTS `ACT_Actividades_Etapas` (
	`idActividad` TINYINT unsigned NOT NULL,
	`idEtapa` TINYINT unsigned NOT NULL,
	CONSTRAINT PK_ACT_idActividad_idEtapa PRIMARY KEY (`idActividad`, `idEtapa`)
);

ALTER TABLE ACT_Actividades_Etapas ADD CONSTRAINT fk_ACT_Actividades_Etapas_idAlumno FOREIGN KEY ACT_Actividades_Etapas(idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE ACT_Actividades_Etapas ADD CONSTRAINT fk_ACT_Actividades_Etapas_idActividad FOREIGN KEY ACT_Actividades_Etapas(idEtapa) REFERENCES etapas(idEtapa) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `ACT_Inscriben_Secciones` (
	`idActividad` TINYINT unsigned NOT NULL,
	`idSeccion` CHAR(5) NOT NULL,
	`fecha_y_hora_Inscripcion` TIMESTAMP NOT NULL default now(),

	CONSTRAINT PK_ACT_Inscriben_idActividad_idSeccion PRIMARY KEY (`idActividad`, `idSeccion`),

	CONSTRAINT fk_ACT_Inscriben_Secciones_idSeccion FOREIGN KEY (idSeccion) REFERENCES secciones(idSeccion) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Inscriben_Secciones_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS `ACT_Inscriben_Alumnos` (
	`idAlumno` INT unsigned NOT NULL,
	`idActividad` TINYINT unsigned NOT NULL,
	`fecha_y_hora_Inscripcion` TIMESTAMP NOT NULL default now(),

	CONSTRAINT PK_ACT_idAlumno PRIMARY KEY (`idAlumno`, `idActividad`),

	CONSTRAINT fk_ACT_Inscriben_Alumnos_idAlumno FOREIGN KEY (idAlumno) REFERENCES alumnos(idAlumno) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Inscriben_Alumnos_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE
);





-- nada que ver

CREATE TABLE IF NOT EXISTS perfiles(
    idPerfil tinyint(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombrePerfil char(4) NOT NULL,
    descripcion varchar(75) NULL
);

CREATE TABLE IF NOT EXISTS perfiles_profesor(
    idUsuario tinyint(3) UNSIGNED NOT NULL,
    idPerfil tinyint(3) UNSIGNED NOT NULL,
    CONSTRAINT fk_perfil_profesor_pro FOREIGN KEY (idUsuario) REFERENCES profesores(idUsuario) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_perfil_profesor_per FOREIGN KEY (idPerfil) REFERENCES perfiles(idPerfil) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE secciones ADD CONSTRAINT fk_secciones_cursos_act FOREIGN KEY (codCurso) REFERENCES act_cursos(codCurso)