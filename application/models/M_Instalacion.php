<?php
/**
 * M_Instalacion
 * 
 * Clase para realizar la instalación de la aplicación.
 * 
 * @author Abraham Núñez Palos y Daniel Torres Galindo
 */
class M_Instalacion extends CI_Model
{
	/**
	 * Guarda el valor de carga de la base de datos.
	 * 
	 * @var undefined
	 *
	 */

	var $bd = NULL;

	/**
	 * __construct
	 *
	 *Carga la base de datos en la variable bd.
	 * 
	 * @return void
	 */
	
	public function __construct()
	{
		parent::__construct();
		$this -> bd = $this -> load -> database('default',true);
	}
	
	/**
	 * tablas
	 *
	 * Crea las tablas en la base de datos , cuando realiza la instalación.
	 * 
	 * @return void
	 */


	public function tablas()
	{
		$tabla = [];
		$tabla[0] = 
			"CREATE TABLE Aplicaciones
				(
					idAplicacion TINYINT UNSIGNED AUTO_INCREMENT,
					nombre VARCHAR(60) NOT NULL UNIQUE,
					descripcion VARCHAR(200) NOT NULL,
					url VARCHAR(100) NOT NULL,
					icono VARCHAR(100) NOT NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY(idAplicacion)
				)ENGINE=INNODB;
			";

		$tabla[1] = 
			"CREATE TABLE Perfiles
				(
					idPerfil TINYINT UNSIGNED AUTO_INCREMENT,
					nombre VARCHAR(60) NOT NULL UNIQUE,
					descripcion VARCHAR(200) NOT NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY(idPerfil)
				)ENGINE=INNODB;
			";

		$tabla[2] = 
			"CREATE TABLE Aplicaciones_Perfiles
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
			";

		$tabla[3] = 
			"CREATE TABLE Usuarios
				(
					idUsuario SMALLINT UNSIGNED AUTO_INCREMENT,
					nombre VARCHAR(60) NOT NULL,
					correo VARCHAR(60) NOT NULL UNIQUE,
					bajaTemporal bit NOT NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY(idUsuario)
				)ENGINE=INNODB;
			";

		$tabla[4] = 
			"CREATE TABLE Perfiles_Usuarios
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
			";

		$tabla[5] = 
			"CREATE TABLE Etapas
				(
					idEtapa TINYINT UNSIGNED AUTO_INCREMENT,
					codEtapa CHAR(5) NOT NULL UNIQUE,
					nombre VARCHAR(40) NOT NULL UNIQUE,
					idCoordinador SMALLINT UNSIGNED NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (idEtapa),
					CONSTRAINT FK_Etapas_Usuarios 
						FOREIGN KEY (idCoordinador) 
							REFERENCES Usuarios (idUsuario) 
								ON DELETE SET NULL 
								ON UPDATE CASCADE
				)ENGINE=INNODB;
			";

		$tabla[6] = 
			"CREATE TABLE Subetapas
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
			";

		$tabla[7] = 
			"CREATE TABLE Cursos
				(
					idCurso TINYINT UNSIGNED AUTO_INCREMENT,
					codCurso CHAR(5) NOT NULL UNIQUE,
					idCursoColegio CHAR(5) NULL UNIQUE,
					nombre VARCHAR(70) NOT NULL UNIQUE,
					idEtapa TINYINT UNSIGNED NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (idCurso),
					CONSTRAINT FK_Cursos_Etapas
						FOREIGN KEY (idEtapa) 
							REFERENCES Etapas (idEtapa) 
								ON DELETE SET NULL 
								ON UPDATE CASCADE
				)ENGINE=INNODB;
			";

		$tabla[8] = 
			"CREATE TABLE FP_Departamentos
				(
					idDepartamento TINYINT UNSIGNED AUTO_INCREMENT,
					nombre VARCHAR(40) NOT NULL UNIQUE,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (idDepartamento)
				)ENGINE=INNODB;
			";

		$tabla[9] =
			 "CREATE TABLE FP_FamiliasProfesionales
				(
					idFamilia TINYINT UNSIGNED AUTO_INCREMENT,
					nombre VARCHAR(40) NOT NULL UNIQUE,
					idDepartamento TINYINT UNSIGNED NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (idFamilia),
					CONSTRAINT FK_FP_FamiliasProfesionales_FP_Departamentos
						FOREIGN KEY (idDepartamento) 
							REFERENCES FP_Departamentos (idDepartamento) 
								ON DELETE SET NULL
								ON UPDATE CASCADE
				)ENGINE=INNODB;
			";

		$tabla[10] = 
			"CREATE TABLE FP_Ciclos
				(
					idCiclo TINYINT UNSIGNED AUTO_INCREMENT,
					codCiclo CHAR(4) NOT NULL UNIQUE,
					nombre VARCHAR(40) NOT NULL UNIQUE,
					idFamilia TINYINT UNSIGNED NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (idCiclo),
					CONSTRAINT FK_FP_Ciclos_FP_FamiliasProfesionales
						FOREIGN KEY (idFamilia)
							REFERENCES FP_FamiliasProfesionales (idFamilia) 
								ON DELETE SET NULL 
								ON UPDATE CASCADE
				)ENGINE=INNODB;
			";

		$tabla[11] = 
			"CREATE TABLE FP_Ciclos_Cursos
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
			";

		$tabla[12] = 
			"CREATE TABLE Secciones
				(
					idSeccion SMALLINT UNSIGNED AUTO_INCREMENT,
					codSeccion CHAR(8) NOT NULL UNIQUE,
					idSeccionColegio CHAR(8) NULL UNIQUE,
					nombre VARCHAR(100) NOT NULL,
					idTutor SMALLINT UNSIGNED NULL UNIQUE,
					idCurso TINYINT UNSIGNED NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (idSeccion),
					CONSTRAINT FK_Secciones_Usuarios
						FOREIGN KEY (idTutor)
							REFERENCES Usuarios (idUsuario) 
								ON DELETE SET NULL 
								ON UPDATE CASCADE,
					CONSTRAINT FK_Secciones_Cursos
						FOREIGN KEY (idCurso)
							REFERENCES Cursos (idCurso) 
								ON DELETE SET NULL 
								ON UPDATE CASCADE
				)ENGINE=INNODB;
			";

		$tabla[13] = 
			"CREATE TABLE Alumnos
				(
					idAlumno INT  UNSIGNED AUTO_INCREMENT,
					NIA INT UNSIGNED NOT NULL UNIQUE,
					nombre VARCHAR(60) NOT NULL,
					DNI CHAR(9) NULL,
					idSeccion SMALLINT UNSIGNED NOT NULL,
					correo VARCHAR(60) NULL,
					sexo enum('m','f') NOT NULL,
					telefono CHAR(9) NULL,
					telefonoUrgencia CHAR(9) NULL,
					fechaNacimiento DATE NOT NULL,
					created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
					updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
					PRIMARY KEY (idAlumno),
					CONSTRAINT FK_Alumnos_Secciones
						FOREIGN KEY (idSeccion)
							REFERENCES Secciones (idSeccion) 
								ON DELETE CASCADE 
								ON UPDATE CASCADE
				)ENGINE=INNODB;
			";

		for($i = 0; $i < 14; $i++)
			$this -> bd -> query($tabla[$i]);

	}


}

?>
