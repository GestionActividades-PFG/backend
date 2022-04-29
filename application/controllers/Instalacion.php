<?php

/**
 * Created by PhpStorm.
 * User: juan1, smatamoros
 * Date: 06/06/2017
 * Modified: 28/04/2022
 */
class Instalacion extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index(){
        $this->load->view("instalacion/bienvenido");
    }

    public function Instalacion1(){
        $this->load->view("instalacion/instalacion1");
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

        $filename = __DIR__ . "/../script_sql/$fileName.sql";

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

        echo "Se han encontrado: $numErrores errores.";

        if($numErrores == 0) $this->load->view("instalacion/instalacion2");
    }

    public function Instalacion3(){

        //Hacemos una insercion masiva de datos...
        $this->FileInstalation("insercionMasiva");

        //Comprobamos que si no hay datos en gestión iniciamos la instalación de gestor.
        //Si no la damos por finalizada la instalación.
        $sql_gestor = "SELECT * FROM gestion";
        $query = $this->db->query($sql_gestor);

        if($query->num_rows()){
            redirect(base_url()."index.php/Instalacion/instalacionExito");
        }
        else{
            redirect(base_url()."index.php/Instalacion/InstalacionGestor");
        }
    }

    public function instalacionExito(){
        $this->load->view("instalacion/instalacion3");
    }

    public function crearUsuarioGestion(){
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'pass' => $this->input->post('pass'),
            'pass2' => $this->input->post('pass2')
        );

        if($data['pass']==$data['pass2']){
            $contra = password_hash($data['pass'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO gestion VALUES(DEFAULT,'".$data['nombre']."','".$contra."')";
            $this->db->query($sql);
            redirect(base_url()."index.php/Instalacion/instalacionExito");
        }
        else{
            redirect(base_url()."index.php/Instalacion/InstalacionGestor");
        }
    }

    public function InstalacionGestor(){
        $this->load->view("instalacion/instalacionGestor");
    }
}