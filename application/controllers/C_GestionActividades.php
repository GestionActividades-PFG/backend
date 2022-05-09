<?php


defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestServer/RestController.php';


/**
 * C_GestionActividades
 * 
 * Clase que contiene todos los métodos necesario para la aplicación de Gestión de Actividades.
 * 
 * Esta clase no contiene las vistas de CodeIgniter, se accede a los métodos mediante AJAX.
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

        if($this->session->userdata('sess_logged_in') == 0 || !$this->M_General->obtenerIdUsuario($_SESSION['email']))
		{
            //Decir al cliente que tiene que reedirigir al login
            //$this->response(false, 200, false);
		}
        $this->response(true, 200, true);
	}

    /**
     * Método que hace re-check
     */
    public function index_get() {}


	/**
	 * logout
	 * 
	 * Función que permite cerrar sesión.
     * 
     * (CAMBIAR)
	 *
	 * @return void
	 */
	public function logout_get()
	{
		session_destroy();
		unset($_SESSION['access_token']);
		$session_data = array
		(
			'sess_logged_in' => 0
		);
		$this -> session -> set_userdata($session_data);

        $this->response($session_data, 200, false);
	}


	
    /**
     * ================================
     *          MOMENTOS
     * ================================
    */

    /**
     * Método que obtiene todos los momentos disponibles.
     */
    public function getMomentos_get() {

        $campo = ["idMomento", "nombre"];

		$momentos = $this -> M_General -> seleccionar("ACT_Momentos", $campo);

		$this->response($momentos, 200);
    }

    /**
     * Método que añade un nuevo momento
     */
    public function addMomento_post() {

        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);


        //Insertamos los datos pasados por el cliente...
        $this -> M_General -> insertar('ACT_Momentos', 
            array(
                'nombre' => $data->nombre,
                'fechaInicio_Inscripcion' => $data->fechaInicio_Inscripcion,
                'fechaFin_Inscripcion' => $data->fechaFin_Inscripcion
            ));
		
        
		$this->response(null, 200);
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

        $id = $this -> get("id");

		
        $this -> M_General -> borrar("ACT_Momentos", array("idMomento", $id));
        //Eliminar por ID

		$this->response(null, 200);
    }

    /**
     * ================================
     *          ACTIVIDADES
     * ================================
    */

     /**
     * Método que obtiene todas las actividades disponibles.
     */
    public function getActividades_get() {

        $campo = ["idActividad", "nombre"];

        //Params del get
        $idMomento = $this->input->get("idMomento");

        $condicionMomento = null;

        if(isset($idMomento)) $condicionMomento = "ACT_Actividades.idMomento = $idMomento";

        //Consultas a B.D
        $nombreMomento = $this->M_General->seleccionar(
            "ACT_Actividades", //Tabla
            "idActividad, ACT_Momentos.nombre", //Campos
            $condicionMomento, //Condición
            ["ACT_Momentos"], //Tabla relación
            ["ACT_Actividades.idMomento = ACT_Momentos.idMomento"], //Relación
            ['left'], //Tipo relación
            "ACT_Momentos.nombre" //Agrupar
        );
            
        $actividades = array(
            "idMomento" => $idMomento,
            "nombre" => $nombreMomento[0]["nombre"],
            "actividades" => $this -> M_General -> seleccionar("ACT_Actividades", $campo, array("idMomento" => $idMomento))
        );


        $this->response($actividades, 200);
            
    }

    /**
     * Método que añade una nueva actividad
     */
    public function addActividades_post() {

        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);


        //Insertamos los datos pasados por el cliente...
        $this -> M_General -> insertar('ACT_Actividades', 
            array(
                'nombre' => $data->nombre,
                "idMomento" => $data->idMomento,
                "idResponsable" => $data->idResponsable,
                "tipo_Participacion" => $data->tipo_Participacion
            ));
		
        
		$this->response(null, 200);
    }

    /**
     * Método que actualiza una actividad
     */
    public function updateActividad_put() {

       
        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);
        
        $id = $data->id;
        $datos = array(
            'nombre' => $data->nombre,
            "idMomento" => $data->idMomento,
            "idResponsable" => $data->idResponsable,
            "tipo_Participacion" => $data->tipo_Participacion
        );

        if($id != null) {

            //Consulta SQL update
            $this -> M_General -> modificar("ACT_Actividades", $datos, $id, "idActividad");

        } else 
		    $this->response($this->momentos, 402);

		

		$this->response($this->momentos, 200);
    }

    /**
     * Método que elimina un momento
     */
    public function removeActividad_delete() {

		
        $this -> M_General -> borrar("ACT_Actividades", array("idMomento", ));
        //Eliminar por ID

		$this->response(null, 200);
    }

    /**
     * Obtiene toda la información relativa a una actividad
     * (Nombre, sexo, individual, momento, responsable, etcétera)
     */
    public function getModificacionActividad_get() {

        $idMomento = $this->input->get("idMomento");
        $idActividad = $this->input->get("idActividad");

        $actividadInfo = $this->M_General->seleccionar(
            "ACT_Actividades actividades", //Tabla

            "actividades.nombre, actividades.sexo, actividades.esIndividual, actividades.numMaxParticipantes,
                actividades.fechaInicio_Inscripcion, actividades.fechaFin_Inscripcion,
                actividades.material, actividades.descripcion, actividades.tipo_Participacion,
                usuarios.nombre AS 'nombreResponsable', ACT_Momentos.nombre AS 'nombreMomento'
            ", //Campos

            "actividades.idActividad = $idActividad", //Condición
            ["ACT_Momentos", "usuarios"], //Tabla relación
            ["actividades.idMomento = ACT_Momentos.idMomento", "actividades.idResponsable = usuarios.idUsuario"], //Relación
            ['left', "left"], //Tipo relación
            "ACT_Momentos.nombre" //Agrupar
        );   
        

		$this->response($actividadInfo, 200);
    }


    public function setInscripcion_put() {


        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);

        $datos = array(
            'fechaInicio_Inscripcion' => $data->fechaInicio_Inscripcion,
            "fechaFin_Inscripcion" => $data->fechaFin_Inscripcion
        );

        $this -> M_General -> modificar("ACT_Actividades", $datos, $data->idActividad, "idActividad");


		$this->response(null, 200);
    }

	
	//Ejemplo de funcionamiento de la Rest API HTTP...
	/*public function users_get()
    {
        // Usuarios (de una bd por ejemplo)
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get( 'id' );

        if ( $id === null )
        {
            // Comprobamos si existen usuarios
            if ( $users )
            {
                // Mandamos la respuesta
                $this->response( $users, 200 );
            }
            else
            {
                // Mandamos la respuesta y salimos
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