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
		///header("Refresh: 10; url = ".base_url()."add-admin");
		/*$this -> M_Instalacion -> tablas();
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Administrador','descripcion' => 'Administrador'));
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Gestor','descripcion' => 'Gestor'));
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Tutor','descripcion' => 'Tutor de una Clase'));
		$this -> M_General -> insertar('Perfiles', Array('nombre' => 'Profesor','descripcion' => 'Profesor'));
		*/
	}	

	public function install() {
		$this->FileInstalation("creacionTablas");
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
     * Método que lee línea por línea de un archivo .sql y lo ejecuta
     * 
     * @param fileName nombre del fichero a ejecutar (sin extensión)
     * 
     * Optimizar esta función, con mapeos o similares.
     */
    public function FileInstalation($nombreArchivo = null) {

        //Ejecutamos la consulta por medio del propio archivo (.sql), y así nos evitamos 
        //ensuciar el código y tener que escribirlas a mano...

        $fileName = ($nombreArchivo == null) ? "incidencias" : $nombreArchivo;//$this->input->get('nombreFichero');

        $filename = __DIR__ . "/../sql/$fileName.sql";

        //Variable temporal, se usa para guardar la query actual.
        $templine = '';

        //Leemos todo el fichero y lo devolvemos como un array
        $lineas = file($filename);

        $numErrores = 0;

        //Iteramos sobre cada línea
        foreach ($lineas as $linea) {
            // Nos la saltamos si es un comentario o similares...
            if (substr($linea, 0, 2) == '--' || $linea == '' || $linea == "/*" || $linea == "*/") continue;
            //if (substr($linea, 0, 2) == '/*') continue;

            // Añadimos la linea
            $templine .= $linea;

            // Si tiene un punto y coma al final, es el final de un query...
            if (substr(trim($linea), -1, 1) == ';') {
                // Ejecutamos el query y mostramos errores si los hay...
                if(!$this->db->query($templine)) {
                    echo 'Se ha producido un error al ejecutar la consulta \'<strong>' 
                        . $templine . '\': ' 
                        . $this->db->query->error() . '<br /><br />';
                    $numErrores++;
                }

                //Vaciamos la variable temporal
                $templine = '';
            }
        }

        //print "Se han encontrado: $numErrores errores.";

        if($numErrores == 0) $this->load->view("Instalacion/V_Admin");
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

		$this->input->post("nombre");

		$datos = array(
			"nombre" => $this->input->post("nombre"),
			"correo" => $this->input->post("correo")
		);

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
