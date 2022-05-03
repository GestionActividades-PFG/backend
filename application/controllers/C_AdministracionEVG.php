<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * C_AdministracionEVG
 * 
 * Clase que contiene todos los métodos necesario para la aplicación de administración.
 * 
 * @author Abraham Núñez Palos y Daniel Torres Galindo
 */
class C_AdministracionEVG extends CI_Controller 
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

		$data['google_login_url'] = $this -> google -> get_login_url();

        if($this->session->userdata('sess_logged_in') == 0 || !$idUsuario=$this->M_General->obtenerIdUsuario($_SESSION['email']))
		{
        	redirect('Auth');
		}
		else
		{
        	$acceso = false;

			$aplicaciones = $this -> M_General -> seleccionar('Aplicaciones a', 'distinct(a.url), a.nombre, a.icono', "idUsuario=".$idUsuario,['Aplicaciones_Perfiles ap','Perfiles_Usuarios pu'], ['a.idAplicacion= ap.idAplicacion','pu.idPerfil=ap.idPerfil'], ['join','join']);
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

		/*APLICACIONES*/
	
	/**
	 * verApps
	 * 
	 * Permite ver todos las aplicaciones existente.
	 *
	 * @return void
	 */

	public function verApps()
	{
		$lista = $this -> M_General -> seleccionar('Aplicaciones','idAplicacion, nombre');
		foreach ($lista as $valor)
			$this -> listaApps[$valor['idAplicacion']] = $valor['nombre'];

		$this -> load -> view('C_Aplicaciones/V_Aplicaciones');
	}
	
	/**
	 * anadirAppForm
	 * 
	 * Muestra el formulario para añadir la aplicación.
	 *
	 * @return void
	 */

	public function anadirAppForm()
	{
		$this -> load -> view("C_Aplicaciones/V_AnadirApp");
	}
	
	/**
	 * anadirApp
	 * 
	 * Añade la aplicación a la base de datos.
	 *
	 * @return void
	 */

	public function anadirApp()
	{
		$datos["nombre"] = $_POST["nombre"];
		$datos["descripcion"] = $_POST["descripcion"];
		$datos["url"] = $_POST["url"];

		$destino = 'uploads/iconos/';
		$archivo_nombre = $_FILES["icono"]["name"];
		if (file_exists($destino . $archivo_nombre)) 
		{
			$contador = 0;
			while (file_exists($destino . ++$contador . "-" . $archivo_nombre));
			$archivo_nombre = $contador . "-" . $archivo_nombre;
		}
		$archivo_temporal = $_FILES["icono"]["tmp_name"]; /*Direccion temporal donde se va a guardar el archivo*/
		copy($archivo_temporal, $destino . $archivo_nombre); /*Copia el archivo de la carpeta temporal a la carpeta destino en el servidor*/
		$datos['icono'] = $archivo_nombre;

		$this-> M_General-> insertar('Aplicaciones',$datos);

		$this->headerLocation("apps");
	}
	
	/**
	 * borrarApp
	 *
	 * Elimina la aplicación de la base de datos.
	 * 
	 * @param  integer $idAplicacion Identificador de la aplicación.
	 * @return void
	 */

	public function borrarApp($idAplicacion)
	{
		$iconoActual = $this -> M_General -> seleccionar('Aplicaciones', 'icono', 'idAplicacion='.$idAplicacion);
		$iconoActual = $iconoActual[0]['icono'];
		if(file_exists('uploads/iconos/'.$iconoActual))
			unlink('uploads/iconos/'.$iconoActual);

		$this -> M_General -> borrar('Aplicaciones',$idAplicacion,'idAplicacion');

		$this->headerLocation("apps");
	}
	
	/**
	 * modificarAppForm
	 *
	 * Muestra un formulario con los datos de la aplicación.
	 * 
	 * @param  integer $idAplicacion Identificador de la aplicación.
	 * @return void
	 */

	public function modificarAppForm($idAplicacion)
	{
		$this -> datosApp = $this -> M_General -> seleccionar('Aplicaciones','nombre, descripcion, url, icono','idAplicacion='.$idAplicacion);
		$this -> load -> view("C_Aplicaciones/V_ModificarApp", Array('idAplicacion' => $idAplicacion));
	}
	
	/**
	 * modificarApp
	 *
	 * Actualiza los datos de la aplicación en la base de datos.
	 * 
	 * @param  integer $idAplicacion Identificador de la aplicación
	 * @return void
	 */

	public function modificarApp($idAplicacion)
	{
		$datos["nombre"] = $_POST["nombre"];
		$datos["descripcion"] = $_POST["descripcion"];
		$datos["url"] = $_POST["url"];

		if(!empty($_FILES['icono']['name']))
		{
			$iconoActual = $this -> M_General -> seleccionar('Aplicaciones', 'icono', 'idAplicacion='.$idAplicacion);
			$iconoActual = $iconoActual[0]['icono'];
			if(file_exists('uploads/iconos/'.$iconoActual))
				unlink('uploads/iconos/'.$iconoActual);

			$destino = 'uploads/iconos/';
			$archivo_nombre = $_FILES["icono"]["name"];
			if (file_exists($destino . $archivo_nombre)) 
			{
				$contador = 0;
				while (file_exists($destino . ++$contador . "-" . $archivo_nombre));
				$archivo_nombre = $contador . "-" . $archivo_nombre;
			}
			$archivo_temporal = $_FILES["icono"]["tmp_name"]; /*Direccion temporal donde se va a guardar el archivo*/
			copy($archivo_temporal, $destino . $archivo_nombre); /*Copia el archivo de la carpeta temporal a la carpeta destino en el servidor*/
			$datos['icono'] = $archivo_nombre;
		}

		$this -> M_General -> modificar('Aplicaciones',$datos,$idAplicacion,'idAplicacion');

		$this->headerLocation("apps");
	}
	
	/**
	 * perfilesAplicacion
	 *
	 * Muestra los perfiles que están asociado a la aplicación, 
	 * ademas de todos los perfiles para poder añadir o quitar perfiles.
	 * 
	 * @param  integer $idAplicacion Identificador de la aplicación.
	 * @return void
	 */

	public function perfilesAplicacion($idAplicacion)
	{
		$nombreApp = $this -> M_General -> seleccionar('Aplicaciones','nombre, descripcion, url, icono','idAplicacion='.$idAplicacion);
		$nombreApp = $nombreApp[0]['nombre'];

		$lista = $this -> M_General -> seleccionar('Perfiles p','p.idPerfil, p.nombre','ap.idAplicacion='.$idAplicacion, ['Aplicaciones_Perfiles ap'],['p.idPerfil = ap.idPerfil']);
		foreach($lista as $valor)
			$this -> perfilesAplicacion[$valor['idPerfil']] = $valor['nombre'];

		$lista2 = $this -> M_General -> seleccionar('Perfiles p','p.idPerfil, p.nombre','p.idPerfil NOT IN (SELECT ap2.idPerfil FROM Aplicaciones_Perfiles ap2 WHERE ap2.idAplicacion='.$idAplicacion.')');
		foreach($lista2 as $valor)
			$this -> perfilesNoAplicacion[$valor['idPerfil']] = $valor['nombre'];

		$this -> load -> view('C_Aplicaciones/V_PerfilesAplicaciones', Array('idAplicacion'=>$idAplicacion, 'nombreApp'=>$nombreApp));
	}
	
	/**
	 * quitarPerfilAplicacion
	 *
	 * Permite quitar perfiles a una aplicación.
	 * 
	 * @param  integer $idAplicacion Identificador de la aplicación.
	 * @param  integer $idPerfil Identificador del perfil.
	 * @return void
	 */

	public function quitarPerfilAplicacion($idAplicacion, $idPerfil)
	{
		$this -> M_General -> borrarCompuesta('Aplicaciones_Perfiles',$idPerfil, $idAplicacion, 'idPerfil', 'idAplicacion');

		$this->headerLocation("profiles-app/".$idAplicacion);
	}
	
	/**
	 * anadirPerfilAplicacion
	 *
	 * Permite añadir perfiles a una aplicación.
	 * 
	 * @param  integer $idAplicacion Identificador de la aplicación.
	 * @param  integer $idPerfil  Identificador del perfil.
	 * @return void
	 */

	public function anadirPerfilAplicacion($idAplicacion, $idPerfil)
	{
		$this -> M_General -> insertar('Aplicaciones_Perfiles',array('idPerfil'=>$idPerfil,'idAplicacion'=>$idAplicacion));

		$this->headerLocation("profiles-app/".$idAplicacion);
	}

		/*PERFILES*/
	
	/**
	 * verPerfiles
	 * 
	 * Muestra los perfiles.
	 *
	 * @return void
	 */

	public function verPerfiles()
	{
		$lista = $this -> M_General -> seleccionar('Perfiles','idPerfil, nombre');
		foreach ($lista as $valor)
			$this -> listaPerfiles[$valor['idPerfil']] = $valor['nombre'];

		$this -> load -> view('C_Perfiles/V_Perfiles');
	}
	
	/**
	 * anadirPerfilForm
	 * 
	 * Muestra el formulario para añadir un perfil.
	 *
	 * @return void
	 */

	public function anadirPerfilForm()
	{
		$this -> load -> view("C_Perfiles/V_AnadirPerfil");
	}
	
	/**
	 * anadirPerfil
	 * 
	 * Añade el perfil a la base de datos.
	 *
	 * @return void
	 */

	public function anadirPerfil()
	{
		$datos["nombre"] = $_POST["nombre"];
		$datos["descripcion"] = $_POST["descripcion"];

		$this -> M_General -> insertar('Perfiles',$datos);

		$this->headerLocation("profiles");
	}
	
	/**
	 * borrarPerfil
	 * 
	 * Elimina el perfil de la base de datos.
	 *
	 * @param  integer $idPerfil Identificador del perfil.
	 * @return void
	 */
	
	public function borrarPerfil($idPerfil)
	{
		$this -> M_General -> borrar('Perfiles',$idPerfil,'idPerfil');

		$this->headerLocation("profiles");
	}
	
	/**
	 * modificarPerfilForm
	 * 
	 * Muestra el formulario con los datos del perfil a modificar.
	 *
	 * @param  integer $idPerfil Identificador del perfil.
	 * @return void
	 */

	public function modificarPerfilForm($idPerfil)
	{
		$this -> datosPerfil = $this -> M_General -> seleccionar('Perfiles','nombre, descripcion','idPerfil='.$idPerfil);
		$this -> load -> view("C_Perfiles/V_ModificarPerfil", Array('idPerfil' => $idPerfil));
	}
	
	/**
	 * modificarPerfil
	 *
	 * Actualiza los datos del perfil en la base de datos.
	 * 
	 * @param  integer $idPerfil Identificador del perfil.
	 * @return void
	 */

	public function modificarPerfil($idPerfil)
	{
		$datos["nombre"] = $_POST["nombre"];
		$datos["descripcion"] = $_POST["descripcion"];

		$this -> M_General -> modificar('Perfiles',$datos,$idPerfil,'idPerfil');

		$this->headerLocation("profiles");
	}
	
	/**
	 * usuariosPerfil
	 *
	 * Muestra los usuario que tiene el perfil,
	 * dando la opción de añadir o quitar a los usuarios.
	 * 
	 * @param  integer $idPerfil Identificador del perfil.
	 * @return void
	 */

	public function usuariosPerfil($idPerfil)
	{
		$nombre = $this -> M_General -> seleccionar('Perfiles','nombre, descripcion','idPerfil='.$idPerfil);
		$nombre = $nombre[0]['nombre'];

		$lista = $this -> M_General -> seleccionar('Usuarios u','u.idUsuario, u.correo','pu.idPerfil='.$idPerfil,['Perfiles_Usuarios pu'],['pu.idUsuario = u.idUsuario']);
		foreach($lista as $valor)
			$this -> usuariosPerfil[$valor['idUsuario']] = $valor['correo'];

		$this -> load -> view('C_Perfiles/V_UsuariosPerfil', Array('idPerfil' => $idPerfil, 'nombre' => $nombre));
	}
	
	/**
	 * quitarUsuarioPerfil
	 *
	 * Quita usuarios del perfil
	 * 
	 * @param  integer $idPerfil Identificador del perfil.
	 * @param  integer $idUsuario Identificador del usuario.
	 * @return void
	 */

	public function quitarUsuarioPerfil($idPerfil,$idUsuario)
	{
		$this -> M_General -> borrarCompuesta('Perfiles_Usuarios',$idPerfil, $idUsuario, 'idPerfil', 'idUsuario');

		$this->headerLocation("users-profile/".$idPerfil);
	}
	
	/**
	 * anadirUsuarioPerfil
	 *
	 * Añade usuario al perfil.
	 * 
	 * @param  int $idPerfil Identificador del perfil
	 * @return void
	 */

	public function anadirUsuarioPerfil($idPerfil)
	{
		if($idUsuario = $this -> M_General -> obtenerIdUsuario($_POST['correo']))
		{
			$perfil = $this -> M_General -> seleccionar('Perfiles_Usuarios', '*', 'idUsuario='.$idUsuario.' and idPerfil='.$idPerfil);
			if(empty($perfil[0]))
				$this -> M_General -> insertar('Perfiles_Usuarios',array('idPerfil'=>$idPerfil,'idUsuario'=>$idUsuario));
		}

		$this->headerLocation("users-profile/".$idPerfil);
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
