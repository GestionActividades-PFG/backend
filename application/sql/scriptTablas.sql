/*
--	Script SQL Grupo anterior, proyecto base
*/


-- START TRANSACTION;
-- DROP DATABASE IF EXISTS Gestion_Escuela;
-- CREATE DATABASE Gestion_Escuela DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE Gestion_Escuela;

/*
--	Script SQL Grupo anterior, proyecto base
*/


CREATE TABLE Aplicaciones
(
	idAplicacion TINYINT UNSIGNED AUTO_INCREMENT,
	nombre VARCHAR(60) NOT NULL UNIQUE,
	descripcion VARCHAR(200) NOT NULL,
	url VARCHAR(100) NOT NULL,
	icono VARCHAR(100) NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idAplicacion)
)ENGINE=INNODB;

CREATE TABLE Perfiles
(
	idPerfil TINYINT UNSIGNED AUTO_INCREMENT,
	nombre VARCHAR(60) NOT NULL UNIQUE,
	descripcion VARCHAR(200) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idPerfil)
)ENGINE=INNODB;

CREATE TABLE Aplicaciones_Perfiles
(
	idPerfil TINYINT UNSIGNED,
	idAplicacion TINYINT UNSIGNED,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_Aplicaciones_Perfiles PRIMARY KEY (idPerfil, idAplicacion),
	
	CONSTRAINT FK_Aplicaciones_Perfiles_Perfiles 
		FOREIGN KEY (idPerfil) 
			REFERENCES Perfiles (idPerfil) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE,
				
	CONSTRAINT FK_Aplicaciones_Perfiles_Aplicaciones 
		FOREIGN KEY (idAplicacion) 
			REFERENCES Aplicaciones (idAplicacion) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE Usuarios
(
	idUsuario SMALLINT UNSIGNED AUTO_INCREMENT,
	nombre VARCHAR(60) NOT NULL,
	correo VARCHAR(60) NOT NULL UNIQUE,
	bajaTemporal BIT NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idUsuario)
)ENGINE=INNODB;

