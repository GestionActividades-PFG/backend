<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth
 * 
 * Clase que permite hacer login en la aplicación.
 * 
 * @author Abraham Núñez Palos y Daniel Torres Galindo
 */

class Auth extends CI_Controller
{
	/**
	 * __construct
	 * 
	 * Carga los controladores de la libreria de google.
	 *
	 * @return void
	 */
	
	function __construct()
	{
        parent::__construct();
        $this -> load -> library('google');
        $this -> load -> model('M_General');
    }
	
	/**
	 * index
	 *
	 * Función que llama al login de google .
	 * 
	 * @return void
	 */
	
	public function index()
	{
		$data['google_login_url'] = $this -> google -> get_login_url();
		$this -> load -> view('home', $data);
	}
	
	/**
	 * oauth2callback
	 * 
	 * Función que valida la cuenta y carga los datos de tu cuenta en un array.
	 *
	 * @return void
	 */

	public function oauth2callback()
	{
		$google_data = $this -> google -> validate();
		$session_data = array
		(
			'name' => $google_data['name'],
			'email' => $google_data['email'],
			'source' => 'google',
			'profile_pic' => $google_data['profile_pic'],
			'link' => $google_data['link'],
			'sess_logged_in' => 1
		);
		$this -> session -> set_userdata($session_data);
		redirect(base_url()."main");
	}
	
	/**
	 * logout
	 * 
	 * Función que permite cerrar sesión.
	 *
	 * @return void
	 */

	public function logout()
	{
		session_destroy();
		unset($_SESSION['access_token']);
		$session_data = array
		(
			'sess_logged_in' => 0
		);
		$this -> session -> set_userdata($session_data);
		redirect(base_url().'Auth');
	}
}
