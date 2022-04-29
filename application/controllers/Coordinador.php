<?php
/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 16/05/2017
 * Time: 21:37
 */
class Coordinador extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('form');
        $this->load->model('ModelPDFAlumnos');
        $this->load->model('ModelActividades');
        $this->load->model('ModelMomentos');
        $this->load->model('ModelCategorias');
        $this->load->model('ModelCursos');
        $this->load->library('form_validation');
        $this->load->library('session');

    }

    public function index(){
        $this->load->view('Coordinador/SesionCoor');
    }

    public function menuCrearActividad(){
        $this->load->view('Coordinador/crearActividad');
    }

    public function menuModificarActividad(){
        $this->load->view('Coordinador/menuModActividad');
    }

    public function modificarActividad(){
        $this->load->view('Coordinador/modificarActividad');
    }

    public function menuAsignarActividades(){
        $this->load->view('Coordinador/asignarActCat');
    }

    public function menuAddActividadesCat(){
        $this->load->view('Coordinador/addActCat');
    }

    public function menuMomentos(){
        $this->load->view('Coordinador/menuMomentos');
    }

    public function modificarMomento(){
        $this->load->view('Coordinador/modificarMomento');
    }

    public function mostrarAlumnosApuntados(){
        $this->load->view('Coordinador/mostrarAlumnosCoor');
    }

    public function cambiarActividadesCategoria(){
        $this->load->view("Coordinador/cambiarActCat");
    }

    public function gestionarCategorias(){
        $this->load->view("Coordinador/gestionarCategorias");
    }

    public function modificarCategoria(){
        $this->load->view("Coordinador/modificarCategoria");
    }

    public function paginaBorrarMomento(){
        $this->load->view("Coordinador/borrarMomento");
    }

    public function menuBorrarCategoria(){
        $this->load->view("Coordinador/borrarCategoria");
    }

    public function menuMeterCurso(){
        $this->load->view("Coordinador/addCursos");
    }

    public function gestionarCursos(){
        $this->load->view("Coordinador/gestionarCursos");
    }

    public function menuModificarCursos(){
        $this->load->view("Coordinador/modificarCurso");
    }

    public function menuBorrarCurso(){
        $this->load->view("Coordinador/borrarCurso");
    }

    public function asignarCursosSecciones(){
        $this->load->view("Coordinador/asignarCursosSecciones");
    }

    public function asignarCurSec(){
        $this->load->view("Coordinador/asignarCurSec");
    }

    public function accesoDenegado(){
        $this->load->view("errors/accesoDenegadoCoor");
    }

    public function borrarActividad(){
        $this->load->view("Coordinador/borrarActividad");
    }

    public function crearMomento(){
        $data_momento = array(
            "momento" => $this->input->post('nombreMomento')
        );

        $consulta = $this->ModelMomentos->insertMomento($data_momento);

        if($consulta == true){
            redirect("Coordinador/menuMomentos?correcto=1");
        }
    }



    public function modificarMomentoProceso(){
        $data_momento = array(
            'idMomento' => $this->input->post('idMomento'),
            'nombre' => $this->input->post('momento')
        );

        $consulta = $this->ModelMomentos->updateMomento($data_momento);

        if($consulta == true){
            redirect("Coordinador/menuMomentos?correcto=2");
        }
        else{
            redirect("Coordinador/menuMomentos?correcto=3");
        }

    }

    public function borrarMomento(){
        $borrar = $this->input->post('borrar');
        $volver = $this->input->post('volver');

        if($borrar != ""){
            $data_momento = array(
                'idMomento' => $this->input->post('idMomento')
            );

            $consulta = $this->ModelMomentos->deleteMomento($data_momento);

            if($consulta == true){
                redirect(base_url()."index.php/Coordinador/menuMomentos?correcto=3");
            }
            else{
                redirect(base_url()."index.php/Coordinador/menuMomentos");
            }
        }
        else if($volver != ""){
            redirect(base_url()."index.php/Coordinador/menuMomentos?error=3");
        }

    }

    public function borrarCategoria(){
        $borrar = $this->input->post('borrar');
        $volver = $this->input->post('volver');

        if($borrar != ""){
            $data_categoria = array(
                'idCategoria' => $this->input->post('idCategoria')
            );

            $consulta = $this->ModelCategorias->deleteCategoria($data_categoria);

            if($consulta == true){
                redirect(base_url()."index.php/Coordinador/gestionarCategorias?correcto=3");
            }
            else{
                redirect(base_url()."index.php/Coordinador/gestionarCategorias");
            }
        }
        else if($volver != ""){
            redirect(base_url()."index.php/Coordinador/gestionarCategorias");
        }

    }

    public function crearActividad(){

        //Configuracion para subir archivos
        $config = [
            "upload_path" => "./archivos",
            "allowed_types" => "pdf",
            "remove_spaces" => false
        ];

        //Si la actividad es individual
        if($this->input->post('tipoAct') == "I"){

            //Recogiendo datos del formulario
            $data_actividad = array(
                'nombreActividad' => $this->input->post('nombreActividad'),
                'monitor' => $this->input->post('monitor'),
                'sexo' => $this->input->post('sexo'),
                'concurso' => $this->input->post('concurso'),
                'bases' => $this->input->post('bases'),
                'fechaInicio' => $this->input->post('fechaInicio'),
                'fechaFin' => $this->input->post('fechaFin'),
                'maxClase' => $this->input->post('maxClase'),
                'tipoAct' => $this->input->post('tipoAct'),
                'momento' => $this->input->post('momento'),

            );

            //Preparando lineas de codigo para subir los archivos
            $this->load->library('upload',$config);

            //Si se va a realizar la subida del archivo
            if($this->upload->do_upload('bases')){

                $upload_data = $this->upload->data();
                $data_actividad['bases'] = $upload_data['file_name'];
            }
            else{
                //Si falla, que muestre los errores
                echo $this->upload->display_errors();
            }

            //Llama al modelo para crear la actividad
            $correcto = $this->ModelActividades->insertActividad($data_actividad);

            if($correcto == true){
                //Si se crea con exito, se le redirige a la pagina principal
                redirect(base_url()."index.php/Coordinador?correcto=actividad");
            }
            else{
                //Si no se crea con exito, se le redirige a la pagina principal con un mensaje de error
                redirect(base_url()."index.php/Coordinador?error=actividad");
            }
        }
        
        //Si es de grupo
        else{
            //Recogiendo datos del formulario
            $data_actividad = array(
                'nombreActividad' => $this->input->post('nombreActividad'),
                'monitor' => $this->input->post('monitor'),
                'sexo' => $this->input->post('sexo'),
                'concurso' => $this->input->post('concurso'),
                'bases' => $this->input->post('bases'),
                'fechaInicio' => $this->input->post('fechaInicio'),
                'fechaFin' => $this->input->post('fechaFin'),
                'maxClase' => $this->input->post('maxClase'),
                'tipoAct' => $this->input->post('tipoAct'),
                'momento' => $this->input->post('momento'),
                'alumnos_seccion' => $this->input->post('alumnos_seccion'),

            );

            //Llama al modelo para crear la actividad
            $this->load->library('upload',$config);

            //Si se va a realizar la subida del archivo
            if($this->upload->do_upload('bases')){

                $upload_data = $this->upload->data();
                $data_actividad['bases'] = $upload_data['file_name'];
            }
            else{
                //Si falla, que muestre los errores
                echo $this->upload->display_errors();
            }

            //Llama al modelo para crear la actividad
            $correcto = $this->ModelActividades->insertActividadGrupo($data_actividad);

            if($correcto == true){
                //Si se crea con exito, se le redirige a la pagina principal
                redirect(base_url()."index.php/Coordinador?correcto=actividad");
            }
            else{
                //Si no se crea con exito, se le redirige a la pagina principal con un mensaje de error
                redirect(base_url()."index.php/Coordinador?error=actividad");
            }

        }

    }

    public function modificarActividadProceso(){

        //Si la actividad es de tipo individual
        if($this->input->post('tipoAct') == "I"){
            $data_actividad = array(
                'idActividad' => $this->input->post('idActividad'),
                'nombreActividad' => $this->input->post('nombreActividad'),
                'monitor' => $this->input->post('monitor'),
                'sexo' => $this->input->post('sexo'),
                'concurso' => $this->input->post('concurso'),
                'bases' => '',
                'fechaInicio' => $this->input->post('fechaInicio'),
                'fechaFin' => $this->input->post('fechaFin'),
                'maxClase' => $this->input->post('maxClase'),
                'tipoAct' => $this->input->post('tipoAct'),
                'momento' => $this->input->post('momento')

            );

            //Si se quiere cambiar el archivo de las bases
            if(isset($_FILES['archivo']['name'])){

                //Busca el nombre del archivo antiguo en la base de datos
                $sql_sacar_archivo = "SELECT urlBases FROM act_actividad WHERE idActividad = ".$data_actividad['idActividad'];
                $query_sacar_archivo = $this->db->query($sql_sacar_archivo);
                $fila_archivo = $query_sacar_archivo->row();

                $dir = "archivos/";

                //Borra el archivo antiguo
                unlink($dir.$fila_archivo->urlBases);

                //Preparando archivo para subir
                $fichero_subido = $dir.basename($_FILES['archivo']['name']);

                //Si se sube el archivo
                if(move_uploaded_file($_FILES['archivo']['tmp_name'],$fichero_subido)){

                    //El nombre del archivo se guarda en el arrar para que se pueda guardar con una consulta
                    //en la base de datos
                    $data_actividad['bases'] = $_FILES['archivo']['name'];
                }
                else{
                    echo 'error';
                }
            }

            //Llama al modal para ejecutar la consulta
            $correcto = $this->ModelActividades->updateActividad($data_actividad);

            //Si es correcto la consulta...
            if($correcto == true){
                redirect(base_url()."index.php/Coordinador/menuModificarActividad?correcto=1");
            }
        }
        //Si la actividad es de tipo grupo
        if($this->input->post('tipoAct') == "G"){
            $data_actividad = array(
                'idActividad' => $this->input->post('idActividad'),
                'nombreActividad' => $this->input->post('nombreActividad'),
                'monitor' => $this->input->post('monitor'),
                'sexo' => $this->input->post('sexo'),
                'concurso' => $this->input->post('concurso'),
                'bases' => '',
                'fechaInicio' => $this->input->post('fechaInicio'),
                'fechaFin' => $this->input->post('fechaFin'),
                'maxClase' => $this->input->post('maxClase'),
                'tipoAct' => $this->input->post('tipoAct'),
                'momento' => $this->input->post('momento'),
                'alumnos_seccion' => $this->input->post('alumnos_seccion')
            );

            //Si se quiere cambiar el archivo de las bases
            if(isset($_FILES['archivo']['name'])){

                //Busca el nombre del archivo antiguo en la base de datos
                $sql_sacar_archivo = "SELECT urlBases FROM act_actividad WHERE idActividad = ".$data_actividad['idActividad'];
                $query_sacar_archivo = $this->db->query($sql_sacar_archivo);
                $fila_archivo = $query_sacar_archivo->row();

                $dir = "archivos/";

                //Borra el archivo antiguo
                unlink($dir.$fila_archivo->urlBases);

                //Preparando archivo para subir
                $fichero_subido = $dir.basename($_FILES['archivo']['name']);

                //Si se sube el archivo
                if(move_uploaded_file($_FILES['archivo']['tmp_name'],$fichero_subido)){

                    //El nombre del archivo se guarda en el arrar para que se pueda guardar con una consulta
                    //en la base de datos
                    $data_actividad['bases'] = $_FILES['archivo']['name'];
                }
                else{
                    echo 'error';
                }
            }

            //Llama al modal para ejecutar la consulta
            $correcto = $this->ModelActividades->updateActividadGrupo($data_actividad);

            //Si es correcto la consulta...
            if($correcto == true){
                redirect(base_url()."index.php/Coordinador/menuModificarActividad?correcto=1");
            }
        }

    }

    public function cargarDatoAlumnos(){

        $alumnos_seccion = array(
            'S' => 'Se apuntan determinados alumnos a la actividad',
            'N' => 'Se apunta la seccion entera a la actividad'
        );

        echo form_dropdown('alumnos_seccion', $alumnos_seccion,'','class="form-control"');
    }

    public function addActividadCategoria(){
        $data = array(
            'idActividad' => $this->input->post('actividad'),
            'idCategoria' => $this->input->post('categorias')
        );

        $error = "dato_duplicado";

        $correcto = $this->ModelActividades->insertActividadCategoria($data);

        if($correcto == true){
            unset($error);
            redirect("Coordinador");
        }else{
            redirect("Coordinador/menuAddActividadesCat?error=".$error);
        }
    }

    public function cambiarActividadCategoria(){
        $enviar = $this->input->post('enviar');
        $volver = $this->input->post('volver');

        $data_categorias = array();


        foreach ($this->input->post('categoria') as $fila => $valor){

            $data_categorias[] = $valor;

        }

        $data = array(
            'actividad' => $this->input->post('actividad'),
            'categoria' => $this->input->post('categoria')
        );

        if($enviar != ""){
            $this->ModelActividades->updateActividadCategoria($data,$data_categorias);
        }
        else if($volver != ""){
            redirect(base_url()."index.php/Coordinador/menuAsignarActividades");
        }
    }

    public function crearPDFAlumnos(){

        $data = array(
            'momento' => $this->input->post('momento')
        );

        $this->ModelPDFAlumnos->generarPDFCoordinador($data);
    }

    public function crearCategoria(){
        $data = array(
            'idCategoria' => $this->input->post('idCategoria'),
            'nombre' => $this->input->post('nombre')
        );

        $sql_existe = "SELECT * FROM act_categorias WHERE idCategoria LIKE '".$data['idCategoria']."'";
        $query_existe = $this->db->query($sql_existe);

        if($query_existe->num_rows()){

            redirect(base_url()."index.php/Coordinador/gestionarCategorias?error=duplicado");



        }
        else{
            if(!ctype_alpha($data['idCategoria'])){
                redirect(base_url()."index.php/Coordinador/gestionarCategorias?error=3");
            }

            $correcto = $this->ModelCategorias->insertCategoria($data);


            if($correcto == true){
                redirect(base_url()."index.php/Coordinador/gestionarCategorias?correcto=1");
            }
            else{
                redirect(base_url()."index.php/Coordinador/gestionarCategorias?error=1");
            }
        }



    }

    public function cambiarNombreCategoria(){

        $data = array(
            'id' => $this->input->post('id'),
            'categoria' => $this->input->post('categoria')
        );

        $correcto = $this->ModelCategorias->updateCategoria($data);

        if($correcto){
            redirect(base_url()."index.php/Coordinador/gestionarCategorias?correcto=2");
        }
        else{
            redirect(base_url()."index.php/Coordinador/gestionarCategorias?error=2");
        }

    }

    public function crearCurso(){

        $data = array(
            'nombre' => $this->input->post('nombreCurso'),
            'categoria' => $this->input->post('categoria')
        );

        $correcto = $this->ModelCursos->insertCurso($data);

        if($correcto){
            redirect(base_url()."index.php/Coordinador?correcto=curso");
        }
        else{
            redirect(base_url()."index.php/Coordinador?error=".$correcto);
        }


    }

    public function modificarCurso(){

        $data = array(
            'idCurso' => $this->input->post('idCurso'),
            'nombreCurso' => $this->input->post('nombreCurso'),
            'categoria' => $this->input->post('categoria')
        );

        $correcto = $this->ModelCursos->updateCurso($data);

        if($correcto){
            redirect(base_url()."index.php/Coordinador/gestionarCursos?correcto=1");
        }
        else{
            redirect(base_url()."index.php/Coordinador/gestionarCursos?error=1");
        }

    }

    public function borrarCurso(){

        $data = array(
            'idCurso' => $this->input->post('idCurso')
        );

        $borrar = $this->input->post('borrar');
        $volver = $this->input->post('volver');

        if($borrar != ""){
            $this->ModelCursos->deleteCurso($data);
            redirect(base_url()."index.php/Coordinador/gestionarCursos?correcto=2");
        }
        if($volver != ""){
            redirect(base_url()."index.php/Coordinador/gestionarCursos");
        }




    }

    public function cambiarCursoSeccion(){

        $data = array(
            'curso' => $this->input->post('curso'),
            'seccion' => $this->input->post('seccion')
        );

        $correcto = $this->ModelCursos->insertCursoSeccion($data);

        if($correcto){
            redirect(base_url()."index.php/Coordinador/asignarCursosSecciones?correcto=1");
        }
        else{
            redirect(base_url()."index.php/Coordinador/asignarCursosSecciones?error=1");
        }

    }

    public function quitarCursoSeccion(){

        $data = array(
            'seccion' => $this->input->get('seccion')
        );

        $correcto = $this->ModelCursos->deleteCursoSeccion($data);

        if($correcto){
            redirect(base_url()."index.php/Coordinador/asignarCursosSecciones?correcto=2");
        }
        else{
            redirect(base_url()."index.php/Coordinador/asignarCursosSecciones?error=2");
        }


    }

    public function actionBorrarActividad(){
        $enviar = $this->input->post('borrar');

        $data = array(
            'actividad' => $this->input->post('actividad')
        );

        if($enviar != ""){
            $correcto = $this->ModelActividades->deleteActividad($data);
            if($correcto == true){
                redirect(base_url()."index.php/Coordinador/menuModificarActividad?correcto=2");
            }
        }
        else{
            redirect(base_url()."index.php/Coordinador/menuModificarActividad");
        }
    }


}