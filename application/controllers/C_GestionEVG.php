<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * C_GestionEVG
 * 
 * Clase que contiene todos los métodos necesario para la aplicación de gestión.
 * 
 * @author Abraham Núñez Palos y Daniel Torres Galindo
 */
class C_GestionEVG extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();

		$this -> load -> helper('form');
		$this -> load -> library('form_validation');
		$this -> load -> helper('url');
		$this -> load -> model('M_General');
		$this -> load -> library('google');
		$this -> load -> library('excel');
		$this -> load -> helper('cookie');

		$data['google_login_url'] = $this -> google -> get_login_url();

        if($this->session->userdata('sess_logged_in') == 0 || !$idUsuario = $this -> M_General->obtenerIdUsuario($_SESSION['email']))
		{
        	redirect('Auth');
		}
		else
		{
        	$acceso = false;

			$aplicaciones = $this -> M_General -> seleccionar('Aplicaciones a','distinct(a.url), a.nombre, a.icono',"idUsuario=".$idUsuario,['Aplicaciones_Perfiles ap','Perfiles_Usuarios pu'], ['a.idAplicacion = ap.idAplicacion','pu.idPerfil = ap.idPerfil'], ['join','join']);
			foreach($aplicaciones as $valor)
				if( $valor['nombre'] == 'GestionEVG' || $valor['nombre'] == 'AdministracionEVG' )
					$acceso = true;

			if(!$acceso)
				redirect('Grid');
		}
	}
	
	/**
	 * index
	 * 
	 * Redirige al Grid.
	 *
	 * @return void
	 */
	public function index()
	{
		redirect('Grid');
	}
	
	/**
	 * comprobarCSU
	 * 
	 * Comprueba que no se repitan los mismos valores.
	 *
	 * @return void
	 */
	public function comprobarCSU()
	{
		$numeroFilas = $this -> M_General -> seleccionar($_POST['tabla'],$_POST['campo'],$_POST['campo']."='".$_POST['valor']."'");

		if (!empty($numeroFilas))
			echo('si');
		else
			echo('no');
	}
	
	/**
	 * comprobarUsuarios
	 * 
	 * Busca usuarios en la base de datos.
	 *
	 * @return void
	 */

	public function comprobarUsuarios()
	{
		$usuarios = $this -> M_General -> seleccionar('Usuarios','*',"idUsuario NOT IN(
			SELECT idUsuario
			FROM Perfiles_Usuarios
			WHERE idPerfil=".$_POST['idPerfil']."
		) AND correo LIKE ('%".$_POST['valor']."%')");
		echo(json_encode($usuarios));
	}

		/*USUARIOS*/
	
	/**
	 * verUsuarios
	 * 
	 * Muestra los usuarios existentes.
	 *
	 * @return void
	 */

	public function verUsuarios($importedUsers = 0)
	{
		$this -> importedUsers = $importedUsers;
		$lista = $this -> M_General -> seleccionar('Usuarios','idUsuario, correo, bajaTemporal');
		foreach ($lista as $valor)
		{
			$this -> listaUsuarios[$valor['idUsuario']] = $valor['correo'];
			$this -> listaBajaTemporal[$valor['idUsuario']] = $valor['bajaTemporal'];
		}

		$this -> load -> view('C_Usuarios/V_Usuarios');
	}
	
	/**
	 * anadirUsuarioForm
	 * 
	 * Muestra el formulario para añadir a un usuario.
	 *
	 * @return void
	 */

	public function anadirUsuarioForm()
	{
		$this -> load -> view("C_Usuarios/V_AnadirUsuario");
	}
	
	/**
	 * anadirUsuario
	 * 
	 * Añade un usuario a la base de datos.
	 *
	 * @return void
	 */

	public function anadirUsuario()
	{
		$datos["nombre"] = $_POST["nombre"];
		$datos["correo"] = $_POST["correo"];

		$idUsuario = $this -> M_General -> insertar('Usuarios',$datos);

		$idPerfil = $this -> M_General -> seleccionar('Perfiles', 'idPerfil', "nombre='profesor'");
		if(isset($_POST['profesor']))
			$this -> M_General -> insertar('Perfiles_Usuarios', Array('idPerfil' => $idPerfil[0]['idPerfil'],'idUsuario' => $idUsuario));

		$this->headerLocation("users");
	}
	
	/**
	 * borrarUsuario
	 * 
	 * Elimina a un usuario de la base de datos.
	 *
	 * @param  integer $idUsuario Identificador del usuario.
	 * @return void
	 */
	public function borrarUsuario($idUsuario)
	{
		$this -> M_General -> borrar('Usuarios', $idUsuario, 'idUsuario');

		$this->headerLocation("users");
	}
	
	/**
	 * modificarUsuarioForm
	 * 
	 * Muestra un formulario para modificar los datos del usuario.
	 *
	 * @param  integer $idUsuario Identificador del usuario.
	 * @return void
	 */

	public function modificarUsuarioForm($idUsuario)
	{
		$this -> datosUsuario = $this -> M_General -> seleccionar('Usuarios','nombre, correo, bajaTemporal','idUsuario='.$idUsuario);
		$this -> load -> view("C_Usuarios/V_ModificarUsuario", Array('idUsuario' => $idUsuario));
	}
	
	/**
	 * modificarUsuario
	 * 
	 * Actualiza los datos del usuario.
	 *
	 * @param  integer $idUsuario Identificador del usuario.
	 * @return void
	 */

	public function modificarUsuario($idUsuario)
	{
		$datos["nombre"] = $_POST["nombre"];
		$datos["correo"] = $_POST["correo"];
		if(isset($_POST["bajaTemporal"]))
			$datos["bajaTemporal"] = 1;
		else
			$datos["bajaTemporal"] = 0;

		$this -> M_General -> modificar('Usuarios',$datos,$idUsuario,'idUsuario');

		$this->headerLocation("users");
	}
	
	/**
	 * importarUsuariosForm
	 * 
	 * Muestrea el formualario para importar a los usuarios.
	 *
	 * @return void
	 */

	public function importarUsuariosForm()
	{
		$this -> load -> view('C_Usuarios/V_ImportarUsuarios');
	}

	/**
	 * importarUsuarios
	 *
	 * Importa a los usuarios de un documento excel añadiendo los datos a la base de datos.
	 *
	 * @return void
	 * @throws PHPExcel_Reader_Exception
	 */

	public function importarUsuarios()
	{
		$destino = 'uploads/'; /*Lugar donde se guardará el archivo subido al servidor*/
		$archivo_nombre = $_FILES["archivo"]["name"]; /*Guarda el nombre del archivo*/
		if(file_exists($destino.$archivo_nombre)){
			$contador = 0;
			while(file_exists($destino.++$contador."-".$archivo_nombre));
			$archivo_nombre = $contador."-".$archivo_nombre;
		}
		$archivo_temporal = $_FILES["archivo"]["tmp_name"]; /*Direccion temporal donde se va a guardar el archivo*/
		copy($archivo_temporal,$destino.$archivo_nombre); /*Copia el archivo de la carpeta temporal a la carpeta destino en el servidor*/

		$object = PHPExcel_IOFactory::load($_FILES["archivo"]["tmp_name"]);
		foreach($object -> getWorksheetIterator() as $worksheet) {
			$highestRow = $worksheet->getHighestRow();
			for ($row = 2; $row <= $highestRow; $row++){
				$nombre = $worksheet -> getCellByColumnAndRow(0, $row) -> getValue();
				if(empty($nombre))/* por si la tabla tiene una longitud máxima mayor que los datos que tiene*/
					break;
				$correo = $this -> M_General -> seleccionar('Usuarios', 'correo', "correo='".$worksheet -> getCellByColumnAndRow(1, $row) -> getValue()."'");
				if(empty($correo[0]['correo']))
				{
					$datos[$row]['nombre'] = $nombre;
					$datos[$row]['correo'] = $worksheet -> getCellByColumnAndRow(1, $row) -> getValue();
					$datos[$row]['profesor'] = $worksheet -> getCellByColumnAndRow(2, $row) -> getValue();
				}
			}
		}
		/*Contador para saber cuántos usuarios se han importado*/
		$c = 0;
		foreach($datos as $valor)
		{
			$idUsuario = $this -> M_General -> insertar('Usuarios', Array('nombre' => $valor['nombre'], 'correo' => $valor['correo']));
			if($valor['profesor'] == 'si') 
			{
				$idPerfil = $this -> M_General -> seleccionar('Perfiles', 'idPerfil', "nombre='Profesor'");
				$this -> M_General -> insertar('Perfiles_Usuarios', Array('idPerfil' => $idPerfil[0]['idPerfil'], 'idUsuario' => $idUsuario));
			}
			$c++;
		}

		set_cookie('importedUsers', $c, 0);

		$this -> headerLocation("users");
	}

		/*ETAPAS*/
	
	/**
	 * verEtapas
	 * 
	 * Muestra las etapas existente.
	 *
	 * @return void
	 */

	public function verEtapas()
	{
		$lista = $this -> M_General -> seleccionar('Etapas','idEtapa, codEtapa');
		foreach ($lista as $valor)
			$this -> listaEtapas[$valor['idEtapa']] = $valor['codEtapa'];

		$this -> load -> view('C_Etapas/V_Etapas');
	}
	
	/**
	 * anadirEtapaForm
	 * 
	 * Muestra un formualario para añadir las etapas.
	 *
	 * @return void
	 */
	
	public function anadirEtapaForm()
	{
		$lista = $this -> M_General -> seleccionar('Usuarios','idUsuario, correo');
		$this -> usuarios = array(0=>'Ninguno');
		foreach ($lista as $valor)
			$this -> usuarios[$valor['idUsuario']] = $valor['correo'];

		$this -> load -> view("C_Etapas/V_AnadirEtapa");
	}
	
	/**
	 * anadirEtapa
	 * 
	 * Añade la etapa a la base de datos.
	 *
	 * @return void
	 */

	public function anadirEtapa()
	{
		$datos["codEtapa"] = $_POST["codEtapa"];
		$datos["nombre"] = $_POST["nombre"];
		if($_POST['idCoordinador'] != 0)
			$datos["idCoordinador"] = $_POST["idCoordinador"];

		$this -> M_General -> insertar('Etapas',$datos);

		$this->headerLocation("stages");
	}
	
	/**
	 * borrarEtapa
	 *
	 * Borra la etapa de la base de datos.
	 * 
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @return void
	 */

	public function borrarEtapa($idEtapa)
	{
		$this -> M_General -> borrar('Etapas',$idEtapa,'idEtapa');

		$this->headerLocation("stages");
	}
	
	/**
	 * modificarEtapaForm
	 * 
	 * Muestra un formulario para modificar los datos de una etapa.
	 *
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @return void
	 */

	public function modificarEtapaForm($idEtapa)
	{
		$this -> datosEtapa = $this -> M_General -> seleccionar('Etapas','codEtapa, nombre, idCoordinador','idEtapa='.$idEtapa);

		$lista = $this -> M_General -> seleccionar('Usuarios','idUsuario, correo');
		$this -> usuarios = array(0=>'Ninguno');
		foreach($lista as $valor)
			$this -> usuarios[$valor['idUsuario']] = $valor['correo'];

		$this -> load -> view("C_Etapas/V_ModificarEtapa", Array('idEtapa' => $idEtapa ));
	}
	
	/**
	 * modificarEtapa
	 *
	 * Actualiza la etapa de la base de datos.
	 * 
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @return void
	 */

	public function modificarEtapa($idEtapa)
	{
		$datos["codEtapa"] = $_POST["codEtapa"];
		$datos["nombre"] = $_POST["nombre"];
		if($_POST['idCoordinador'] != 0)
			$datos["idCoordinador"] = $_POST["idCoordinador"];
		else
			$datos['idCoordinador'] = null;

		$this -> M_General -> modificar('Etapas',$datos,$idEtapa,'idEtapa');

		$this->headerLocation("stages");
	}
	
	/**
	 * etapaPadre
	 *
	 * Muestra las etapas padres a la que pertenece,
	 * ademas de permitir añadir o quitar de una etapa padre.
	 * 
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @return void
	 */

	public function etapaPadre($idEtapa)
	{
		$codEtapa = $this -> M_General->seleccionar('Etapas','codEtapa, nombre, idCoordinador','idEtapa='.$idEtapa);
		$codEtapa = $codEtapa[0]['codEtapa'];

		$lista = $this -> M_General -> seleccionar('Etapas e','e.idEtapa, S.idEtapaPadre,E2.codEtapa','e.idEtapa='.$idEtapa,['Subetapas S','Etapas E2'], ['e.idEtapa = S.idEtapa','S.idEtapaPadre=E2.idEtapa'],['join','join']);
		foreach($lista as $valor)
			$this -> etapasPadre[$valor['idEtapaPadre']] = $valor['codEtapa'];

		$lista2 = $this -> M_General->seleccionar('Etapas e','e.idEtapa, e.codEtapa','e.idEtapa!='.$idEtapa.' AND e.idEtapa NOT IN (SELECT s2.idEtapaPadre FROM Etapas e2 INNER JOIN Subetapas s2 ON e2.idEtapa = s2.idEtapa WHERE e2.idEtapa='.$idEtapa.' )',['Subetapas s'], ['e.idEtapa = s.idEtapa'],['left']);
		foreach($lista2 as $valor)
			$this -> etapasNoPadre[$valor['idEtapa']] = $valor['codEtapa'];

		$this -> load -> view('C_Etapas/V_EtapasPadre', Array('idEtapa' => $idEtapa, 'codEtapa' => $codEtapa));
	}
	
	/**
	 * quitarEtapaPadre
	 * 
	 * Quita una etapa padre.
	 *
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @param  integer $idEtapaPadre Identificador de la etapaPadre.
	 * @return void
	 */

	public function quitarEtapaPadre($idEtapa, $idEtapaPadre)
	{
		$this -> M_General -> borrarCompuesta('Subetapas',$idEtapa, $idEtapaPadre, 'idEtapa', 'idEtapaPadre');

		$this->headerLocation("father-stage/".$idEtapa);
	}
	
	/**
	 * anadirEtapaPadre
	 * 
	 * Añade una etapa padre.
	 *
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @param  integer $idEtapaPadre Identificador de la etapaPadre.
	 * @return void
	 */

	public function anadirEtapaPadre($idEtapa, $idEtapaPadre)
	{
		$this -> M_General -> insertar('Subetapas',array('idEtapa' => $idEtapa,'idEtapaPadre' => $idEtapaPadre));

		$this->headerLocation("father-stage/".$idEtapa);
	}

		/*CURSOS*/

	
	/**
	 * verCursos
	 * 
	 * Muestra los cursos disponibles.
	 *
	 * @return void
	 */

	public function verCursos()
	{
		$lista = $this -> M_General -> seleccionar('Cursos','idCurso, codCurso, idEtapa');
		foreach ($lista as $valor)
		{
			$this -> listaCursos[$valor['idCurso']] = $valor['codCurso'];
			$this -> listaEtapasCursos[$valor['idCurso']] = $valor['idEtapa'];
		}

		$this -> load -> view('C_Cursos/V_Cursos');
	}
	
	/**
	 * anadirCursoForm
	 * 
	 * Muestra un formulario para añadir un curso.
	 *
	 * @return void
	 */

	public function anadirCursoForm()
	{
		$this -> load -> view("C_Cursos/V_AnadirCurso");
	}
	
	/**
	 * anadirCurso
	 *
	 * Añade un curso a la base de datos.
	 * 
	 * @return void
	 */

	public function anadirCurso()
	{
		$datos = array();
		$datos["codCurso"] = $_POST["codCurso"];
		$datos["nombre"] = $_POST["nombre"];
		if(!empty($_POST['idCursoColegio']))
			$datos["idCursoColegio"] = $_POST["idCursoColegio"];

		$this -> M_General -> insertar('Cursos',$datos);

		$this->headerLocation("courses");
	}
	
	/**
	 * borrarCurso
	 *
	 * Borra un curso de la base de datos.
	 * 
	 * @param  integer $idCurso Identificador del curso.
	 * @return void
	 */

	public function borrarCurso($idCurso)
	{
		$this -> M_General -> borrar('Cursos',$idCurso,'idCurso');

		$this->headerLocation("courses");
	}
	
	/**
	 * modificarCursoForm
	 * 
	 * Muestra un formulario que permite editar los datos del curso.
	 *
	 * @param  integer $idCurso Identificador del curso.
	 * @return void
	 */
	public  function modificarCursoForm($idCurso)
	{
		$this -> datosCurso = $this -> M_General -> seleccionar('Cursos','idCursoColegio, codCurso, nombre, idEtapa','idCurso='.$idCurso);
		$this -> load -> view("C_Cursos/V_ModificarCurso", Array('idCurso' => $idCurso));
	}
	
	/**
	 * modificarCurso
	 * 
	 * Actualiza los datos del curso de la base de datos.
	 *
	 * @param  integer $idCurso Identificador del curso.
	 * @return void
	 */

	public function modificarCurso($idCurso)
	{
		$datos["codCurso"] = $_POST["codCurso"];
		$datos["nombre"] = $_POST["nombre"];
		$datos["idCursoColegio"] = $_POST["idCursoColegio"];

		$this -> M_General -> modificar('Cursos',$datos,$idCurso,'idCurso');

		$this->headerLocation("courses");
	}
	
	/**
	 * asignarEtapaCursoForm
	 * 
	 * Formulario para asignar una etapa a un curso.
	 *
	 * @param  integer $idCurso Identificador del curso.
	 * @return void
	 */

	public function asignarEtapaCursoForm($idCurso)
	{
		$codCurso = $this -> M_General -> seleccionar('Cursos','idCursoColegio, codCurso, nombre, idEtapa','idCurso='.$idCurso);
		$idEtapa = $codCurso[0]['idEtapa'];
		$codCurso = $codCurso[0]['codCurso'];

		$lista = $this -> M_General -> seleccionar('Etapas','idEtapa, codEtapa');
		$this -> etapas = Array(0 => 'Ninguna');
		foreach($lista as $valor)
			$this -> etapas[$valor['idEtapa']] = $valor['codEtapa'];

		$this -> load -> view('C_Cursos/V_EtapasCurso', Array('idCurso' => $idCurso, 'codCurso' => $codCurso, 'idEtapa' => $idEtapa));
	}
	
	/**
	 * asignarEtapaCurso
	 * 
	 * Asigna la etapa al curso en la base de datos.
	 *
	 * @param  integer $idCurso Identificador del curso.
	 * @return void
	 */

	public function asignarEtapaCurso($idCurso)
	{
		if($_POST['etapa']==0)
			$datos['idEtapa'] = null;
		else
			$datos["idEtapa"] = $_POST["etapa"];

		$this -> M_General -> modificar('Cursos',$datos,$idCurso,'idCurso');

		$this->headerLocation("courses");
	}
	
	/**
	 * importarCursosForm
	 * 
	 * Muestra un formulario para importar los cursos.
	 *
	 * @return void
	 */

	public function importarCursosForm()
	{
		$this -> load -> view('C_Cursos/V_ImportarCursos');
	}

	/**
	 * importarCursos
	 *
	 * Importa un curso desde un documento excel añadiendo los datos a la base de datos.
	 *
	 * @return void
	 * @throws PHPExcel_Reader_Exception
	 */

	public function importarCursos()
	{
		$destino = 'uploads/'; /*Lugar donde se guardará el archivo subido al servidor*/
		$archivo_nombre = $_FILES["archivo"]["name"]; /*Guarda el nombre del archivo*/
		if(file_exists($destino.$archivo_nombre)){
			$contador = 0;
			while(file_exists($destino.++$contador."-".$archivo_nombre));
			$archivo_nombre = $contador."-".$archivo_nombre;
		}
		$archivo_temporal = $_FILES["archivo"]["tmp_name"]; /*Direccion temporal donde se va a guardar el archivo*/
		copy($archivo_temporal,$destino.$archivo_nombre); /*Copia el archivo de la carpeta temporal a la carpeta destino en el servidor*/

		$object = PHPExcel_IOFactory::load($_FILES["archivo"]["tmp_name"]);
		foreach($object -> getWorksheetIterator() as $worksheet) {
			$highestRow = $worksheet->getHighestRow();
			for ($row = 2; $row <= $highestRow; $row++){
				$codCurso = $worksheet -> getCellByColumnAndRow(0, $row) -> getValue();
				if(empty($codCurso))/* por si la tabla tiene una longitud máxima mayor que los datos que tiene*/
					break;
				$codCursoComprobar = $this -> M_General -> seleccionar('Cursos', 'codCurso', "codCurso='".$codCurso."'");
				if(empty($codCursoComprobar[0]['codCurso'])) {
					$datos[$row]['codCurso'] = $codCurso;
					$datos[$row]['nombre'] = $worksheet -> getCellByColumnAndRow(1, $row) -> getValue();
				}
			}
		}
		/*Contador para saber cuántos cursos se han importado*/
		$c = 0;
		foreach($datos as $valor)
		{
			$this -> M_General -> insertar('Cursos', Array('nombre' => $valor['nombre'], 'codCurso' => $valor['codCurso']));
			$c++;
		}

		set_cookie('importedCourses', $c, 0);

		$this -> headerLocation("courses");

	}

		/*DEPARTAMENTOS*/
	
	/**
	 * verDepartamentos
	 * 
	 * Muestra los departamento existentes.
	 *
	 * @return void
	 */

	public function verDepartamentos()
	{
		$lista = $this -> M_General -> seleccionar('FP_Departamentos','idDepartamento, nombre');
		foreach ($lista as $valor)
			$this -> listaDepartamentos[$valor['idDepartamento']] = $valor['nombre'];

		$this -> load -> view('C_Departamentos/V_Departamentos');
	}
	
	/**
	 * anadirDepartamentoForm
	 * 
	 * Muestra un formulario para añadir un departamento.
	 *
	 * @return void
	 */

	public function anadirDepartamentoForm()
	{
		$this -> load -> view("C_Departamentos/V_AnadirDepartamento");
	}
	
	/**
	 * anadirDepartamento
	 * 
	 * Añade un departamento a la base de datos.
	 *
	 * @return void
	 */

	public function anadirDepartamento()
	{
		$datos["nombre"] = $_POST["nombre"];

		$this -> M_General -> insertar('FP_Departamentos',$datos);

		$this->headerLocation("departments");
	}
	
	/**
	 * borrarDepartamento
	 *
	 * Eliminar un departamento a la base de datos.
	 * 
	 * @param  integer $idDepartamento Identificador del departamento.
	 * @return void
	 */

	public function borrarDepartamento($idDepartamento)
	{
		$this -> M_General -> borrar('FP_Departamentos',$idDepartamento,'idDepartamento');

		$this->headerLocation("departments");
	}
	
	/**
	 * modificarDepartamentoForm
	 * 
	 * Muestra un formulario con los datos de un departamento.
	 *
	 * @param  integer $idDepartamento Identificador del departamento.
	 * @return void
	 */

	public  function modificarDepartamentoForm($idDepartamento)
	{
		$this -> datosDepartamento = $this -> M_General -> seleccionar('FP_Departamentos','nombre','idDepartamento='.$idDepartamento);
		$this -> load -> view("C_Departamentos/V_ModificarDepartamento", Array('idDepartamento' => $idDepartamento));
	}
	
	/**
	 * modificarDepartamento
	 * 
	 * Actualiza los datos de un departameto en la base de datos.
	 *
	 * @param  integer $idDepartamento Identificador del departamento.
	 * @return void
	 */
	public function modificarDepartamento($idDepartamento)
	{
		$datos["nombre"] = $_POST["nombre"];

		$this -> M_General -> modificar('FP_Departamentos',$datos,$idDepartamento,'idDepartamento');

		$this->headerLocation("departments");
	}

		/*FAMILIAS PROFESIONALES*/
	
	/**
	 * verFamilias
	 * 
	 * Muestra las familias profesionales existentes.
	 *
	 * @return void
	 */

	public function verFamilias()
	{
		$lista = $this -> M_General -> seleccionar('FP_FamiliasProfesionales','idFamilia, nombre');
		foreach ($lista as $valor)
			$this -> listaFamilias[$valor['idFamilia']] = $valor['nombre'];

		$this -> load -> view('C_Familias/V_Familias');
	}
	
	/**
	 * anadirFamiliaForm
	 * 
	 * Muestra un formulario para añadir familia profesionales.
	 *
	 * @return void
	 */

	public function anadirFamiliaForm()
	{
		$lista = $this -> M_General -> seleccionar('FP_Departamentos','idDepartamento, nombre');
		$this -> departamentos = array(0 => 'Ninguno');
		foreach ($lista as $valor)
			$this -> departamentos[$valor['idDepartamento']] = $valor['nombre'];

		$this -> load -> view("C_Familias/V_AnadirFamilia");
	}
	
	/**
	 * anadirFamilia
	 *
	 * Añade una familia profesional a la base de datos.
	 * 
	 * @return void
	 */

	public function anadirFamilia()
	{
		$datos["nombre"] = $_POST["nombre"];
		if($_POST['departamento'] != 0)
			$datos["idDepartamento"] = $_POST["departamento"];

		$this -> M_General -> insertar('FP_FamiliasProfesionales',$datos);

		$this->headerLocation("families");
	}
	
	/**
	 * borrarFamilia
	 * 
	 * Borra una familia profesional de la base de datos.
	 *
	 * @param  integer $idFamilia Identificador de la familia profesional.
	 * @return void
	 */

	public function borrarFamilia($idFamilia)
	{
		$this -> M_General -> borrar('FP_FamiliasProfesionales',$idFamilia,'idFamilia');

		$this->headerLocation("families");
	}
	
	/**
	 * modificarFamiliaForm
	 * 
	 * Muestra un fomulario con los datos de una familia profesional para modificar.
	 *
	 * @param  mixed $idFamilia Identificador de la familia profesional.
	 * @return void
	 */

	public function modificarFamiliaForm($idFamilia)
	{
		$this -> datosFamilia = $this -> M_General -> seleccionar('FP_FamiliasProfesionales','idFamilia, nombre, idDepartamento','idFamilia='.$idFamilia);

		$lista = $this -> M_General -> seleccionar('FP_Departamentos','idDepartamento, nombre');
		$this -> departamentos = array(0 => 'Ninguno');
		foreach($lista as $valor)
			$this -> departamentos[$valor['idDepartamento']] = $valor['nombre'];

		$this -> load -> view("C_Familias/V_ModificarFamilia", Array('idFamilia' => $idFamilia, 'idDepartamento' => $this->datosFamilia[0]['idDepartamento']));
	}
	
	/**
	 * modificarFamilia
	 * 
	 * Actualiza los datos de una familia profesional.
	 *
	 * @param  integer $idFamilia Identificador de la familia profesional.
	 * @return void
	 */

	public function modificarFamilia($idFamilia)
	{
		$datos["nombre"] = $_POST["nombre"];
		if($_POST['departamento'] != 0)
			$datos["idDepartamento"] = $_POST["departamento"];
		else
			$datos['idDepartamento'] = null;

		$this -> M_General -> modificar('FP_FamiliasProfesionales',$datos,$idFamilia,'idFamilia');

		$this->headerLocation("families");
	}

		/*CICLOS*/
	
	/**
	 * verCiclos
	 * 
	 * Muestra los ciclos existentes.
	 *
	 * @return void
	 */

	public function verCiclos()
	{
		$lista = $this -> M_General -> seleccionar('FP_Ciclos','idCiclo, codCiclo');

		foreach ($lista as $valor)
			$this -> listaCiclos[$valor['idCiclo']] = $valor['codCiclo'];

		$this -> load -> view('C_Ciclos/V_Ciclos');
	}
	
	/**
	 * anadirCicloForm
	 * 
	 * Muestra el formulario para añadir los ciclos.
	 *
	 * @return void
	 */

	public function anadirCicloForm()
	{
		$lista = $this -> M_General -> seleccionar('FP_FamiliasProfesionales','idFamilia, nombre');

		$this -> familias = array(0 => 'Ninguna');
		foreach ($lista as $valor)
			$this -> familias[$valor['idFamilia']] = $valor['nombre'];

		$this -> load -> view("C_Ciclos/V_AnadirCiclo");
	}
	
	/**
	 * anadirCiclo
	 * 
	 * Añade los ciclos a la base de datos.
	 *
	 * @return void
	 */

	public function anadirCiclo()
	{
		$datos["nombre"] = $_POST["nombre"];
		$datos["codCiclo"] = $_POST["codCiclo"];
		if($_POST['familia'] != 0)
			$datos["idFamilia"] = $_POST["familia"];

		$this -> M_General -> insertar('FP_Ciclos',$datos);

		$this->headerLocation("cycles");
	}
	
	/**
	 * borrarCiclo
	 *
	 * Elimina el ciclo de la base de datos.
	 * 
	 * @param  integer $idCiclo Identificador del ciclo.
	 * @return void
	 */

	public function borrarCiclo($idCiclo)
	{
		$this -> M_General -> borrar('FP_Ciclos',$idCiclo,'idCiclo');

		$this->headerLocation("cycles");
	}
	
	/**
	 * modificarCicloForm
	 * 
	 * Muestra el formulario para modificar los datos de un ciclo.
	 *
	 * @param  integer $idCiclo Identificador del ciclo.
	 * @return void
	 */

	public function modificarCicloForm($idCiclo)
	{
		$this -> datosCiclo = $this -> M_General -> seleccionar('FP_Ciclos','idCiclo, codCiclo, nombre, idFamilia','idCiclo='.$idCiclo);

		$lista = $this -> M_General -> seleccionar('FP_FamiliasProfesionales','idFamilia, nombre');
		$this -> familias = array(0 => 'Ninguna');
		foreach($lista as $valor)
			$this -> familias[$valor['idFamilia']] = $valor['nombre'];

		$this -> load -> view("C_Ciclos/V_ModificarCiclo", Array('idCiclo' => $idCiclo, 'idFamilia' => $this -> datosCiclo[0]['idFamilia']));
	}
	
	/**
	 * modificarCiclo
	 *
	 * Actualiza los datos de un ciclo de la base de datos.
	 * 
	 * @param  integer $idCiclo Identificador del ciclo.
	 * @return void
	 */

	public function modificarCiclo($idCiclo)
	{
		$datos["codCiclo"] = $_POST["codCiclo"];
		$datos["nombre"] = $_POST["nombre"];
		if($_POST['familia'] != 0)
			$datos["idFamilia"] = $_POST["familia"];
		else
			$datos['idFamilia'] = null;

		$this -> M_General -> modificar('FP_Ciclos',$datos,$idCiclo,'idCiclo');

		$this->headerLocation("cycles");
	}
	
	/**
	 * cursosCiclo
	 * 
	 * Muestra los cursos que pertenece los ciclos,
	 * permitiendo quitar y añadir cursos.
	 *
	 * @param  integer $idCiclo Identificador del ciclo.
	 * @return void
	 */

	public function cursosCiclo($idCiclo)
	{
		$codCiclo = $this -> M_General -> seleccionar('FP_Ciclos','idCiclo, codCiclo, nombre, idFamilia','idCiclo='.$idCiclo);
		$codCiclo = $codCiclo[0]['codCiclo'];

		$lista = $this -> M_General -> seleccionar('Cursos cu','cu.idCurso, cu.codCurso','cc.idCiclo='.$idCiclo, ['FP_Ciclos_Cursos cc'], ['cu.idCurso = cc.idCurso']);
		foreach($lista as $valor)
			$this -> ciclosCurso[$valor['idCurso']] = $valor['codCurso'];

		$lista2 = $this -> M_General -> seleccionar('Cursos cu','cu.idCurso, cu.codCurso','cu.idCurso NOT IN (SELECT cc2.idCurso FROM FP_Ciclos_Cursos cc2 WHERE cc2.idCiclo='.$idCiclo.')');
		foreach($lista2 as $valor)
			$this -> cursosNoCiclo[$valor['idCurso']] = $valor['codCurso'];

		$this -> load -> view('C_Ciclos/V_CursosCiclos', Array('idCiclo' => $idCiclo, 'codCiclo' => $codCiclo));
	}
	
	/**
	 * quitarCursoCiclo
	 * 
	 * Quita el curso de un ciclo.
	 *
	 * @param  integer $idCiclo Identificador del ciclo.
	 * @param  integer $idCurso Identificador del curso.
	 * @return void
	 */

	public function quitarCursoCiclo($idCiclo, $idCurso)
	{
		$this -> M_General -> borrarCompuesta('FP_Ciclos_Cursos',$idCurso, $idCiclo, 'idCurso', 'idCiclo');

		$this->headerLocation("courses-cycle/".$idCiclo);
	}
	
	/**
	 * anadirCursoCiclo
	 *
	 * Añade el curso para un ciclo.
	 * 
	 * @param  integer $idCiclo Identificador del ciclo.
	 * @param  integer $idCurso Identificador del curso.
	 * @return void
	 */

	public function anadirCursoCiclo($idCiclo, $idCurso)
	{
		$this -> M_General -> insertar('FP_Ciclos_Cursos',array('idCurso' => $idCurso,'idCiclo' => $idCiclo));

		$this->headerLocation("courses-cycle/".$idCiclo);
	}

	/*SECCIONES*/
	
	/**
	 * verSecciones
	 *
	 * Muestra las secciones existentes.
	 * 
	 * @return void
	 */

	public function verSecciones($importedSections = 0)
	{
		$this -> importedSections = $importedSections;

		$lista = $this -> M_General -> seleccionar('Secciones','idSeccion, codSeccion');

		foreach ($lista as $valor)
			$this -> listaSecciones[$valor['idSeccion']] = $valor['codSeccion'];

		$this -> load-> view('C_Secciones/V_Secciones');
	}
	
	/**
	 * anadirSeccionForm
	 * 
	 * Muestra el formulario para añadir las secciones.
	 *
	 * @return void
	 */

	public function anadirSeccionForm()
	{
		$lista = $this -> M_General -> seleccionar('Cursos','idCurso, codCurso');
		$this -> cursos = array(0 => 'Ninguno');
		foreach ($lista as $valor)
			$this -> cursos[$valor['idCurso']] = $valor['codCurso'];

		$this -> load -> view("C_Secciones/V_AnadirSeccion");
	}
	
	/**
	 * anadirSeccion
	 * 
	 * Añade la seccion a la base de datos.
	 *
	 * @return void
	 */

	public function anadirSeccion()
	{
		$datos["codSeccion"] = $_POST["codSeccion"];
		$datos["nombre"] = $_POST["nombre"];
		if($_POST['curso'] != 0)
			$datos["idCurso"] = $_POST["curso"];
		if(!empty($_POST['idSeccionColegio']))
			$datos["idSeccionColegio"] = $_POST["idSeccionColegio"];

		$this -> M_General -> insertar('Secciones',$datos);

		$this->headerLocation("sections");
	}
	
	/**
	 * borrarSeccion
	 *
	 * Elimina una sección de la base de datos.
	 * 
	 * @param  integer $idSeccion Identificador de la seccion.
	 * @return void
	 */

	public function borrarSeccion($idSeccion)
	{
		$alumnos = $this -> M_General -> seleccionar('Alumnos', '*', 'idSeccion='.$idSeccion);
		if(!empty($alumnos))
			echo '
				<script>
					alert("No se puede borrar la sección porque tiene alumnos");
					location.href="'.base_url().'C_GestionEVG/verSecciones";
				</script>
			';
		else
		{
			$this -> M_General -> borrar('Secciones',$idSeccion,'idSeccion');

			$this->headerLocation("sections");
		}
	}
	
	/**
	 * modificarSeccionForm
	 *
	 * Muestra el formulario para modificar los datos de una sección.
	 * 
	 * @param  integer $idSeccion Identificador de la seccion.
	 * @return void
	 */

	public function modificarSeccionForm($idSeccion){
		$this -> datosSeccion = $this -> M_General -> seleccionar('Secciones','idSeccion, idSeccionColegio, codSeccion, nombre, idCurso','idSeccion='.$idSeccion);

		$lista = $this -> M_General -> seleccionar('Cursos','idCurso, codCurso');
		$this -> cursos = Array(0 => 'Ninguno');
		foreach($lista as $valor)
			$this -> cursos[$valor['idCurso']] = $valor['codCurso'];

		$this -> load -> view("C_Secciones/V_ModificarSeccion", Array('idSeccion' => $idSeccion, 'idCurso' => $this -> datosSeccion[0]['idCurso']));
	}
	
	/**
	 * modificarSeccion
	 *
	 * Actualiza los datos de una sección en la base de datos.
	 * 
	 * @param  integer $idSeccion Identificador de la seccion.
	 * @return void
	 */

	public function modificarSeccion($idSeccion)
	{
		$datos["codSeccion"] = $_POST["codSeccion"];
		$datos["nombre"] = $_POST["nombre"];
		if($_POST['curso'] == 0)
			$datos['idCurso'] = null;
		else
			$datos["idCurso"] = $_POST["curso"];
		if(!empty($_POST['idSeccionColegio']))
			$datos["idSeccionColegio"] = $_POST["idSeccionColegio"];
		else
			$datos["idSeccionColegio"] = null;

		$this -> M_General -> modificar('Secciones',$datos,$idSeccion,'idSeccion');

		$this->headerLocation("sections");
	}
	
	/**
	 * asignarTutorForm
	 * 
	 * Muestra el formulario para seleccionar al tutor de la sección.
	 *
	 * @param  integer $idSeccion Identificador de la seccion.
	 * @return void
	 */

	public function asignarTutorForm($idSeccion)
	{
		$codSeccion = $this -> M_General -> seleccionar('Secciones','idSeccion, idSeccionColegio, codSeccion, nombre, idCurso','idSeccion='.$idSeccion);
		$nombreSeccion = $codSeccion[0]['nombre'];
		$codSeccion = $codSeccion[0]['codSeccion'];
		$idTutorActual = $this -> M_General -> seleccionar('Secciones','idTutor',"idSeccion=".$idSeccion);
		if(isset($idTutorActual[0]['idTutor'])) 
		{
			$idTutorActual = $idTutorActual[0]['idTutor'];
			$nombreTutor = $this -> M_General -> seleccionar('Usuarios', 'correo', 'idUsuario='.$idTutorActual);
			$nombreTutor = $nombreTutor[0]['correo'];
		} 
		else 
		{
			$idTutorActual = 0;
			$nombreTutor = 'No hay tutor';
		}

		$lista = $this -> M_General -> seleccionar('Usuarios','idUsuario, correo',"idUsuario IN (SELECT idUsuario FROM Perfiles_Usuarios WHERE idPerfil=( SELECT idPerfil FROM Perfiles WHERE nombre='profesor')AND idUsuario NOT IN (SELECT idUsuario FROM Perfiles_Usuarios WHERE idPerfil=(SELECT idPerfil FROM Perfiles WHERE nombre='tutor')))",null,null,null,'correo');
		$this -> profesores = array(0 => 'Ninguno');
		foreach ($lista as $valor)
			$this -> profesores[$valor['idUsuario']] = $valor['correo'];

		$this -> load -> view('C_Secciones/V_AsignarTutor', Array('idSeccion' => $idSeccion, 'codSeccion' => $codSeccion, 'nombreSeccion' => $nombreSeccion, 'idTutorActual' => $idTutorActual, 'nombreTutor' => $nombreTutor));
	}
	
	/**
	 * anadirQuitarTutor
	 * 
	 * Permite añadir y quitar el tutor de la sección.
	 *
	 * @param  integer $idSeccion Identificador de la seccion.
	 * @param  integer $idTutorActual Identificador del usuario.
	 * @return void
	 */

	public function anadirQuitarTutor($idSeccion, $idTutorActual)
	{
		$idPerfilTutor = $this -> M_General -> seleccionar('Perfiles','idPerfil',"nombre='tutor'");
		$idPerfilTutor = $idPerfilTutor[0]['idPerfil'];
		if($_POST['tutor'] == 0)
		{
			$this -> M_General -> modificar('Secciones', array('idtutor' => null), $idSeccion, 'idSeccion');
		}
		else
		{
			$this -> M_General -> modificar('Secciones', Array('idtutor' => $_POST["tutor"]), $idSeccion, 'idSeccion');
			$this -> M_General -> insertar('Perfiles_Usuarios',Array('idUsuario' => $_POST["tutor"], 'idPerfil' => $idPerfilTutor));
		}

		$this -> M_General -> borrarCompuesta('Perfiles_Usuarios', $idTutorActual, $idPerfilTutor, 'idUsuario', 'idPerfil');

		$this->headerLocation("sections");
	}
	
	/**
	 * importarSeccionesForm
	 *
	 * Muestra el formulario para importar secciones.
	 * 
	 * @return void
	 */

	public function importarSeccionesForm()
	{
		$this -> load -> view('C_Secciones/V_ImportarSecciones');
	}

	/**
	 * importarSecciones
	 *
	 * Importa secciones desde un documento excel añadiendo los datos a la base de datos.
	 *
	 * @return void
	 * @throws PHPExcel_Reader_Exception
	 */

	public function importarSecciones()
	{
		$destino = 'uploads/'; /*Lugar donde se guardará el archivo subido al servidor*/
		$archivo_nombre = $_FILES["archivo"]["name"]; /*Guarda el nombre del archivo*/
		if(file_exists($destino.$archivo_nombre))
		{
			$contador = 0;
			while(file_exists($destino.++$contador."-".$archivo_nombre));
			$archivo_nombre = $contador."-".$archivo_nombre;
		}
		$archivo_temporal = $_FILES["archivo"]["tmp_name"]; /*Direccion temporal donde se va a guardar el archivo*/
		copy($archivo_temporal,$destino.$archivo_nombre); /*Copia el archivo de la carpeta temporal a la carpeta destino en el servidor*/

		$object = PHPExcel_IOFactory::load($_FILES["archivo"]["tmp_name"]);
		foreach($object -> getWorksheetIterator() as $worksheet) {
			$highestRow = $worksheet -> getHighestRow();
			for ($row = 2; $row <= $highestRow; $row++)
			{
				$codSeccion = $worksheet -> getCellByColumnAndRow(0, $row) -> getValue();
				if(empty($codSeccion))/*Por si la tabla tiene una longitud máxima mayor que los datos que tiene*/
					break;
				$codSeccionComprobar = $this -> M_General->seleccionar('Secciones', 'codSeccion', "codSeccion='".$codSeccion."'");
				if(empty($codSeccionComprobar[0]['codSeccion']))
				{
					$datos[$row]['codSeccion'] = $codSeccion;
					$datos[$row]['nombre'] = $worksheet -> getCellByColumnAndRow(1, $row) -> getValue();
				}
			}
		}
		/*Contador para saber cuántos secciones se han importado*/
		$c = 0;
		foreach($datos as $valor)
		{
			$this -> M_General -> insertar('Secciones', $valor);
			$c++;
		}

		set_cookie('importedSections', $c, 0);

		$this -> headerLocation("sections");
	}

	/*ALUMNOS*/
	
	/**
	 * verAlumnos
	 * 
	 * Muestra las etapas exitentes.
	 *
	 * @return void
	 */
	
	public function verAlumnos()
	{
		$lista = $this -> M_General -> seleccionar('Etapas','idEtapa, codEtapa');
		foreach ($lista as $valor)
			$this -> listaEtapas[$valor['idEtapa']] = $valor['codEtapa'];

		$this -> load -> view('C_Alumnos/V_Alumnos');
	}
	
	/**
	 * verSeccionesEtapa
	 *
	 * Muestra las secciones existetes, segun la etapa.
	 * 
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @return void
	 */

	public function verSeccionesEtapa($idEtapa)
	{
		$codEtapa = $this -> M_General -> seleccionar('Etapas','codEtapa','idEtapa='.$idEtapa);
		$codEtapa = $codEtapa[0]['codEtapa'];

		$lista = $this -> M_General -> seleccionar('Secciones s','s.idSeccion, s.codSeccion',"e.idEtapa = ".$idEtapa,['Cursos c','Etapas e'], ['s.idCurso=c.idCurso','c.idEtapa=e.idEtapa'],['join','join']);
		foreach ($lista as $valor)
			$this -> listaSecciones[$valor['idSeccion']] = $valor['codSeccion'];

		$this -> load -> view('C_Alumnos/V_AlumnosSecciones', Array('codEtapa' => $codEtapa, 'idEtapa' => $idEtapa));
	}
	
	/**
	 * verAlumnosSeccion
	 *
	 * Muestra los alumnos existentes, segun la sección.
	 * 
	 * @param  integer $idSeccion Identificador de la seccion.
	 * @param  integer $idEtapa Identificador de la etapa.
	 * @return void
	 */

	public function verAlumnosSeccion($idSeccion, $idEtapa)
	{
		$codSeccion = $this -> M_General -> seleccionar('Secciones','codSeccion','idSeccion='.$idSeccion);
		$codSeccion = $codSeccion[0]['codSeccion'];
		$this -> idSeccion = $idSeccion;

		$lista = $this -> M_General -> seleccionar('Alumnos','idAlumno, nombre','idSeccion='.$idSeccion);
		foreach ($lista as $valor)
			$this -> listaAlumnos[$valor['idAlumno']] = $valor['nombre'];

		$this -> load -> view('C_Alumnos/V_AlumnosClase', Array('codSeccion' => $codSeccion, 'idEtapa' => $idEtapa));
	}
	
	/**
	 * anadirAlumnoForm
	 *
	 * Muestra el formulario para añadir un alumno.
	 * 
	 * @return void
	 */

	public function anadirAlumnoForm()
	{
		$lista = $this -> M_General -> seleccionar('Secciones','idSeccion, codSeccion');
		foreach ($lista as $valor)
			$this -> secciones[$valor['idSeccion']] = $valor['codSeccion'];

		$this -> load -> view("C_Alumnos/V_AnadirAlumno");
	}
	
	/**
	 * anadirAlumno
	 * 
	 * Añade el alumno a la base de datos.
	 *
	 * @return void
	 */

	public function anadirAlumno()
	{
		$datos["DNI"] = $_POST["dni"];
		$datos["NIA"] = $_POST["nia"];
		$datos["nombre"] = $_POST["nombre"];
		$datos["fechaNacimiento"] = date('Y-m-d',strtotime($_POST["fecha_nacimiento"]));
		$datos["idSeccion"] = $_POST["secciones"];
		if(!empty($_POST["correo"]))
			$datos["correo"] = $_POST["correo"];
		$datos["sexo"] = $_POST["sexo"];
		if(!empty($_POST["telefono"]))
			$datos["telefono"] = $_POST["telefono"];
		if(!empty($_POST["telefono_urgencia"]))
			$datos["telefonoUrgencia"] = $_POST["telefono_urgencia"];

		$this -> M_General -> insertar('Alumnos', $datos);

		$this -> headerLocation("students");
	}
	
	/**
	 * borrarAlumno
	 *
	 * Elimina el alumno de la base de datos.
	 * 
	 * @param integer $idAlumno Identificador del alumno.
	 * @param integer $idSeccion Identificador de la sección.
	 * @param integer $idEtapa Identificador de la etapa.
	 * @return void
	 */

	public function borrarAlumno($idAlumno, $idSeccion, $idEtapa)
	{
		$this -> M_General -> borrar('Alumnos', $idAlumno, 'idAlumno');

		$this -> headerLocation("section-students/".$idSeccion."/".$idEtapa."");
	}
	
	/**
	 * modificarAlumnoForm
	 *
	 * Muestra un formulario para modificar los datos de un alumno.
	 * 
	 * @param  integer $idAlumno Identificador del alumno.
	 * @param  integer $idEtapa Identificador del etapa.
	 * @return void
	 */

	public function modificarAlumnoForm($idAlumno, $idEtapa)
	{
		$this -> datosAlumno = $this -> M_General -> seleccionar('Alumnos','*','idAlumno='.$idAlumno);

		$lista = $this -> M_General -> seleccionar('Secciones','idSeccion, codSeccion');
		foreach($lista as $valor)
			$this -> secciones[$valor['idSeccion']] = $valor['codSeccion'];

		$this -> load -> view("C_Alumnos/V_ModificarAlumno", Array('idAlumno' => $idAlumno, 'idEtapa' => $idEtapa));
	}
	
	/**
	 * modificarAlumno
	 * 
	 * Actualiza los datos del alumno en la base de datos.
	 *
	 * @param  integer $idAlumno Identificador del alumno.
	 * @param  integer $idSeccion Identificador del seccion.
	 * @param  integer $idEtapa Identificador del etapa.
	 * @return void
	 */

	public function modificarAlumno($idAlumno, $idSeccion, $idEtapa)
	{
		$datos["DNI"] = $_POST["dni"];
		$datos["NIA"] = $_POST["nia"];
		$datos["nombre"] = $_POST["nombre"];
		$datos["fechaNacimiento"] = date('Y-m-d',strtotime($_POST["fecha_nacimiento"]));
		$datos["idSeccion"] = $_POST["secciones"];
		if(!empty($_POST["correo"]))
			$datos["correo"] = $_POST["correo"];
		$datos["sexo"] = $_POST["sexo"];
		if(!empty($_POST["telefono"]))
			$datos["telefono"] = $_POST["telefono"];
		if(!empty($_POST["telefono_urgencia"]))
			$datos["telefonoUrgencia"] = $_POST["telefono_urgencia"];

		$this -> M_General -> modificar('Alumnos', $datos, $idAlumno, 'idAlumno');

		$this -> headerLocation('section-students/'.$idSeccion.'/'.$idEtapa);
	}
	
	/**
	 * importarAlumnosForm
	 * 
	 * Muestra el formulario para importar alumnos.
	 *
	 * @return void
	 */

	public function importarAlumnosForm()
	{
		$this -> secciones = $this -> M_General -> seleccionar('Secciones', '*', '1=1');
		$this -> load -> view('C_Alumnos/V_ImportarAlumnos');
	}

	/**
	 * importarAlumnos
	 *
	 * Importa alumnos desde un documento excel añadiendo los datos a la base de datos.
	 *
	 * @return void
	 * @throws PHPExcel_Reader_Exception
	 */

	public function importarAlumnos()
	{
		$destino = 'uploads/'; /*Lugar donde se guardará el archivo subido al servidor*/
		$archivo_nombre = $_FILES["archivo"]["name"]; /*Guarda el nombre del archivo*/
		if(file_exists($destino.$archivo_nombre))
		{
			$contador = 0;
			while(file_exists($destino.++$contador."-".$archivo_nombre));
			$archivo_nombre = $contador."-".$archivo_nombre;
		}
		$archivo_temporal = $_FILES["archivo"]["tmp_name"]; /*Direccion temporal donde se va a guardar el archivo*/
		copy($archivo_temporal,$destino.$archivo_nombre); /*Copia el archivo de la carpeta temporal a la carpeta destino en el servidor*/

		$object = PHPExcel_IOFactory::load($_FILES["archivo"]["tmp_name"]);
		foreach($object -> getWorksheetIterator() as $worksheet)
		{
			$highestRow = $worksheet -> getHighestRow();
			for ($row = 2; $row <= $highestRow; $row++){
				$nia = $worksheet -> getCellByColumnAndRow(1, $row) -> getValue();
				/*Por si la tabla tiene una longitud máxima mayor que los datos que tiene*/
				if(empty($nia))
					break;
				$niaComprobar = $this -> M_General -> seleccionar('Alumnos', 'nia', "nia=".$nia);
				$estado = $worksheet -> getCellByColumnAndRow(9, $row) -> getValue();
				if(empty($niaComprobar[0]['nia']) && $estado != 'Trasladada' && $estado != 'Obtiene Título' && $estado != 'Anulada')
				{
					$datos[$row]['nia'] = $nia;
					$datos[$row]['nombre'] = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$datos[$row]['dni'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					
					$value = $worksheet -> getCellByColumnAndRow(3, $row)->getValue();
					$valueTime = PHPExcel_Shared_Date::ExcelToPHP( $value );
					$datos[$row]['fechaNacimiento'] = date('Y-m-d', $valueTime);

					if($worksheet -> getCellByColumnAndRow(4, $row) -> getValue() != '')
						$datos[$row]['telefono'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();

					if($worksheet -> getCellByColumnAndRow(5, $row) -> getValue() != '')
						$datos[$row]['telefonoUrgencia'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					
					if($worksheet -> getCellByColumnAndRow(8, $row) -> getValue() == 'H')
						$datos[$row]['sexo'] = 'm';
					else
						$datos[$row]['sexo'] = 'f';

					if($worksheet -> getCellByColumnAndRow(6, $row) -> getValue() != '')
						$datos[$row]['correo'] = $worksheet -> getCellByColumnAndRow(6, $row) -> getValue();

					$idSeccion = $this -> M_General -> seleccionar('Secciones', 'idSeccion', "codSeccion='".$worksheet -> getCellByColumnAndRow(7, $row) -> getValue()."'");
					$datos[$row]['idSeccion'] = $idSeccion[0]['idSeccion'];
				}
				else if($estado=='Trasladada' || $estado=='Obtiene Título' || $estado=='Anulada')
				{
					$this -> M_General -> borrar('Alumnos', $nia, 'nia');
				}
			}
		}
		/*Recorrer el array con los datos del excel que no estén en la base de datos para insertarlos*/
		/*Contador para saber cuántos alumnos se han importado*/
		$c = 0;
		foreach($datos as $valor)
		{
			$this -> M_General -> insertar('Alumnos', $valor);
			$c++;
		}

		set_cookie('importedStudents', $c, 0);

		$this -> headerLocation('students');
	}

	/*LISTADO TUTORES*/
	
	/**
	 * listadoTutores
	 * 
	 * Genera un pdf, que contiene el listado de los tutores con su sección correspondiente.
	 *
	 * @return void
	 */

	public function listadoTutores()
	{
		include_once('application/TFPDF/tfpdf.php');

		$datos = $this -> M_General -> seleccionar('Secciones s','s.nombre, u.correo', null, ['Usuarios u'], ['s.idTutor=u.idUsuario'], ['left']);
		foreach ($datos as $valor)
			$this -> listaTutores[$valor['nombre']] = $valor['correo'];

		if(!empty($this -> listaTutores)) 
		{
			$pdf = new TFPDF('P', 'mm', 'A4'); /*Crea el objeto FPDP*/
			$pdf -> SetTitle('Listado de Tutores');
			$pdf -> SetDrawColor(0, 0, 0); /*Color de los Bordes*/
			$pdf -> SetTextColor(0, 0, 0); /*Color del Texto*/
			$pdf -> AddPage(); /*Añade una página*/
			$pdf -> AddFont('DejaVu','','DejaVuSans-Bold.ttf',true); /*Establece el estilo de letra*/
			$pdf -> SetFont('DejaVu','',7); /*Establece el estilo de letra*/
			$pdf -> Cell(0, 10, 'LISTADO DE TUTORES - ' . date('d/m/Y'), 0, 0, 'R'); /*Encabezado del PDF*/
			$pdf -> Image(base_url().'uploads/iconos/escudo-evg.png', 10, 10, 45); /*Logo EVG*/
			$pdf -> SetMargins(10, 10, 40); /*Establecer márgenes*/
			$pdf -> Ln(20); /*Salto de linea*/
			$pdf -> Cell(95, 10, 'SECCIÓN', 1, 0, 'C'); /*$pdf->Cell(ancho, alto, valor a escribir, borde, salto de linea, 'alineamiento');*/
			$pdf -> Cell(95, 10, 'TUTOR', 1, 1, 'C');

			foreach ($this -> listaTutores as $indice => $valor) 
			{
				$pdf -> Cell(95, 10, $indice, 1, 0, 'C');
				if (!empty($valor))
					$pdf -> Cell(95, 10, $valor, 1, 1, 'C');
				else
					$pdf -> Cell(95, 10, '-', 1, 1, 'C');
			}

			$pdf -> Output();
		}
		else
		{
			echo
			('
				<script>
					alert("No hay secciones creadas");
					window.close();
				</script>
			');
		}
	}

	/* NUEVO CURSO */
	
	/**
	 * nuevoCurso
	 * 
	 * Elimina a todos los alumnos de la base de datos.
	 *
	 * @return void
	 */
	public function nuevoCurso()
	{
		$this -> M_General -> borrar('Alumnos', 1, 1);
		
		$this -> headerLocation('import-students');
	}

	/*Redirecionar localizaciones*/
	
	/**
	 * headerLocation
	 *
	 * Metodo para redireccionar.
	 * 
	 * @param  string $location Ruta para redirigir a una direccion.
	 * @return void
	 */
	public function headerLocation($location)
	{
		header('Location:'.base_url().$location);
	}

}
?>
