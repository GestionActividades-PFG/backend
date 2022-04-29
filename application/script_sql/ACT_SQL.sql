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
	CONSTRAINT PK_ACT_idEstadisticaTotal PRIMARY KEY (`idEstadisticaTotal`)
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