CREATE TABLE Perfiles_Usuarios
(
	idPerfil TINYINT UNSIGNED,
	idUsuario SMALLINT UNSIGNED,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_Perfiles_Usuarios PRIMARY KEY (idPerfil, idUsuario),
	
	CONSTRAINT FK_Perfiles_Usuarios_Perfiles 
		FOREIGN KEY (idPerfil) 
			REFERENCES Perfiles (idPerfil) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE,
				
	CONSTRAINT FK_Perfiles_Usuarios_Usuarios 
		FOREIGN KEY (idUsuario) 
			REFERENCES Usuarios (idUsuario) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE Etapas
(
	idEtapa TINYINT UNSIGNED AUTO_INCREMENT,
	codEtapa CHAR(5) NOT NULL UNIQUE,
	nombre VARCHAR(40) NOT NULL UNIQUE,
	idCoordinador SMALLINT UNSIGNED NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idEtapa),
	
	CONSTRAINT FK_Etapas_Usuarios 
		FOREIGN KEY (idCoordinador) 
			REFERENCES Usuarios (idUsuario) 
				ON DELETE SET NULL 
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE Subetapas
(
	idEtapa TINYINT UNSIGNED,
	idEtapaPadre TINYINT UNSIGNED,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_Subetapas PRIMARY KEY (idEtapa, idEtapaPadre),
	
	CONSTRAINT FK_Subetapas_Etapas
		FOREIGN KEY (idEtapa) 
			REFERENCES Etapas (idEtapa) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE,
				
	CONSTRAINT FK_Subetapas_EtapasPadre
		FOREIGN KEY (idEtapaPadre) 
			REFERENCES Etapas (idEtapa) 
				ON DELETE CASCADE
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE Cursos
(
	idCurso TINYINT UNSIGNED AUTO_INCREMENT,
	codCurso CHAR(5) NOT NULL UNIQUE,
	nombre VARCHAR(40) NULL UNIQUE,
	idEtapa TINYINT UNSIGNED NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idCurso),
	
	CONSTRAINT FK_Cursos_Etapas
		FOREIGN KEY (idEtapa) 
			REFERENCES Etapas (idEtapa) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE FP_Departamentos
(
	idDepartamento TINYINT UNSIGNED AUTO_INCREMENT,
	nombre VARCHAR(40) NOT NULL UNIQUE,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idDepartamento)
)ENGINE=INNODB;

CREATE TABLE FP_FamiliasProfesionales
(
	idFamilia TINYINT UNSIGNED AUTO_INCREMENT,
	nombre VARCHAR(40) NOT NULL UNIQUE,
	idDepartamento TINYINT UNSIGNED NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idFamilia),
	
	CONSTRAINT FK_FP_FamiliasProfesionales_FP_Departamentos
		FOREIGN KEY (idDepartamento) 
			REFERENCES FP_Departamentos (idDepartamento) 
				ON DELETE SET NULL
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE FP_Ciclos
(
	idCiclo TINYINT UNSIGNED AUTO_INCREMENT,
	codCiclo CHAR(4) NOT NULL UNIQUE,
	nombre VARCHAR(40) NOT NULL UNIQUE,
	idFamilia TINYINT UNSIGNED NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idCiclo),
	
	CONSTRAINT FK_FP_Ciclos_FP_FamiliasProfesionales
		FOREIGN KEY (idFamilia)
			REFERENCES FP_FamiliasProfesionales (idFamilia) 
				ON DELETE SET NULL 
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE FP_Ciclos_Cursos
(
	idCiclo TINYINT UNSIGNED,
	idCurso TINYINT UNSIGNED, 
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_FP_Ciclos_Cursos PRIMARY KEY (idCiclo, idCurso),
	
	CONSTRAINT FK_FP_Ciclos_Cursos_FP_Ciclos
		FOREIGN KEY (idCiclo)
			REFERENCES FP_Ciclos (idCiclo) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE,
				
	CONSTRAINT FK_FP_Ciclos_Cursos_Cursos
		FOREIGN KEY (idCurso)
			REFERENCES Cursos (idCurso) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE
)ENGINE=INNODB;


CREATE TABLE Secciones
(
	idSeccion SMALLINT UNSIGNED AUTO_INCREMENT,
	codSeccion CHAR(6) NOT NULL UNIQUE,
	nombre VARCHAR(100) NOT NULL UNIQUE,
	idTutor SMALLINT UNSIGNED NULL UNIQUE,
	idCurso TINYINT UNSIGNED NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idSeccion),
	
	CONSTRAINT FK_Secciones_Usuarios
		FOREIGN KEY (idTutor)
			REFERENCES Usuarios (idUsuario) 
				ON DELETE SET NULL 
				ON UPDATE CASCADE,
				
	CONSTRAINT FK_Secciones_Cursos
		FOREIGN KEY (idCurso)
			REFERENCES Cursos (idCurso) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE Alumnos
(
	idAlumno INT UNSIGNED NOT NULL AUTO_INCREMENT,
	NIA INT UNSIGNED NOT NULL UNIQUE,
	nombre VARCHAR(60) NOT NULL,
	DNI CHAR(9) NULL,
	idSeccion SMALLINT UNSIGNED NOT NULL,
	correo VARCHAR(60) NULL,
	sexo ENUM('m','f') NOT NULL,
	telefono CHAR(9) NOT NULL,
	telefonoUrgencia CHAR(9) NULL,
	fechaNacimiento DATE NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	PRIMARY KEY(idAlumno),
	
	CONSTRAINT FK_Alumnos_Secciones
		FOREIGN KEY (idSeccion)
			REFERENCES Secciones (idSeccion) 
				ON DELETE CASCADE 
				ON UPDATE CASCADE
)ENGINE=INNODB;

INSERT INTO
	Perfiles (nombre, descripcion)
VALUES 
	('Administrador', 'Administrador'),
	('Gestor', 'Gestor'),
	('Profesor', 'Profesor'),
	('Tutor', 'Tutor');
COMMIT;

/*-------------------------------------------------------------------------------------------------------*/	
--											ACTIVIDADES
/*-------------------------------------------------------------------------------------------------------*/	

CREATE TABLE IF NOT EXISTS ACT_Momentos (
	idMomento TINYINT unsigned NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(60) NOT NULL UNIQUE,
	ultimoCelebrado CHAR(5),
	fechaInicio_Inscripcion DATETIME NOT NULL,
	fechaFin_Inscripcion DATETIME NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT PK_Momentos PRIMARY KEY (`idMomento`)
);

CREATE TABLE IF NOT EXISTS `ACT_Actividades` (
	`idActividad` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	`sexo` CHAR(2) NOT NULL default 'NP' check(sexo = 'M' OR sexo = 'F' OR sexo = 'MX' OR sexo = 'NP'),
	`nombre` VARCHAR(60) NOT NULL,
	`esIndividual` BIT NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`numMaxParticipantes` TINYINT unsigned NULL,
	`fechaInicio_Actividad` DATETIME NULL,
	`fechaFin_Actividad` DATETIME NULL,
	`material` VARCHAR(100) NULL,
	`descripcion` VARCHAR(200) NULL,
	`idResponsable` SMALLINT UNSIGNED NOT NULL,
	`tipo_Participacion` CHAR(1) NOT NULL check(tipo_Participacion = 'C' OR tipo_Participacion = 'G'),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_Actividades PRIMARY KEY (`idActividad`),
	CONSTRAINT fk_ACT_Actividades_idMomento FOREIGN KEY (idMomento) REFERENCES ACT_Momentos(idMomento) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Actividades_idResponsable FOREIGN KEY (idResponsable) REFERENCES Usuarios(idUsuario) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `ACT_Individuales` (
	`idActividad` TINYINT unsigned NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,

	CONSTRAINT PK_ACT_Individuales PRIMARY KEY (`idActividad`),
	CONSTRAINT fk_ACT_Individuales_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `ACT_Parejas` (
	`idActividad` TINYINT unsigned NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,

	CONSTRAINT PK_ACT_Parejas PRIMARY KEY (`idActividad`),
	CONSTRAINT fk_ACT_Parejas_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `ACT_Clase` (
	`idActividad` TINYINT unsigned NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,

	CONSTRAINT PK_ACT_Clase PRIMARY KEY (`idActividad`),
	CONSTRAINT fk_ACT_Clase_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE

);

CREATE TABLE IF NOT EXISTS `ACT_Actividades_Etapas` (
	`idActividad` TINYINT unsigned NOT NULL,
	`idEtapa` TINYINT unsigned NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_ACT_idActividad_idEtapa PRIMARY KEY (`idActividad`, `idEtapa`),
	CONSTRAINT fk_ACT_Actividades_Etapas_idAlumno FOREIGN KEY ACT_Actividades_Etapas(idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Actividades_Etapas_idActividad FOREIGN KEY ACT_Actividades_Etapas(idEtapa) REFERENCES Etapas(idEtapa) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `ACT_Inscriben_Secciones` (
	`idActividad` TINYINT unsigned NOT NULL,
	`idSeccion` SMALLINT UNSIGNED NOT NULL,
	`fecha_y_hora_Inscripcion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_ACT_Inscriben_Secciones PRIMARY KEY (`idActividad`, `idSeccion`),
	CONSTRAINT fk_ACT_Inscriben_Secciones_idSeccion FOREIGN KEY (idSeccion) REFERENCES Secciones(idSeccion) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Inscriben_Secciones_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Clase(idActividad) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE IF NOT EXISTS `ACT_Inscriben_Alumnos` (
	`idAlumno` INT unsigned NOT NULL,
	`idActividad` TINYINT unsigned NOT NULL,
	`fecha_y_hora_Inscripcion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,

	CONSTRAINT PK_ACT_Inscriben_Alumnos PRIMARY KEY (`idAlumno`, `idActividad`),
	CONSTRAINT fk_ACT_Inscriben_Alumnos_idAlumno FOREIGN KEY (idAlumno) REFERENCES Alumnos(idAlumno) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Inscriben_Alumnos_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Individuales(idActividad) ON DELETE CASCADE ON UPDATE CASCADE
);

/*
CREATE TABLE IF NOT EXISTS `ACT_Parejas_Alumnos` (
--	`idAlumno` INT unsigned NOT NULL,
--	`idPareja` INT unsigned NOT NULL,
--	`fecha_y_hora_Inscripcion` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
--	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
--	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
--
--	CONSTRAINT PK_ACT_Parejas_Alumnos PRIMARY KEY (`idAlumno`,`idPareja`),
--	CONSTRAINT fk_ACT_Parejas_Alumnos_idAlumno FOREIGN KEY (idAlumno) REFERENCES ACT_Parejas(idAlumno) ON DELETE CASCADE ON UPDATE CASCADE,
--	CONSTRAINT fk_ACT_Parejas_Alumnos_idPareja FOREIGN KEY (idPareja) REFERENCES ACT_Parejas(idPareja) ON DELETE CASCADE ON UPDATE CASCADE
);
*/

CREATE TABLE IF NOT EXISTS `ACT_Estadisticas_Actividad` (
	`idEstadisticaAlumno` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`idActividad` TINYINT unsigned NOT NULL,
	`idEtapa` TINYINT unsigned NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`anioEscolar` CHAR(5) NULL,
	`total_Inscripciones` SMALLINT unsigned NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_Estadisticas_Actividad PRIMARY KEY (`idEstadisticaAlumno`),
	CONSTRAINT fk_ACT_Estadisticas_Actividades_idActividad FOREIGN KEY (idActividad) REFERENCES ACT_Actividades(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Estadisticas_Actividades_idEtapa FOREIGN KEY (idEtapa) REFERENCES Etapas(idEtapa) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Estadisticas_Actividades_idMomento FOREIGN KEY (idMomento) REFERENCES ACT_Momentos(idMomento) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS `ACT_Estadisticas_Totales` (
	`idEstadisticaTotal` TINYINT unsigned NOT NULL AUTO_INCREMENT,
	`idEtapa` TINYINT unsigned NOT NULL,
	`idMomento` TINYINT unsigned NOT NULL,
	`anioEscolar` CHAR(5) NULL,
	`total_Alumnos` SMALLINT unsigned NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
	
	CONSTRAINT PK_ACT_Estadisticas_Totales PRIMARY KEY (`idEstadisticaTotal`),
	CONSTRAINT fk_ACT_Estadisticas_Totales_idMomento FOREIGN KEY (idMomento) REFERENCES ACT_Momentos(idMomento) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT fk_ACT_Estadisticas_Totales_idEtapa FOREIGN KEY (idEtapa) REFERENCES Etapas(idEtapa) ON DELETE CASCADE ON UPDATE CASCADE
);

/*-------------------------INSERT---------------------------------------*/

/*
--	coordinador eso(21)-->COORDINADOR ESO  y ISA(23)-->COORDINADORA CF
--	Esperanza(18),Sergio(19) , Luis(20), Isa(23) y Manu(24) --> ADMIN
-- 	tutor 1esob(22) --> TUTOR 1ESOB
*/

INSERT INTO `Usuarios` (`idUsuario`, `nombre`, `correo`, `bajaTemporal`, `created_at`, `updated_at`) VALUES 
(NULL, 'Marta Romero Ramirez', 'mromeroramirez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Carlos Cuello Díaz', 'ccuellodiaz.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Álvaro Espinosa Martínez', 'aespinosamartinez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Mateo Espinosa García', 'mespinosagarcia.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Daniel Molinos Correa', 'dmolinoscorreo.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Marcos García García', 'mgarciagarcia.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Carmen Ramamírez Bernaldez', 'cramirezbernaldez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Joaquín Rodríguez Rodríguez', 'jrodriguezrodriguez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Rocio Galván Rueda', 'rgalvanrueda.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Martina Rivas Silva', 'mrivassilva.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Susana Sopa Reyes', 'ssopareyes.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Lola Gutierrez Silva', 'lgutierrezsilva.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Mario Rodríguez Galindo', 'mrodriguezgalindo.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Carla Villoslada Martín', 'cvillosladamartin.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Roberto Plata Recio', 'rplatarecio.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Paula Andrade Martínez', 'pandrademartinez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Manuela Merina Gonzalez', 'mmerinagonzalez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Esperanza Rodríguez Martínez', 'erodriguezmartinez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Sergio Matamoros Delgado', 'smatamorosdelgado.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'Luis Marzal de la Concepción', 'lmarzalconcepcion.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp()),
(NULL, 'coordinador eso', 'gacoordinador@gmail.com', '', current_timestamp(), current_timestamp()),
(NULL, 'tutor 1esob', 'gatutor1esob@gmail.com', '', current_timestamp(), current_timestamp()),
(NULL, 'Isabel Muñoz', 'imunoz@fundacionloyola.es', '', current_timestamp(), current_timestamp()),
(NULL, 'Manuel Solis Gomez', 'msolisgomez.guadalupe@alumnado.fundacionloyola.net', '', current_timestamp(), current_timestamp());


INSERT INTO `Etapas` (`idEtapa`, `codEtapa`, `nombre`, `idCoordinador`, `created_at`, `updated_at`) VALUES 
(NULL, 'ESO', 'Educación Secundaria Obligatoria', '21', current_timestamp(), current_timestamp()),
(NULL, 'BACH', 'Bachillerato', '17', current_timestamp(), current_timestamp()),
(NULL, 'CF', 'Ciclo Formativo', '23', current_timestamp(), current_timestamp());


INSERT INTO `Cursos` (`idCurso`, `codCurso`, `nombre`, `idEtapa`, `created_at`, `updated_at`) VALUES 
(NULL, '1ESO', '1º Educación Secundaria Obligatoria', '1', current_timestamp(), current_timestamp()),
(NULL, '2ESO', '2º Educación Secundaria Obligatoria', '1', current_timestamp(), current_timestamp()),
(NULL, '3ESO', '3º Educación Secundaria Obligatoria', '1', current_timestamp(), current_timestamp()),
(NULL, '4ESO', '4º Educación Secundaria Obligatoria', '1', current_timestamp(), current_timestamp()),
(NULL, '1BACH', '1º Bachillerato', '2', current_timestamp(), current_timestamp()),
(NULL, '2BACH', '2º Bachillerato', '2', current_timestamp(), current_timestamp()),
(NULL, '1SMR', '1º Sistemas Microinformáticos y Redes', '3', current_timestamp(), current_timestamp()),
(NULL, '2SMR', '2º Sistemas Microinformáticos y Redes', '3', current_timestamp(), current_timestamp()),
(NULL, '1DAW', '1º Desarrollo de Aplicaciones Web', '3', current_timestamp(), current_timestamp()),
(NULL, '2DAW', '2º Desarrollo de Aplicaciones Web', '3', current_timestamp(), current_timestamp());


INSERT INTO `Secciones` (`idSeccion`, `codSeccion`, `nombre`, `idTutor`, `idCurso`, `created_at`, `updated_at`) VALUES 
(NULL, '1ESOA', '1º Educación Secundaria Obligatoria A', '1', '1', current_timestamp(), current_timestamp()),
(NULL, '1ESOB', '1º Educación Secundaria Obligatoria B', '22', '1', current_timestamp(), current_timestamp()),
(NULL, '1ESOC', '1º Educación Secundaria Obligatoria C', '3', '1', current_timestamp(), current_timestamp()),
(NULL, '2ESOA', '2º Educación Secundaria Obligatoria A', '4', '2', current_timestamp(), current_timestamp()),
(NULL, '2ESOB', '2º Educación Secundaria Obligatoria B', '5', '2', current_timestamp(), current_timestamp()),
(NULL, '2ESOC', '2º Educación Secundaria Obligatoria C', '6', '2', current_timestamp(), current_timestamp()),
(NULL, '3ESOA', '3º Educación Secundaria Obligatoria A', '7', '3', current_timestamp(), current_timestamp()),
(NULL, '3ESOB', '3º Educación Secundaria Obligatoria B', '8', '3', current_timestamp(), current_timestamp()),
(NULL, '3ESOC', '3º Educación Secundaria Obligatoria C', '9', '3', current_timestamp(), current_timestamp()),
(NULL, '4ESOA', '4º Educación Secundaria Obligatoria A', '10', '4', current_timestamp(), current_timestamp()),
(NULL, '4ESOB', '4º Educación Secundaria Obligatoria B', '11', '4', current_timestamp(), current_timestamp()),
(NULL, '4ESOC', '4º Educación Secundaria Obligatoria C', '12', '4', current_timestamp(), current_timestamp()),
(NULL, '1SMR', '1º Sistemas Microinformáticos y Redes', '13', '5', current_timestamp(), current_timestamp()),
(NULL, '2SMR', '2º Sistemas Microinformáticos y Redes', '14', '5', current_timestamp(), current_timestamp()),
(NULL, '1DAW', '1º Desarrollo de Aplicaciones Web', '15', '6', current_timestamp(), current_timestamp()),
(NULL, '2DAW', '2º Desarrollo de Aplicaciones Web', '16', '6', current_timestamp(), current_timestamp());


INSERT INTO `Perfiles_Usuarios` (`idPerfil`, `idUsuario`, `created_at`, `updated_at`) VALUES 
('2', '24', current_timestamp(), current_timestamp()),
('3', '24', current_timestamp(), current_timestamp()),
('2', '23', current_timestamp(), current_timestamp()),
('3', '23', current_timestamp(), current_timestamp()),
('1', '18', current_timestamp(), current_timestamp()),
('1', '19', current_timestamp(), current_timestamp()),
('1', '20', current_timestamp(), current_timestamp()),
('2', '17', current_timestamp(), current_timestamp()),
('3', '17', current_timestamp(), current_timestamp()),
('2', '21', current_timestamp(), current_timestamp()),
('3', '21', current_timestamp(), current_timestamp()),
('4', '22', current_timestamp(), current_timestamp()),
('3', '22', current_timestamp(), current_timestamp()),
('3', '1', current_timestamp(), current_timestamp()),
('3', '2', current_timestamp(), current_timestamp()),
('3', '3', current_timestamp(), current_timestamp()),
('3', '4', current_timestamp(), current_timestamp()),
('3', '5', current_timestamp(), current_timestamp()),
('3', '6', current_timestamp(), current_timestamp()),
('3', '7', current_timestamp(), current_timestamp()),
('3', '8', current_timestamp(), current_timestamp()),
('3', '9', current_timestamp(), current_timestamp()),
('3', '10', current_timestamp(), current_timestamp()),
('3', '11', current_timestamp(), current_timestamp()),
('3', '12', current_timestamp(), current_timestamp()),
('3', '13', current_timestamp(), current_timestamp()),
('3', '14', current_timestamp(), current_timestamp()),
('3', '15', current_timestamp(), current_timestamp()),
('3', '16', current_timestamp(), current_timestamp()),
('4', '1', current_timestamp(), current_timestamp()),
('4', '2', current_timestamp(), current_timestamp()),
('4', '3', current_timestamp(), current_timestamp()),
('4', '4', current_timestamp(), current_timestamp()),
('4', '5', current_timestamp(), current_timestamp()),
('4', '6', current_timestamp(), current_timestamp()),
('4', '7', current_timestamp(), current_timestamp()),
('4', '8', current_timestamp(), current_timestamp()),
('4', '9', current_timestamp(), current_timestamp()),
('4', '10', current_timestamp(), current_timestamp()),
('4', '11', current_timestamp(), current_timestamp()),
('4', '12', current_timestamp(), current_timestamp()),
('4', '13', current_timestamp(), current_timestamp()),
('4', '14', current_timestamp(), current_timestamp()),
('4', '15', current_timestamp(), current_timestamp()),
('4', '16', current_timestamp(), current_timestamp());

 -- Definimos ubicación de las Aplicacaiones
 
 INSERT INTO `Aplicaciones` (`idAplicacion`, `nombre`, `descripcion`, `url`, `icono`, `created_at`, `updated_at`) VALUES 
(NULL, 'AdministracionEVG', 'Administración de EVG', ' app/1', 'administracion.jpg', current_timestamp(), current_timestamp()),
(NULL, 'GestionEVG ', 'Administración de EVG', ' app/2', 'gestion.jpg', current_timestamp(), current_timestamp()),
(NULL, 'Gestion Aplicaciones', 'Administración de EVG', ' https://04.2daw.esvirgua.com/Actividades-Front/', NULL, current_timestamp(), current_timestamp());


-- Asignamos permisos a los perfiles

INSERT INTO `Aplicaciones_Perfiles` (`idPerfil`, `idAplicacion`, `created_at`, `updated_at`) VALUES 
('1', '1', current_timestamp(), current_timestamp()),
('1', '2', current_timestamp(), current_timestamp()),
('1', '3', current_timestamp(), current_timestamp()),
('2', '1', current_timestamp(), current_timestamp()),
('2', '2', current_timestamp(), current_timestamp()),
('3', '3', current_timestamp(), current_timestamp()),
('4', '3', current_timestamp(), current_timestamp()),
('2', '3', current_timestamp(), current_timestamp());


/*5 Alumnos para cada clase: 1ESOA,1ESOB,1ESOC,2ESOA,2ESOB,2ESOC,1SMR,2SMR,1DAW,2DAW*/

INSERT INTO `Alumnos` (`idAlumno`, `NIA`, `nombre`, `DNI`, `idSeccion`, `correo`, `sexo`, `telefono`, `telefonoUrgencia`, `fechaNacimiento`, `created_at`, `updated_at`) VALUES 
(NULL, '235125421', 'Alfredo Domínguez Sopa', '03215625G', '1', 'adomiguezsopa.guadalupe@alumnado.fundacionloyola.net', 'm', '652145255', '655222214', '02/01/2009', current_timestamp(), current_timestamp()),
(NULL, '021452355', 'Isabel Martínez Moreno', '03215225G', '1', 'imartinezmoreno.guadalupe@alumnado.fundacionloyola.net', 'f', '652145266', '655252563', '02/02/2009', current_timestamp(), current_timestamp()),
(NULL, '447589654', 'Carlos Gonzalez Ramirez', '03215115G', '1', 'cgonzalezramirez.guadalupe@alumnado.fundacionloyola.net', 'm', '625352636', '653656565', '12/03/2009', current_timestamp(), current_timestamp()),
(NULL, '471563588', 'Sara Rodríguez Gambino', '03215135G', '1', 'srodriguezgambino.guadalupe@alumnado.fundacionloyola.net', 'f', '652452652', '651151544', '22/04/2009', current_timestamp(), current_timestamp()),
(NULL, '374569963', 'Marcos Romero Martín', '03215625A', '1', 'mromeromartinez.guadalupe@alumnado.fundacionloyola.net', 'm', '652654123', '659595948', '04/05/2009', current_timestamp(), current_timestamp()),
(NULL, '471428596', 'Tomás Gambino Marcial', '03625414H', '2', 'tgambinomarcial.guadalupe@alumnado.fundacionloyola.net', 'm', '655654987', '652262626', '22/12/2009', current_timestamp(), current_timestamp()),
(NULL, '369655214', 'Carmen Vazquez Silva', '23235214H', '2', 'cvazquezsilva.guadalupe@alumnado.fundacionloyola.net', 'f', '632258369', '652652652', '11/11/2009', current_timestamp(), current_timestamp()),
(NULL, '153357531', 'Juan Carlos Romero Zambrano', '32547856A', '2', 'jcromerozambrano.guadalupe@alumnado.fundacionloyola.net', 'm', '654654654', '652142525', '12/03/2009', current_timestamp(), current_timestamp()),
(NULL, '159951563', 'Pilar Domínguez Carretera', '32147856S', '2', 'pdominguezcarretera.guadalupe@alumnado.fundacionloyola.net', 'f', '652365236', '652145455', '14/02/2009', current_timestamp(), current_timestamp()),
(NULL, '852258527', 'Ismael Vélez Martínez', '26541698D', '2', 'ivelezmartinez.guadalupe@alumnado.fundacionloyola.net', 'm', '655578963', '652147888', '15/11/2009', current_timestamp(), current_timestamp()),
(NULL, '147711472', 'Ramon Silva Matamoros', '32587456F', '3', 'rsilvamatamoros.guadalupe@alumnado.fundacionloyola.net', 'm', '652658521', '651951951', '02/04/2009', current_timestamp(), current_timestamp()),
(NULL, '369933691', 'Silvia Zambrano Marín', '32578459G', '3', 'szambranomarin.guadalupe@alumnado.fundacionloyola.net', 'f', '65311775', '654369147', '03/05/2009', current_timestamp(), current_timestamp()),
(NULL, '189977896', 'Francisco Pozo Carrasco', '32541555E', '3', 'fpozocarrasco.guadalupe@alumnado.fundacionloyola.net', 'm', '654888662', '654258369', '12/01/2009', current_timestamp(), current_timestamp()),
(NULL, '456654562', 'Almudena Rivera Díaz', '26547854F', '3', 'ariveradiaz.guadalupe@alumnado.fundacionloyola.net', 'f', '655215265', '657369258', '07/09/2009', current_timestamp(), current_timestamp()),
(NULL, '123321230', 'Jesús Cuello Marcial', '256654785F', '3', 'jcuellomarcial.guadalupe@alumnado.fundacionloyola.net', 'm', '652485914', '654748484', '09/01/2009', current_timestamp(), current_timestamp()),
(NULL, '030200604', 'Daniel Fernández Carrillo', '21456325A', '4', 'dfernandezcarrillo.guadalupe@alumnado.fundacionloyola.net', 'm', '65414255', '652362515', '23/09/2008', current_timestamp(), current_timestamp()),
(NULL, '242655984', 'Matilda Correa Cano', '36526985A', '4', 'mcorreacano.guadalupe@alumnado.fundacionloyola.net', 'f', '657474874', '652363625', '02/01/2006', current_timestamp(), current_timestamp()),
(NULL, '759863210', 'Miguel Ángel Ferrera García', '32501420A', '4', 'maferreragarcia.guadalupe@alumnado.fundacionloyola.net', 'm', '652144759', '653653653', '21/08/2008', current_timestamp(), current_timestamp()),
(NULL, '147539175', 'Lola Rodríguez Villoslada', '20147856D', '4', 'lrodriguezvilloslada.guadalupe@alumnado.fundacionloyola.net', 'f', '652145155', '658658658', '03/01/2008', current_timestamp(), current_timestamp()),
(NULL, '852645264', 'Marcos Carapeto Ramos', '23652102D', '4', 'mcarapetoramos.guadalupe@alumnado.fundacionloyola.net', 'm', '652145225', '659659659', '07/01/2008', current_timestamp(), current_timestamp()),
(NULL, '963148520', 'Alberto García Fernandez', '03258745D', '5', 'agarciafernandez.guadalupe@alumnado.fundacionloyola.net', 'm', '652245255', '657657657', '03/02/2008', current_timestamp(), current_timestamp()),
(NULL, '147893214', 'Marta Vallecillo Gonzalez', '14785236D', '5', 'mvallecillogonzalez.guadalupe@alumnado.fundacionloyola.net', 'f', '622145255', '650650650', '05/12/2008', current_timestamp(), current_timestamp()),
(NULL, '111223366', 'Juan Manuel Galván Rueda', '21452365F', '5', 'jmgalvanrueda.guadalupe@alumnado.fundacionloyola.net', 'm', '652135255', '652333111', '14/02/2008', current_timestamp(), current_timestamp()),
(NULL, '222558884', 'Carmen Jaramillo Marcial', '23658745F', '5', 'cjaramillomarcial.guadalupe@alumnado.fundacionloyola.net', 'f', '652145244', '652444777', '10/02/2008', current_timestamp(), current_timestamp()),
(NULL, '115598877', 'Saúl García Silva', '74596582T', '5', 'sgarciasilva.guadalupe@alumnado.fundacionloyola.net', 'm', '652145222', '653999888', '03/03/2008', current_timestamp(), current_timestamp()),
(NULL, '335574466', 'Fernando Cano Gonzalez', '23652102T', '6', 'fcanogonzalez.guadalupe@alumnado.fundacionloyola.net', 'm', '652145226', '658777999', '23/04/2008', current_timestamp(), current_timestamp()),
(NULL, '186552200', 'Ángela Ferrera Marcial', '11122233T', '6', 'aferreramarcial.guadalupe@alumnado.fundacionloyola.net', 'f', '652145227', '654999333', '22/05/2008', current_timestamp(), current_timestamp()),
(NULL, '000112236', 'Gonzalo Fernandez Vazquez', '22266698Y', '6', 'gfernandezvazquez.guadalupe@alumnado.fundacionloyola.net', 'm', '652115255', '654111333', '12/06/2008', current_timestamp(), current_timestamp()),
(NULL, '999633221', 'Tania Suarez Carrasco', '55544223Y', '6', 'tsuarezcarrasco.guadalupe@alumnado.fundacionloyola.net', 'f', '652144784', '654888222', '11/02/2008', current_timestamp(), current_timestamp()),
(NULL, '111111414', 'Manuel Solís Gomez', '25632541J', '6', 'msolisgomez.guadalupe@alumnado.fundacionloyola.net', 'm', '652146599', '654777999', '14/04/2008', current_timestamp(), current_timestamp()),
(NULL, '151512635', 'Ruben Solís Correa', '23012058J', '13', 'rsoliscorrea.guadalupe@alumnado.fundacionloyola.net', 'm', '653200210', '651999222', '22/01/2005', current_timestamp(), current_timestamp()),
(NULL, '159151599', 'Laura Fernandez Díaz', '12545875K', '13', 'lfernandezdiaz.guadalupe@alumnado.fundacionloyola.net', 'f', '652014798', '651333777', '15/12/2005', current_timestamp(), current_timestamp()),
(NULL, '357353577', 'Arturo Carrión Domínguez', '26354785K', '13', 'acarriondominguez.guadalupe@alumnado.fundacionloyola.net', 'm', '651999333', '625114422', '11/11/2005', current_timestamp(), current_timestamp()),
(NULL, '351268410', 'María Silva Gamero', '66655598K', '13', 'msilvagamero.guadalupe@alumnado.fundacionloyola.net', 'f', '652365211', '651444666', '12/01/2005', current_timestamp(), current_timestamp()),
(NULL, '222233333', 'Juan Antonio Galván Silva', '25412578U', '13', 'jagalvansilva.guadalupe@alumnado.fundacionloyola.net', 'm', '653252522', '65494944', '02/04/2005', current_timestamp(), current_timestamp()),
(NULL, '111112222', 'Juan Gamero Silva', '15632547U', '14', 'jgamerosilva.guadalupe@alumnado.fundacionloyola.net', 'm', '652145111', '653222222', '22/12/2004', current_timestamp(), current_timestamp()),
(NULL, '177779999', 'Raquel Solís Ramos', '32588754U', '14', 'rsolisramos.guadalupe@alumnado.fundacionloyola.net', 'f', '652145999', '656664477', '02/03/2004', current_timestamp(), current_timestamp()),
(NULL, '444446686', 'Adrian Jaramillo García', '26531485I', '14', 'ajaramillogarcia.guadalupe@alumnado.fundacionloyola.net', 'm', '65214555', '655114422', '12/02/2004', current_timestamp(), current_timestamp()),
(NULL, '222228888', 'Alondra Ramirez Cuello', '21032015I', '14', 'aramirezcuello.guadalupe@alumnado.fundacionloyola.net', 'f', '652111147', '655114411', '04/01/2004', current_timestamp(), current_timestamp()),
(NULL, '166667777', 'Juan Carlos Moreno Nieto', '63521478I', '14', 'jcmorenonieto.guadalupe@alumnado.fundacionloyola.net', 'm', '652696933', '655114433', '05/05/2004', current_timestamp(), current_timestamp()),
(NULL, '161616116', 'Gabriél Romero Fonseca', '02147856O', '15', 'gromerofonseca.guadalupe@alumnado.fundacionloyola.net', 'm', '652858585', '655114444', '02/12/2003', current_timestamp(), current_timestamp()),
(NULL, '171717117', 'Esmeralda Malavé Gamero', '21452369O', '15', 'emalavegamero.guadalupe@alumnado.fundacionloyola.net', 'f', '652969696', '655114455', '04/03/2003', current_timestamp(), current_timestamp()),
(NULL, '181818181', 'Jose Rodríguez Marredo', '65214788P', '15', 'jrodriguezmarredo.guadalupe@alumnado.fundacionloyola.net', 'm', '652363636', '655114466', '05/07/2003', current_timestamp(), current_timestamp()),
(NULL, '212121212', 'Adriana Martín Serrano', '20320145P', '15', 'amartinezserrano.guadalupe@alumnado.fundacionloyola.net', 'f', '652141414', '655114477', '12/08/2003', current_timestamp(), current_timestamp()),
(NULL, '010203020', 'Juan Manuel Moreno Martínez', '63598745P', '15', 'jmmorenomartinez.guadalupe@alumnado.fundacionloyola.net', 'm', '652474747', '655114488', '32/09/2003', current_timestamp(), current_timestamp()),
(NULL, '000111002', 'Jacobo Zambrano Romero', '21452365L', '16', 'jzambranoromero.guadalupe@alumnado.fundacionloyola.net', 'm', '652282828', '655114499', '02/07/2002', current_timestamp(), current_timestamp()),
(NULL, '010407080', 'Lourdes Serrano Ramos', '65327895L', '16', 'lserranoramos.guadalupe@alumnado.fundacionloyola.net', 'f', '652010203', '655114412', '03/03/2002', current_timestamp(), current_timestamp()),
(NULL, '157584946', 'Lorezon Esturrica Moreno', '362514178L', '16', 'lesturricamoreno.guadalupe@alumnado.fundacionloyola.net', 'm', '652140506', '655114413', '07/07/2002', current_timestamp(), current_timestamp()),
(NULL, '262651452', 'Macarena Fonseca Sopa', '45217896R', '16', 'mfonsecasopa.guadalupe@alumnado.fundacionloyola.net', 'f', '652989878', '655114414', '04/01/2002', current_timestamp(), current_timestamp()),
(NULL, '252625265', 'Miguel Marredo Malavé', '02541288R', '16', 'mmarredomalave.guadalupe@alumnado.fundacionloyola.net', 'm', '652144548', '655114415', '02/11/2002', current_timestamp(), current_timestamp());


INSERT INTO `ACT_Momentos` (`idMomento`, `nombre`, `ultimoCelebrado`, `fechaInicio_Inscripcion`, `fechaFin_Inscripcion`, `created_at`, `updated_at`) VALUES 
(NULL, 'Navidad', NULL, '2022-10-10 23:21:30.000000', '2022-11-10 23:21:30.000000', current_timestamp(), current_timestamp()),
(NULL, 'Semana Ingnaciana', NULL,'2022-04-09 23:21:30.000000', '2022-04-22 23:21:30.000000', current_timestamp(), current_timestamp()),
(NULL, 'Fiestas Escolares', NULL,'2022-05-11 23:21:30.000000','2022-05-27 23:21:30.000000', current_timestamp(), current_timestamp());


INSERT INTO `ACT_Actividades` (`idActividad`, `sexo`, `nombre`, `esIndividual`, `idMomento`, `numMaxParticipantes`, `fechaInicio_Actividad`,
 `fechaFin_Actividad`, `material`, `descripcion`, `idResponsable`, `tipo_Participacion`, `created_at`, `updated_at`) VALUES
 (NULL, 'NP', 'Consurso de Migas', 0, '1', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
 'Productos necesarios para cocinar las migas', 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '14', 'G', current_timestamp(), current_timestamp()),
 (NULL, 'NP', 'Consurso de Fotografía', 1, '1', NULL, '2022-05-10 23:21:30.000000','2022-05-15 23:21:30.000000', 
 NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '12', 'G', current_timestamp(), current_timestamp()),
 (NULL, 'NP', 'Consurso de Tortilla', 0, '2', NULL, '2022-05-10 23:21:30.000000','2022-05-15 23:21:30.000000', 
 'Productos necesarios para cocinar la tortilla', 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '14', 'G', current_timestamp(), current_timestamp()),
   (NULL, 'NP', 'Consurso de Fotografía', 1, '2', NULL, '2022-05-10 23:21:30.000000','2022-05-15 23:21:30.000000', 
 NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '12', 'G', current_timestamp(), current_timestamp()),
  (NULL, 'MX', 'Futbol',0, '1', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '10', 'C', current_timestamp(), current_timestamp()),
   (NULL, 'MX', 'Futbol',0, '2', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '10', 'C', current_timestamp(), current_timestamp()),
    (NULL, 'MX', 'Futbol',0, '3', NULL, '2022-05-10 23:21:30.000000','2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '10', 'C', current_timestamp(), current_timestamp()),
    (NULL, 'M', 'Baloncesto',1, '1', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '11', 'C', current_timestamp(), current_timestamp()),
    (NULL, 'M', 'Baloncesto',1, '2', NULL, '2022-05-10 23:21:30.000000','2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '11', 'C', current_timestamp(), current_timestamp()),
     (NULL, 'M', 'Baloncesto',1, '3', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '11', 'C', current_timestamp(), current_timestamp()),
     (NULL, 'F', 'Balonmano',1, '1', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '09', 'C', current_timestamp(), current_timestamp()),
     (NULL, 'F', 'Carrera',1, '2', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '09', 'C', current_timestamp(), current_timestamp()),
      (NULL, 'F', 'Balonmano',0, '3', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '09', 'C', current_timestamp(), current_timestamp()),
      (NULL, 'MX', 'Tenis',0, '1', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '08', 'C', current_timestamp(), current_timestamp()),
       (NULL, 'MX', 'Tenis',0, '2', NULL, '2022-05-10 23:21:30.000000', '2022-05-15 23:21:30.000000', 
NULL, 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. 
 Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta)
 desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. ', '08', 'C', current_timestamp(), current_timestamp());
 
 
 -- Definimos el tipo de Actividad (Individuales,Clase o de Pareja)

INSERT INTO `ACT_Individuales` (`idActividad`) VALUES 
(2),
(4),
(8),
(9),
(10),
(11),
(12);

INSERT INTO `ACT_Clase` (`idActividad`) VALUES 
(1),
(3),
(5),
(6),
(7);

INSERT INTO `ACT_Parejas` (`idActividad`) VALUES 
(13),
(14),
(15);
 
-- Asignamos Actividades a las Etapas
 
 INSERT INTO `ACT_Actividades_Etapas` (`idActividad`, `idEtapa`) VALUES 
(1,1),
(3,1),
(5,3),
(6,3),
(7,3),
(2,1),
(4,1),
(8,1),
(9,3),
(10,3),
(11,3),
(12,2),
(13,2),
(14,2),
(15,2);
 


/*
-- Agregamos parejas

INSERT INTO `ACT_Parejas_Alumnos` (`idAlumno`, `idPareja`) VALUES 
(1,2),
(3,4);
*/

 -- Inscripciones por Clase
 
 INSERT INTO `ACT_Inscriben_Secciones` (`idActividad`, `idSeccion`) VALUES 
(1,2),
(3,2),
(5,12),
(6,12),
(7,2),
(1,12),
(3,12),
(5,16),
(6,16),
(7,14),
(1,16),
(3,16),
(5,15),
(6,14),
(7,10);

 -- Inscripciones Individuales
 
 INSERT INTO `ACT_Inscriben_Alumnos` (`idActividad`, `idAlumno`) VALUES 
(2,1),
(2,5),
(2,16),
(2,18),
(2,11),
(4,11),
(4,12),
(4,2),
(4,3),
(4,5),
(8,12),
(8,17),
(8,19),
(8,20),
(8,22),
(9,1),
(9,2),
(9,8),
(9,9),
(9,27),
(10,28),
(10,22),
(10,21),
(10,20),
(10,25),
(11,16),
(11,26),
(11,28),
(11,3),
(11,30),
(12,31),
(12,32),
(12,22),
(12,7),
(12,28);
 