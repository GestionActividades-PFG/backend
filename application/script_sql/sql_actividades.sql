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


ALTER TABLE secciones ADD COLUMN codCurso tinyint(3) UNSIGNED NULL;

ALTER TABLE secciones ADD CONSTRAINT fk_secciones_cursos_act FOREIGN KEY (codCurso) REFERENCES act_cursos(codCurso)
