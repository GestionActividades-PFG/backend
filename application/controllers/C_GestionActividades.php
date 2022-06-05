<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

require APPPATH . 'libraries/RestServer/RestController.php';
require_once APPPATH . '/libraries/JWT/JWTGenerator.php';



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
        $this -> load -> helper('cookie');

		$data['google_login_url'] = $this -> google -> get_login_url();

        if($this->session->userdata('sess_logged_in') == 0 || !$this->M_General->obtenerIdUsuario($_SESSION['email']))
		{
            //Decir al cliente que tiene que reedirigir al login
            //$this->response(false, 200, false);
		}
         
        $this->response(true, 200, true);
    }

    

    /**
     * Método que hace re-check y envía el token JWT.
     */
    public function index_get() {
        //Check Role (comprobamos si tienes permisos para acceder al recurso)
        $this -> jwt = new CreatorJwt();

        // session_start();
        $email = $this->session->userdata("email");
        $idUsuario = 19;//$this -> M_General -> obtenerIdUsuario($email);

        //Obtenemos el rango del usuario...
        $role = $this->M_General->seleccionar(
            "Perfiles_Usuarios pu", //Tabla
            "nombre", //Campos
            "pu.idUsuario = $idUsuario", //Condición
            ["Perfiles p"], //Tabla relación
            ["pu.idPerfil = p.idPerfil"], //Relación
            ['left'] //Tipo relación
        );

        //Obtenemos si es tutor de algún curso...
        $tutorCurso = $this->M_General->seleccionar(
            "Usuarios u", //Tabla
            "codSeccion", //Campos
            "u.idUsuario = $idUsuario", //Condición
            ["Secciones c"], //Tabla relación
            ["u.idUsuario = c.idTutor"], //Relación
            ['left'] //Tipo relación
        );
        
        //JWT, controla la expiration y el iat
        $tokenData['id'] = $idUsuario;
        $tokenData['role'] = $role;
        $tokenData['iat'] = time(); //Issued At
        $tokenData['exp'] = $tokenData["iat"] + 60 * 60 * 1;
        $tokenData["tutorCurso"] = $tutorCurso;
        $tokenData['timeStamp'] = Date('Y-m-d h:i:s');

        $jwt = $this->jwt->GenerateToken($tokenData);

        $this->response($jwt, 200);
    }

   
    /**
     * Método para DEBUG
     */
    public function token_get() {
        $token = $this->input->get("token");
        $this->jwt = new CreatorJwt();

        $decode = $this->jwt->DecodeToken($token);

        $this->response($decode, 200);

    }


    /*************Use for token then fetch the data**************/
         
    public function GetTokenData()
    {
        $received_Token = $this->input->request_headers('Authorization');
        try
        {
            $jwtData = $this->objOfJwt->DecodeToken($received_Token['Token']);
            echo json_encode($jwtData);
        }
        catch (Exception $e)
        {
            http_response_code('401');
            echo json_encode(array( "status" => false, "message" => $e->getMessage()));
            exit;
        }
    }

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

        $idMomento = $this->input->get("idMomento");


        $condicionMomento = null;
        if(isset($idMomento)) $condicionMomento = "idMomento = $idMomento";

        $campo = ["idMomento AS 'id'", "nombre", "fechaInicio_Inscripcion", "fechaFin_Inscripcion"];

		$momentos = $this -> M_General -> seleccionar("ACT_Momentos", $campo, $condicionMomento);

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
     * @param id INT id del momento a actualizar.
     */
    public function updateMomento_put() {

        $id = $this -> input -> get('idMomento');

        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);

        $datos = array(
            'nombre' => $data->nombre,
            //"ultimoCelebrado" => $data->ultimoCelebrado,
            "fechaInicio_Inscripcion" => $data->fechaInicio,
            "fechaFin_Inscripcion" => $data->fechaFin
        );


        if(isset($id)) $this -> M_General -> modificar("ACT_Momentos", $datos, $id, "idMomento");
        else $this->response($this->momentos, 401);

		

		$this->response($datos, 200);
    }

    /**
     * Método que elimina un momento
     */
    public function removeMomento_delete() {

        $id = $this-> input -> get("id");

        //Eliminar por ID
        $this -> M_General -> borrar("ACT_Momentos", $id, "idMomento");

		$this->response($id, 200);
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
            "id" => $idMomento,
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
				'sexo' => $data->sexo,
				'esIndividual' => $data->esIndividual,
                "idMomento" => $data->idMomento,
                "idResponsable" => $data->idResponsable,
                "tipo_Participacion" => $data->tipo_Participacion,
                "descripcion" => $data->descripcion,
                "material" => $data->material,
                "numMaxParticipantes" => $data->numMaxParticipantes,
                "fechaInicio_Actividad" => $data->fechaInicio_Actividad,
                "fechaFin_Actividad" => $data->fechaFin_Actividad
            ));
		
        
		$this->response(null, 200);
    }
	
    /**
     * Método que actualiza una actividad
     */
    public function updateActividad_put() {

		$id = $this -> input -> get('idActividad');
       
        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);

        $datos = array(
			'nombre' => $data[0]->nombre,
			'sexo' => $data[0]->sexo,
			'esIndividual' => $data[0]->esIndividual,
			"idResponsable" => $data[0]->idResponsable,
			"tipo_Participacion" => $data[0]->tipo_Participacion,
			"descripcion" => $data[0]->descripcion,
			"material" => $data[0]->material,
			"numMaxParticipantes" => $data[0]->numMaxParticipantes,
			"fechaInicio_Actividad" => $data[0]->fechaInicio_Actividad,
			"fechaFin_Actividad" => $data[0]->fechaFin_Actividad
        );

        if(isset($id)) $this -> M_General -> modificar("ACT_Actividades", $datos, $id, "idActividad");
        else $this->response($this->actividad, 401);

		

		$this->response($datos, 200);
    }
	
    /**
     * Método que elimina un momento
     */
    public function removeActividad_delete() {
		
		$id = $this-> input -> get("id");
		
        $this -> M_General -> borrar("ACT_Actividades", $id,"idActividad");
        //Eliminar por ID

		$this->response($id, 200);
    }
	
    /**
     * Obtiene toda la información relativa a una actividad
     * (Nombre, sexo, individual, momento, responsable, etcétera)
     * 
     * @param idActividad Number muestra la información de esa actividad y su responsable.
     * Si se omite este parámetro mostrará unicamente todos los responsables.
     */
    public function getModificacionActividad_get() {

        $idMomento = $this->input->get("idMomento");
        $idActividad = $this->input->get("idActividad");

        
        $actividadInfo = array (
            
            "Actividad" => $this->M_General->seleccionar(
                "ACT_Actividades actividades", //Tabla

                "actividades.nombre, actividades.sexo, actividades.esIndividual, actividades.numMaxParticipantes,
                    actividades.fechaInicio_Actividad, actividades.fechaFin_Actividad,
                    actividades.material, actividades.descripcion, actividades.tipo_Participacion,
                    Usuarios.nombre AS 'nombreResponsable', ACT_Momentos.nombre AS 'nombreMomento'
                ", //Campos

                "actividades.idActividad = $idActividad", //Condición
                ["ACT_Momentos", "Usuarios"], //Tabla relación
                ["actividades.idMomento = ACT_Momentos.idMomento", "actividades.idResponsable = Usuarios.idUsuario"], //Relación
                ['left', "left"], //Tipo relación
                "ACT_Momentos.nombre" //Agrupar
            ),
            "responsables" => $this->M_General->seleccionar("Usuarios", "idUsuario, nombre")
        );   
        

		$this->response($actividadInfo, 200);
    }
	
	/**
     * ================================
     *          ACTIVIDAD
     * ================================
    */

     /**
     * Método que obtiene información de actividad.
     */
    public function getActividad_get() {

        //Params del get
        $idActividad = $this->input->get("idActividad");

        $condicionActividad = null;

        if(isset($idActividad)) $condicionActividad = "ACT_Actividades.idActividad = $idActividad";

        //Consultas a B.D
        $actividad = $this->M_General->seleccionar(
            "ACT_Actividades", //Tabla
            "idActividad,sexo,ACT_Actividades.nombre,esIndividual,numMaxParticipantes,fechaInicio_Actividad,fechaFin_Actividad,material,descripcion,idResponsable,Usuarios.nombre AS nombreUsuario,tipo_Participacion", //Campos
			$condicionActividad, //Condición
			["Usuarios"], //Tabla relación
			["ACT_Actividades.idResponsable = Usuarios.idUsuario"], //Relación
			['left'] //Tipo relación
        );
            

        $this->response($actividad, 200);
    }
    
    /**
     * ===========================================
     *          INSCRIPCIONES INDIVIDUALES
     * ===========================================
    */
	
	/**
     * Método que obtiene todos los Alumnos corespondientes al tutor para añadirlos al Select.
     */
    public function getAlumnosTutor_get() {

        //Params del get
        $codSeccion = $this->input->get("codSeccion");

        $condicionSeccion = null;

        if(isset($codSeccion)) $condicionSeccion = "Secciones.codSeccion = $codSeccion";

        //Consultas a B.D
        $nombresAlumnos = $this->M_General->seleccionar(
            "Alumnos", //Tabla
            "Alumnos.idAlumno,Alumnos.nombre", //Campos
			$condicionSeccion, //Condición
			["Secciones"], //Tabla relación
			["Alumnos.idSeccion = Secciones.idSeccion"], //Relación
			['left'] //Tipo relación
			
        );
		         
		$this->response($nombresAlumnos, 200);      
            
    }
		
	/**
     * Método que obtiene todos los Alumnos corespondientes al coordinador para añadirlos al Select.
     */
    public function getAlumnosCoordinador_get() {

        //Params del get
        $idEtapa = $this->input->get(idEtapa);

        $condicionEtapa = null;

        if(isset($idEtapa)) $condicionEtapa = "Cursos.idEtapa = $idEtapa";

        //Consultas a B.D
        $nombresAlumnos = $this->M_General->seleccionar(
            "Alumnos", //Tabla
            "Alumnos.idAlumno,Alumnos.nombre", //Campos
			$condicionEtapa, //Condición
			["Secciones","Cursos"], //Tabla relación
			["Alumnos.idSeccion = Secciones.idSeccion","Cursos.idCurso=Secciones.idCurso"], //Relación
			['left','left'] //Tipo relación
			
        );
		         
		$this->response($nombresAlumnos, 200);      
		
            
    }
	
	/**
     * Inscribir Alumnos a Actividades.
     */
    public function setInscripcionIndividual_post() {

        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);

		foreach ($data->idAlumno as $idAlumno){
			$datos = array(
				'idActividad' => $data->idActividad,
				"idAlumno" => $idAlumno
			);
			$this -> M_General -> insertar("ACT_Inscriben_Alumnos", $datos);
		}

		$this->response(null, 200);
    }
	
	/**
     * Método que obtiene todos los Alumnos inscritos a una Actividad Individual, mostrando solo los de su tutoria.
     */
    public function getAlumnosInscritosTutoria_get() {

        //Params del get
		$idActividad = $this->input->get("idActividad");
        $codSeccion = $this->input->get("codSeccion");

	   $condicion = null;
	   
		if(isset($idActividad) and isset($codSeccion)) $condicion = "ACT_Inscriben_Alumnos.idActividad = $idActividad and Secciones.codSeccion = $codSeccion";

        //Consultas a B.D
        $inscritos = $this->M_General->seleccionar(
            "ACT_Inscriben_Alumnos", //Tabla
            "Alumnos.idAlumno,Alumnos.nombre,Secciones.codSeccion", //Campos
			$condicion, //Condición
			["Alumnos","Secciones"], //Tabla relación
			["ACT_Inscriben_Alumnos.idAlumno = Alumnos.idAlumno","Alumnos.idSeccion = Secciones.idSeccion"], //Relación
			['left','left'] //Tipo relación
        );
            
		$this->response($inscritos, 200);
        
            
    }
	
	/**
     * Método que obtiene todos los Alumnos inscritos a una Actividad Individual, mostrando solo los de su cooridinación.
     */
    public function getAlumnosInscritosCoordinador_get() {

        //Params del get
		$idActividad = $this->input->get("idActividad");
        $idEtapa = $this->input->get("idEtapa");

	   $condicion = null;
	   
		if(isset($idActividad) and isset($idEtapa)) $condicion = "ACT_Inscriben_Alumnos.idActividad = $idActividad and Cursos.idEtapa = $idEtapa";

        //Consultas a B.D
        $inscritos = $this->M_General->seleccionar(
            "ACT_Inscriben_Alumnos", //Tabla
            "Alumnos.idAlumno,Alumnos.nombre,Secciones.codSeccion", //Campos
			$condicion, //Condición
			["Alumnos","Secciones","Cursos"], //Tabla relación
			["ACT_Inscriben_Alumnos.idAlumno = Alumnos.idAlumno","Alumnos.idSeccion = Secciones.idSeccion","Secciones.idCurso=Cursos.idCurso"], //Relación
			['left','left','left'] //Tipo relación
        );
            
		$this->response($inscritos, 200);
        
    }
	
	/**
     * Método que elimina un inscripcion de alumno
     */
    public function removeInscripcionAlumno_delete() {

        $idActividad = $this-> input -> get("idActividad");
        $idAlumno = $this-> input -> get("idAlumno");

        //Eliminar por ID
        $this -> M_General -> borrarCompuesta("ACT_Inscriben_Alumnos", $idAlumno, $idActividad, "idAlumno" , "idActividad");

		$this->response(true, 200);
    }
	
	
    /**
     * Obtienes todas las inscripciones si no se le pasa un parámetro.
     * Campos:
        * idAlumno -> Obtiene las actividades de un alumno (prox)
        * idActividad -> Obtiene todos los alumnos inscritos a una actividad específica
    *   @return Array Inscripciones
     */
    public function getInscripcionesIndividuales_get() {

        $idAlumno = $this->input->get("idAlumno");
        $idActividad = $this->input->get("idActividad");

        
        $condicion = null;

        if(isset($idActividad)) $condicion = "individuales.idActividad = $idActividad";
        if(isset($idAlumno)) $condicion = "alumno.idAlumno = $idAlumno";


        $actividadInfo = 
            $this->M_General->seleccionar(
                "ACT_Individuales individuales", //Tabla
                "alumno.nombre AS nombreAlumno,seccion.codSeccion as nombreSeccion", //Campos
                $condicion, //Condición
                ["Alumnos alumno", "Secciones seccion"], //Tabla relación
                ["individuales.idAlumno = alumno.idAlumno", "alumno.idSeccion = seccion.idSeccion"], //Relación
                ['left', 'left'] //Tipo relación
            );

		$this->response($actividadInfo, 200);
    }


    /**
     * ====================================
     *          INSCRIPCIONES CLASE
     * ====================================
    */

    public function setInscripcionClase_post() {

        // Obtenemos los datos del body
        $json = file_get_contents('php://input');

        //Decodificamos el JSON
        $data = json_decode($json);

        $datos = array(
            "idSeccion" => $data->idSeccion,
            'idActividad' => $data->idActividad
        );

        $this -> M_General -> insertar("ACT_Inscriben_Secciones", $datos);


		$this->response(null, 200);
    }

    /**
     * Método que elimina un inscripcion de alumno
     */
    public function removeInscripcionClase_delete() {

        $idActividad = $this-> input -> get("idActividad");
        $idSeccion = $this-> input -> get("idSeccion");

        //Eliminar por ID
        $this -> M_General -> borrarCompuesta("ACT_Inscriben_Secciones", $idSeccion, $idActividad, "idSeccion" , "idActividad");

		$this->response(true, 200);
    }
	
    /**
     * Obtienes todas las inscripciones si no se le pasa un parámetro.
     * Campos:
        * idClase -> Obtiene las actividades de una clase (prox)
        * idActividad -> Obtiene todos las clases inscritas a una actividad específica
    *   @return Array Inscripciones
     */
    public function getInscripcionesClase_get() {

        $idActividad = $this->input->get("idActividad");
        $idClase = $this->input->get("idClase");

        
        $condicion = null;

        if(isset($idActividad)) $condicion = "clase.idActividad = $idActividad";
        if(isset($idClase)) $condicion = "Secciones.idSeccion = $idClase";


        $actividadInfo = 
            $this->M_General->seleccionar(
                "ACT_Clase clase", //Tabla
                "Secciones.codSeccion", //Campos
                $condicion, //Condición
                ["Secciones"], //Tabla relación
                ["clase.idClase = Secciones.idSeccion"], //Relación
                ['left'] //Tipo relación
            );

		$this->response($actividadInfo, 200);
    }

    /**
     * Obtienes todas las inscripciones si no se le pasa un parámetro.
     * Campos:
        * tipoInscripcion -> 'c' Clase, 'i' Individual
        * idAlumno -> Id del alumno a insertar a la actividad
        * idClase -> Id de la clase a insertar
        * idActividad -> Obtiene todos los alumnos inscritos a una actividad específica
    *   @return Array Inscripciones
     */
    public function altaInscripcionIndividual_post() {

        $idAlumno = $this->input->get("idAlumno");
        $idActividad = $this->input->get("idActividad");
        $idClase = $this->input->get("idClase");
        $tipoInscripcion = $this->input->get("tipoInscripcion");
        
        $condicion = null;
        
        if(!isset($tipoInscripcion)) $this->response(null, 400);
        if(isset($idActividad)) $condicion = "individuales.idActividad = $idActividad";
        if(isset($idAlumno)) $condicion = "alumno.idAlumno = $idAlumno";

        $datos = null;
        
        if($tipoInscripcion == "C")
            $datos = array(
                ""
            );
        else if($tipoInscripcion =="I")
            $datos = array(
                ""
            );


        $this -> M_General -> insertar("ACT_Clase", $datos);


		$this->response(null, 200);
    }

    /**
	 * Generar PDF
	 * 
	 * Genera un pdf, con los datos pasados al método por medio de BODY.
     * 
	 * @return void
	 */
	public function generarPDF_get()
	{
		include_once('application/TFPDF/tfpdf.php');

		$datos = $this -> M_General -> seleccionar('Secciones s','s.nombre, u.correo', null, ['Usuarios u'], ['s.idTutor=u.idUsuario'], ['left']);
		
        $inscritos = $this->M_General->seleccionar(
            "ACT_Inscriben_Alumnos", //Tabla
            "alumnos.nombre,secciones.codSeccion", //Campos
			$condicion, //Condición
			["Alumnos","secciones"], //Tabla relación
			["ACT_Inscriben_Alumnos.idAlumno = alumnos.idAlumno","alumnos.idSeccion = secciones.idSeccion"], //Relación
			['left','left'] //Tipo relación
        );
        
        //echo $inscritos;

        $pdf = new TFPDF('P', 'mm', 'A4'); /*Crea el objeto FPDPF*/
        $pdf -> SetTitle('Listado de Inscritos a');
        $pdf -> SetDrawColor(0, 0, 0); /*Color de los Bordes*/
        $pdf -> SetTextColor(0, 0, 0); /*Color del Texto*/
        $pdf -> AddPage(); /*Añade una página*/
        $pdf -> AddFont('DejaVu','','DejaVuSans-Bold.ttf',true); /*Establece el estilo de letra*/
        $pdf -> SetFont('DejaVu','',7); /*Establece el estilo de letra*/
        $pdf -> Cell(0, 10, 'LISTADO DE INSCRITOS - ' . date('d/m/Y'), 0, 0, 'R'); /*Encabezado del PDF*/
        $pdf -> Image(base_url().'uploads/iconos/escudo-evg.png', 10, 10, 45); /*Logo EVG*/
        $pdf -> SetMargins(10, 10, 40); /*Establecer márgenes*/
        $pdf -> Ln(20); /*Salto de linea*/

        $pdf -> Cell(95, 10, 'SECCIÓN', 1, 0, 'C'); /*$pdf->Cell(ancho, alto, valor a escribir, borde, salto de linea, 'alineamiento');*/
        $pdf -> Cell(95, 10, 'TUTOR', 1, 1, 'C');
		if(!empty($inscritos)) 
		{
			
			

			foreach ($inscritos as $indice => $valor) 
			{
				$pdf -> Cell(95, 10, $indice, 1, 0, 'C');
				if (!empty($valor))
					$pdf -> Cell(95, 10, $valor, 1, 1, 'C');
				else
					$pdf -> Cell(95, 10, '-', 1, 1, 'C');
			}
		}
		else
		{
			$pdf->Cell(95,10, "Todavía no hay datos...", 1, 1, 'C');
		}
        //Redireccionamos a la URL para el pdf
        $this->response($pdf -> Output("I"), 200);
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