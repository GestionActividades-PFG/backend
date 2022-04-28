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
  codEtapa CHAR(5) PRIMARY KEY,
  nombre VARCHAR(30) NOT NULL,
  coordinador TINYINT UNSIGNED DEFAULT NULL,
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
CREATE TABLE  IF NOT EXISTS alumnos(
  nia CHAR (7) PRIMARY KEY,
  nombreCompleto VARCHAR(50) NOT NULL,
  telefono VARCHAR(9) NOT NULL,
  sexo CHAR(1) NOT NULL,
  idSeccion CHAR(6) NOT NULL ,
  numPartes tinyint(4) DEFAULT NULL,
  CONSTRAINT fk_secciones_alumnos  FOREIGN KEY (idSeccion) REFERENCES secciones(idSeccion)
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
  === TABLAS DE ACTIVIDADES ==
*/

/*
    B.D arreglada...
*/
CREATE TABLE IF NOT EXISTS act_momento(
	idMomento tinyint(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nombreMomento varchar(25) NOT NULL
);

CREATE TABLE IF NOT EXISTS act_categorias(
	idCategoria char(1) PRIMARY KEY,
  nombreCategoria varchar(35) NOT NULL
);

CREATE TABLE IF NOT EXISTS act_cursos(
  codCurso tinyint(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombreCurso varchar(20) NOT NULL,
  idCategoria char(1) NOT NULL,
  CONSTRAINT fk_cursos_categorias FOREIGN KEY (idCategoria) REFERENCES act_categorias(idCategoria) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS act_actividad(
    idActividad tinyint(7) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombreActividad varchar(35) NOT NULL,
    monitor varchar(50) NULL,
    sexo char(1) NULL,
    concurso char(1) NOT NULL,
    urlBases varchar(255) NULL,
    fechaInicio date NOT NULL,
    fechaFin date NOT NULL,
    maxClase tinyint(1) NOT NULL,
    tipoAct char(1) NOT NULL,
    momento tinyint(3) UNSIGNED NOT NULL,
    CONSTRAINT fk_actividad_momento FOREIGN KEY (momento) REFERENCES act_momento(idMomento) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS act_individual_al(
    idActividad tinyint(7) UNSIGNED NOT NULL,
    NIA char(7) NOT NULL,
    CONSTRAINT pk_act_individual_al PRIMARY KEY (idActividad, NIA),
    CONSTRAINT fk_act_individual_actividad FOREIGN KEY (idActividad) REFERENCES act_actividad(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_act_individual_nia FOREIGN KEY (NIA) REFERENCES alumnos(nia) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS act_grupo(
    idActividad tinyint(7) UNSIGNED NOT NULL PRIMARY KEY,
    alumnos char(1) NOT NULL,
    CONSTRAINT fk_grupo_actividad FOREIGN KEY (idActividad) REFERENCES act_actividad(idActividad) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS act_insc_grupo(
    numGrupo tinyint(7) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    idActividad tinyint(7) UNSIGNED NOT NULL,
    idSeccion char(6) NOT NULL,
    CONSTRAINT fk_insc_grupo_actividad FOREIGN KEY (idActividad) REFERENCES act_grupo(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_insc_grupo_seccion FOREIGN KEY (idSeccion) REFERENCES secciones(idSeccion) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS act_detalle_al_grupo(
    numGrupo tinyint(7) UNSIGNED NOT NULL,
    nia char(7) NOT NULL,
    CONSTRAINT pk_act_detalle_grupo PRIMARY KEY (numGrupo,nia),
    CONSTRAINT fk_detalle_numGrupo FOREIGN KEY (numGrupo) REFERENCES act_insc_grupo(numGrupo) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_nia FOREIGN KEY (nia) REFERENCES alumnos(nia) ON DELETE CASCADE ON UPDATE CASCADE
    
);

CREATE TABLE IF NOT EXISTS act_actividad_cat(
    idActividad tinyint(7) UNSIGNED NOT NULL,
    idCategoria char(1) NOT NULL,
    CONSTRAINT pk_actividad_cat PRIMARY KEY (idActividad,idCategoria),
    CONSTRAINT fk_actividad_actividad_categoria FOREIGN KEY (idActividad) REFERENCES act_actividad(idActividad) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_actividad_act_cat FOREIGN KEY (idCategoria) REFERENCES act_categorias(idCategoria) ON DELETE CASCADE ON UPDATE CASCADE
);

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