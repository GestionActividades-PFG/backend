<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Grid
 * 
 * Clase para gestionar las aplicaciones.
 * 
 * @author Abraham Núñez Palos y Daniel Torres Galindo
 */
class Grid extends CI_Controller
{	
	/**
	 * __construct
	 * 
	 * Carga las librerias y los modelos ademas de comprobar si tienes iniciado sesión.
	 *
	 * @return void
	 */
	
	function __construct()
	{
		parent::__construct();

		$this -> load -> model('M_General');
		$this -> load -> library('google');

		if($this -> session -> userdata('sess_logged_in') == 0 || !$idUsuario = $this -> M_General -> obtenerIdUsuario($_SESSION['email']))
			redirect('Auth');

		$this -> load -> model('M_General');
	}
	
	/**
	 * index
	 * 
	 * Obtiene las aplicaciones del usuario y lo carga en las vista V_Grid.
	 *
	 * @return void
	 */

	public function index()
	{
		$idUsuario = $this -> M_General -> obtenerIdUsuario($_SESSION['email']);
		$this -> aplicaciones = $this -> M_General -> seleccionar('Aplicaciones a','distinct(a.url), a.nombre, a.icono',"idUsuario=".$idUsuario,['Aplicaciones_Perfiles ap','Perfiles_Usuarios pu'],['a.idAplicacion= ap.idAplicacion','pu.idPerfil=ap.idPerfil'], ['join','join']);
		$this -> load -> view('V_Grid');
	}

	public function vistaGeneral($app)
	{
		$this -> app = $app;
		$this -> load -> view('Plantilla/mainView');
	}
}
