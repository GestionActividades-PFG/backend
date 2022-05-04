<?php


defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestController.php';


/**
 * C_GestionActividades
 * 
 * Clase que contiene todos los métodos necesario para la aplicación de Gestión de Actividades.
 * 
 * @author Sergio Matamoros Delgado
 */
class C_GestionActividades extends RestController 
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
		}
		else
		{
        	$acceso = false;

			$aplicaciones = $this -> M_General -> seleccionar('Aplicaciones a', 'distinct(a.url), a.nombre, a.icono', "idUsuario=".$idUsuario,['Aplicaciones_Perfiles ap','Perfiles_Usuarios pu'], ['a.idAplicacion= ap.idAplicacion','pu.idPerfil=ap.idPerfil'], ['join','join']);
			foreach($aplicaciones as $valor)
				if( $valor['nombre'] == 'GestionEVG' || $valor['nombre'] == 'AdministracionEVG' )
					$acceso = true;

            //Acceso fallido
			if(!$acceso)
				redirect('Grid');
		}
	}

    private $momentos = null;
	

    /**
     * Método que obtiene todos los momentos disponibles.
     */
    public function getMomentos_get() {

		//$numeroFilas = $this -> M_General -> seleccionar($_POST['tabla'],$_POST['campo'],$_POST['campo']."='".$_POST['valor']."'");
		
		$this->momentos = [
			array(
				"id" => "1",
				"nombre" => "Navidad"
			),
			array(
				"id" => "2",
				"nombre" => "Momento 1"
			),
			array(
				"id" => "3",
				"nombre" => "Momento 2"
			)
		];

		$this->response($this->momentos, 200);
    }

    /**
     * Método que añade un nuevo momento
     */
    public function addMomento_post() {

		//$numeroFilas = $this -> M_General -> seleccionar($_POST['tabla'],$_POST['campo'],$_POST['campo']."='".$_POST['valor']."'");
		
		$this->momentos = [
			array(
				"id" => "1",
				"nombre" => "Navidad"
			),
			array(
				"id" => "2",
				"nombre" => "Momento 1"
			),
			array(
				"id" => "3",
				"nombre" => "Momento 2"
			)
		];

		$this->response($this->momentos, 200);
    }

    /**
     * Método que actualiza un momento
     */
    public function updateMomento_put() {

		//$numeroFilas = $this -> M_General -> seleccionar($_POST['tabla'],$_POST['campo'],$_POST['campo']."='".$_POST['valor']."'");
       
        $id = $this -> put('id');
        $datos[] = $this -> put("datos");

        if($id != null) {

            //Consulta SQL update
            $this -> M_General -> modificar("Momentos", $datos, $id, "id");

        } else 
		    $this->response($this->momentos, 402);

		

		$this->response($this->momentos, 200);
    }

    /**
     * Método que elimina un momento
     */
    public function removeMomento_delete() {

		//$numeroFilas = $this -> M_General -> seleccionar($_POST['tabla'],$_POST['campo'],$_POST['campo']."='".$_POST['valor']."'");
		
        //Eliminar por ID

		$this->response($this->momentos, 200);
    }
	
	//Ejemplo HTTP...
	/*public function users_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            // Check if the users data store contains users
            if ( $users )
            {
                // Set the response and exit
                $this->response( $users, 200 );
            }
            else
            {
                // Set the response and exit
                $this->response( [
                    'status' => false,
                    'message' => 'No users were found'
                ], 404 );
            }
        }
        else
        {
            if ( array_key_exists( $id, $users ) )
            {
                $this->response( $users[$id], 200 );
            }
            else
            {
                $this->response( [
                    'status' => false,
                    'message' => 'No such user found'
                ], 404 );
            }
        }
    }*/
}