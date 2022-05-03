<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * C_Instalacion
 *  
 * Clase que permite realizar la instalación de la aplicacion.
 * 
 * @author Abraham Núñez Palos y Daniel Torres Galindo
 */
class C_Instalacion extends CI_Controller
{
	
	/**
	 * __construct
	 * 
	 * Carga los metodos.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this -> load -> helper('form');
		$this -> load -> library('form_validation');
		$this -> load -> helper('url');
		$this -> load -> model('M_General');
		$this -> load -> model('M_Instalacion');
	}
	
	/**
	 * index
	 * 
	 * Añade los perfiles basico.
	 *
	 * @return void
	 */
	public function index()
	{
		$this -> load -> view('Instalacion/V_Cargando');
		$this -> M_Instalacion -> tablas();
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Administrador','descripcion' => 'Administrador'));
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Gestor','descripcion' => 'Gestor'));
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Tutor','descripcion' => 'Tutor de una Clase'));
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Profesor','descripcion' => 'Profesor'));
		header("Refresh: 1; url = ".base_url()."add-admin");
	}	

	/**
	 * formularioAdmin
	 * 
	 * Muestra el formulario del administrador.
	 *
	 * @return void
	 */
	public function formularioAdmin(){
		sleep(2);
		$this -> load -> view('Instalacion/V_Admin');
	}

	
	/**
	 * anadirAdmin
	 * 
	 * Permite registrar al administrador de la aplicación.
	 *
	 * @return void
	 */
	public function anadirAdmin()
	{
		$datos = array();
		$datos["nombre"] = $this->input->post('nombre');
		$datos["correo"] = $_GET["correo"];


		$idUsuario = $this -> M_General -> insertar('Usuarios', $datos);

		$idPerfilA = $this -> M_General -> seleccionar('Perfiles', 'idPerfil', "nombre='Administrador'");
		$idPerfilG = $this -> M_General -> seleccionar('Perfiles', 'idPerfil', "nombre='Gestor'");

		$this -> M_General -> insertar('Perfiles_Usuarios', Array('idPerfil' => $idPerfilA[0]['idPerfil'], 'idUsuario' => $idUsuario));
		$this -> M_General -> insertar('Perfiles_Usuarios', Array('idPerfil' => $idPerfilG[0]['idPerfil'], 'idUsuario' => $idUsuario));
		$idAplicacionA = $this -> M_General -> insertar('Aplicaciones', Array('nombre' => 'AdministracionEVG', 'descripcion' => 'Aplicación para administrar aplicaciones y perfiles', 'url' => base_url().'app/1', 'icono' => 'administracion.jpg'));
		$idAplicacionG = $this -> M_General -> insertar('Aplicaciones', Array('nombre'=>'GestionEVG','descripcion' => 'Aplicación para gestionar datos', 'url' => base_url().'app/2', 'icono' => 'gestion.jpg'));
		$this -> M_General -> insertar('Aplicaciones_Perfiles', Array('idPerfil' => $idPerfilA[0]['idPerfil'], 'idAplicacion' => $idAplicacionA));
		$this -> M_General -> insertar('Aplicaciones_Perfiles', Array('idPerfil' => $idPerfilG[0]['idPerfil'], 'idAplicacion' => $idAplicacionG));

		header("Location:".base_url()."C_GestionEVG");
	}

}
?>
