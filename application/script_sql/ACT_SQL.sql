/**
	Fecha: 28/04/2022
*/

CREATE TABLE `ACT_Momentos` (
	`idMomento` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`nombre` VARCHAR(60) NOT NULL UNIQUE,
	`ultimoCelebrado` CHAR(5),
	`fechaInicio_Inscripcion` TIMESTAMP NOT NULL,
	`fechaFin_Inscripcion` TIMESTAMP NOT NULL,
	PRIMARY KEY (`idMomento`)
);

CREATE TABLE `ACT_Actividades` (
	`idActividad` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`sexo` CHAR(2) NULL default NULL check(M,F,MX),
	`nombre` VARCHAR(60) NOT NULL,
	`esIndividual` BIT NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`numMaxParticipantes` TINYINT unsigned NULL,
	`fechaInicio_Inscripcion` TIMESTAMP NULL,
	`fechaFin_Inscripcion` TIMESTAMP NULL,
	`created_at` TIMESTAMP NOT NULL default now(),
	`updated_at` TIMESTAMP NOT NULL default now(),
	`material` VARCHAR(100) NULL,
	`descripcion` VARCHAR(200) NULL,
	`idResponsable` SMALLINT unsigned NOT NULL,
	`tipo_Participacion` CHAR(1) NOT NULL check(C,G),
	PRIMARY KEY (`idActividad`)
);

ALTER TABLE ACT_Actividades ADD CONSTRAINT fk_ACT_Actividades_idMomento FOREIGN KEY (idMomento) REFERENCES ACT_Momentos(idMomento) ON DELETE CASCADE, ON UPDATE CASCADE;
ALTER TABLE ACT_Actividades ADD CONSTRAINT fk_ACT_Actividades_idResponsable FOREIGN KEY (idResponsable) REFERENCES Usuarios(idUsuario) ON DELETE CASCADE, ON UPDATE CASCADE;




CREATE TABLE `ACT_Estadisticas_Actividades` (
	`idEstadisticaAlumno` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`idActividad` TINYINT unsigned NOT NULL,
	`idEtapa` TINYINT unsigned NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`created_at` TIMESTAMP NOT NULL default now(),
	`updated_at` TIMESTAMP NOT NULL default now(),
	`anioEscolar` CHAR(5) NULL,
	`total_Inscripciones` SMALLINT unsigned NOT NULL,
	PRIMARY KEY (`idEstadisticaAlumno`)
);

ALTER TABLE ACT_Estadisticas_Actividades ADD CONSTRAINT fk_ACT_Estadisticas_Actividades_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE, ON UPDATE CASCADE;
ALTER TABLE ACT_Estadisticas_Actividades ADD CONSTRAINT fk_ACT_Estadisticas_Actividades_idEtapa FOREIGN KEY (idEtapa) REFERENCES Etapas(idEtapa) ON DELETE CASCADE, ON UPDATE CASCADE;
ALTER TABLE ACT_Estadisticas_Actividades ADD CONSTRAINT fk_ACT_Estadisticas_Actividades_idMomento FOREIGN KEY (idMomento) REFERENCES ACT_Momentos(idMomento) ON DELETE CASCADE, ON UPDATE CASCADE;



CREATE TABLE `ACT_Estadisticas_Totales` (
	`idEstadisticaTotal` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`idEtapa` TINYINT unsigned NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`created_at` TIMESTAMP NOT NULL default now(),
	`updated_at` TIMESTAMP NOT NULL default now(),
	`anioEscolar` CHAR(5) NULL,
	`total_Alumnos` SMALLINT unsigned NOT NULL,
	PRIMARY KEY (`idEstadisticaTotal`)
);

ALTER TABLE ACT_Estadisticas_Totales ADD CONSTRAINT fk_ACT_Estadisticas_Totales_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE, ON UPDATE CASCADE;
ALTER TABLE ACT_Estadisticas_Totales ADD CONSTRAINT fk_ACT_Estadisticas_Totales_idEtapa FOREIGN KEY (idEtapa) REFERENCES Etapas(idEtapa) ON DELETE CASCADE, ON UPDATE CASCADE;


CREATE TABLE `ACT_Actividades_Etapas` (
	`idActividad` TINYINT unsigned NOT NULL,
	`idEtapa` TINYINT unsigned NOT NULL,
	PRIMARY KEY (`idActividad`, `idEtapa`)
);

ALTER TABLE ACT_Actividades_Etapas ADD CONSTRAINT fk_ACT_Actividades_Etapas_idAlumno FOREIGN KEY (idAlumno) REFERENCES Alumnos(idAlumno) ON DELETE CASCADE, ON UPDATE CASCADE;
ALTER TABLE ACT_Actividades_Etapas ADD CONSTRAINT fk_ACT_Actividades_Etapas_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE, ON UPDATE CASCADE;


CREATE TABLE `ACT_Inscriben_Secciones` (
	`idActividad` TINYINT unsigned NOT NULL,
	`idSeccion` TINYINT unsigned NOT NULL,
	`fecha_y_hora_Inscripcion` TIMESTAMP NOT NULL default now(),
	PRIMARY KEY (`idActividad`, `idSeccion`)
);

ALTER TABLE ACT_Inscriben_Secciones ADD CONSTRAINT fk_ACT_Inscriben_Secciones_idSeccion FOREIGN KEY (idSeccion) REFERENCES Secciones(idSeccion) ON DELETE CASCADE, ON UPDATE CASCADE;
ALTER TABLE ACT_Inscriben_Secciones ADD CONSTRAINT fk_ACT_Inscriben_Secciones_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE, ON UPDATE CASCADE;



CREATE TABLE `ACT_Inscriben_Alumnos` (
	`idAlumno` INT unsigned NOT NULL,
	`idActividad` TINYINT unsigned NOT NULL,
	`fecha_y_hora_Inscripcion` TIMESTAMP NOT NULL default now(),
	PRIMARY KEY (`idAlumno`, `idActividad`)
);

ALTER TABLE ACT_Inscriben_Alumnos ADD CONSTRAINT fk_ACT_Inscriben_Alumnos_idAlumno FOREIGN KEY (idAlumno) REFERENCES Alumnos(idAlumno) ON DELETE CASCADE, ON UPDATE CASCADE;
ALTER TABLE ACT_Inscriben_Alumnos ADD CONSTRAINT fk_ACT_Inscriben_Alumnos_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE, ON UPDATE CASCADE;

