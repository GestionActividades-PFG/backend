<?php
/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 16/05/2017
 * Time: 21:37
 */
class Tutor extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('ModelPDFAlumnos');
        $this->load->database();
        $this->load->library('session');

    }

    public function index()
    {

        $this->load->view('tutor/SesionTutor');

    }

    public function altaInscripcion()
    {

        $this->load->view('tutor/altaInscripcionAlumnos');

    }

    public function CerrarSesion()
    {
        redirect(base_url()."index.php");
    }

    public function accesoDenegado(){
        $this->load->view('errors/accesoDenegadoTutor');
    }

    public function cargarDetalles()
    {

        $where = " (DATE(now()) BETWEEN fechaInicio AND fechaFin) AND idActividad = " . $_REQUEST['actividad'];
        $this->db->select('*')->from('act_actividad')->where($where);

        $query_actividades = $this->db->get();

        foreach ($query_actividades->result() as $fila_act) {

            if (!$fila_act->monitor == "") {
                echo '<p>Monitor: '.$fila_act->monitor .'</p>';
            }

            if ($fila_act->concurso == "S") {
                echo '<p>Concurso: Sí</p>';
            } else {
                echo '<p>Concurso: No</p>';
            }

            if (!$fila_act->urlBases == "") {
                echo '<p><a href="' . base_url() . 'archivos/' . $fila_act->urlBases . '">Haz click aquí para ver las bases del concurso</a></p>';
            }

            if ($fila_act->tipoAct == "I") {
                echo '<p>Tipo de actividad: Individual</p>';
            } else {
                echo '<p>Tipo de actividad: Grupo</p>';
            }

            echo form_hidden('tipoAct', $fila_act->tipoAct);

            echo form_hidden('maxClase', $fila_act->maxClase);

            echo '<p>Maximo de participantes por clase: '.$fila_act->maxClase.'</p>';

            $sql_momento = "SELECT * FROM act_momento WHERE idMomento = ".$fila_act->momento;
            $query_momento = $this->db->query($sql_momento);
            $fila_momento = $query_momento->row();

            echo '<p>Momento de la actividad: '.$fila_momento->nombreMomento.'</p>';

            echo form_submit('enviar', 'Inscribir alumnos', 'id="enviar_actividad" class="btn btn-success"');

        }

    }

    public function existeSeccion()
    {

        $this->load->view("Tutor/existeSeccionAct");

    }

    public function formAlumnosGrupo()
    {

        $this->load->view("Tutor/altaAlumnoActGrupo");

    }

    public function mostrarAlumnosApuntados(){

        $this->load->view("Tutor/mostrarAlumnosTutor");

    }


    public function prepararFormAlumnos()
    {

        $data = array(
            'actividad' => $this->input->post('actividad'),
            'maxClase' => $this->input->post('maxClase')
        );

        if ($this->input->post('tipoAct') == "I") {
            redirect(base_url()."index.php/Tutor/formAlumnosIndividual?actividad=".$data['actividad']."&maxClase=".$data['maxClase']);
        } else {

            $query_alumnos = $this->db->query("SELECT * FROM act_grupo WHERE idActividad = ".$data['actividad']);
            $ret_alumnos = $query_alumnos->row();

            if ($ret_alumnos->alumnos == 'N') {

                $sql_existe = "SELECT * FROM act_insc_grupo WHERE idActividad = ".$data['actividad']." AND idSeccion = '".$this->session->seccion."'";
                $query_existe = $this->db->query($sql_existe);

                $fila_existe = $query_existe->row();

                if (isset($fila_existe->numGrupo)) {
                    redirect(base_url()."index.php/Tutor/existeSeccion?actividad=".$data['actividad']);
                } else {
                    $insertar_datos = "INSERT INTO act_insc_grupo VALUES (NULL, ".$data['actividad'].", '".$this->session->seccion ."');";
                    $this->db->query($insertar_datos);
                    redirect(base_url() . "index.php/Tutor/altaInscripcion?actSeccion=A");
                }

            } else {
                redirect(base_url() . "index.php/Tutor/formAlumnosGrupo?actividad=" . $data['actividad'] . "&maxClase=" . $data['maxClase']);
            }

        }
    }

    public function formAlumnosIndividual()
    {


        $this->load->view("tutor/altaAlumnoActIndividual");

    }

    public function inscribirAlumnoIndividual()
    {

        if($this->input->post('volver')){
            redirect(base_url() . "index.php/Tutor/altaInscripcion");
        }

        $data = array(
            'maxClase' => $this->input->post('maxClase')
        );

        $data_insertar = array(
            'idActividad' => $this->input->post('idActividad'),
            'NIA' => $this->input->post('alumno')
        );

        $max_clase = $data['maxClase'];

        $alta = false;

        $sql_max = "SELECT count(*) as alumnos
                            FROM act_individual_al INNER JOIN alumnos
                                ON alumnos.NIA=act_individual_al.NIA
                            INNER JOIN secciones
                                ON alumnos.idSeccion=secciones.idSeccion
                        WHERE secciones.idSeccion LIKE '".$this->session->seccion."' AND idActividad = ".$data_insertar['idActividad']."";

        $query_max_clase = $this->db->query($sql_max);
        $ret = $query_max_clase->row();

        if ($ret->alumnos < $data['maxClase']) {
            
            $sql_existe = "SELECT * FROM act_individual_al
                WHERE idActividad = ".$data_insertar['idActividad']." AND NIA LIKE '".$data_insertar['NIA']."'";
            $query_existe = $this->db->query($sql_existe);

            $ret_existe = $query_existe->row();

            if (!$ret_existe) {

                $sql_sexo = "SELECT * FROM alumnos WHERE NIA LIKE '".$data_insertar['NIA']."'";
                $query_sexo = $this->db->query($sql_sexo);
                $fila_sexo = $query_sexo->row();

                $sql_act_sexo = "SELECT * FROM act_actividad WHERE idActividad = ".$data_insertar['idActividad'];
                $query_act_sexo = $this->db->query($sql_act_sexo);
                $fila_act_sexo = $query_act_sexo->row();

                //Valida el sexo del alumno
                if(($fila_sexo->sexo == "H" && $fila_act_sexo->sexo == "M")
                   || ($fila_sexo->sexo == "M" && $fila_act_sexo->sexo == "F")
                   || $fila_act_sexo->sexo == ""){
                    
                    $alta = true;
                }
                else{
                    $error = "5"; //Cuando la actividad y el alumno tiene sexos diferentes
                }

            } else {
                $error = "1"; //Cuando el alumno ya esta apuntado.
            }

        } else {
            $error = "2"; //Cuando ya se ha llegado al tope de alumnos.
        }

        if ($alta == true) {
            $this->db->insert('act_individual_al', $data_insertar);
            redirect(base_url()."index.php/Tutor/formAlumnosIndividual?actividad=".$data_insertar['idActividad']."&maxClase=".$max_clase);
        } else {
            redirect(base_url()."index.php/Tutor/formAlumnosIndividual?actividad=".$data_insertar['idActividad']."&maxClase=".$max_clase."&error=".$error);
        }

    }

    public function borrarAlumnoIndividual()
    {
        $data = array(
            'idActividad' => $this->input->get('actividad'),
            'NIA' => $this->input->get('alumno'),
            'maxClase' => $this->input->get('maxClase')

        );

        $query_borrar_alumno = "DELETE FROM act_individual_al WHERE idActividad = ".$data['idActividad']." AND nia = '".$this->input->get("usuario") . "'";
        $this->db->query($query_borrar_alumno);
        redirect(base_url() . "index.php/Tutor/formAlumnosIndividual?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']);

    }

    public function inscribirGrupoAlumnos()
    {

        if($this->input->post('volver')){
            redirect(base_url() . "index.php/Tutor/altaInscripcion");
        }

        $data = array(
            'idActividad' => $this->input->post('idActividad'),
            'maxClase' => $this->input->post('maxClase')

        );

        $sql_max2 = "SELECT * FROM act_actividad WHERE idActividad = ".$_POST['idActividad'];
        $query_max2 = $this->db->query($sql_max2);
        $fila_max2 = $query_max2->row();

        $contador = 0;
        foreach($_POST['alumno'] as $fila){
            $contador++;
        }

        if($fila_max2->maxClase < $contador){
            redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']."&error=5");
        }

        foreach($_POST['alumno'] as $fila){
            $sql_alumno = "SELECT * FROM alumnos WHERE nia LIKE '".$fila."'";
            $query_alumno = $this->db->query($sql_alumno);
            $fila_al = $query_alumno->row();

            if($fila_max2->sexo == "F" && $fila_al->sexo != "M"){
                redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']."&error=6");
            }
            if($fila_max2->sexo == "M" && $fila_al->sexo != "H"){
                redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']."&error=6");
            }

        }

        $sql_existe_num_grupo = "SELECT numGrupo FROM act_insc_grupo WHERE idActividad = " . $_POST['idActividad'] . " AND idSeccion = '" . $this->session->seccion . "'";
        $query_existe_num = $this->db->query($sql_existe_num_grupo);
        $fila_existe = $query_existe_num->row();

        if (!$query_existe_num->num_rows()) {
            $insertar_datos = "INSERT INTO act_insc_grupo VALUES (NULL, ".$_POST['idActividad'].", '".$this->session->seccion ."');";
            $this->db->query($insertar_datos);

            $sql_max = "SELECT max(numGrupo) as num_maximo FROM act_insc_grupo";
            $query_max = $this->db->query($sql_max);
            $fila_max = $query_max->row();


            foreach ($_POST['alumno'] as $fila) {
                $sql_insertar_alumno = "INSERT INTO act_detalle_al_grupo VALUES (" . $fila_max->num_maximo . ",'" . $fila . "')";
                $this->db->query($sql_insertar_alumno);
            }
            redirect(base_url() . "index.php/Tutor/formAlumnosGrupo?actividad=" . $data['idActividad'] . "&maxClase=" . $data['maxClase']);
        }
        else{

            $sql_sacar_max = "SELECT maxClase
                    FROM act_actividad WHERE idActividad = " . $_POST['idActividad'];
            $query_sacar_max = $this->db->query($sql_sacar_max);
            $fila_max = $query_sacar_max->row();

            $sql_num_alumnos_insc="SELECT count(*) as contador
                    FROM act_detalle_al_grupo WHERE numGrupo = ".$fila_existe->numGrupo;
            $query_num_alumnos_insc = $this->db->query($sql_num_alumnos_insc);
            $fila_num_alumnos = $query_num_alumnos_insc->row();

            if($fila_max->maxClase > $fila_num_alumnos->contador){

                $sql_num_grupo = "SELECT DISTINCT act_insc_grupo.numGrupo
                                        FROM act_insc_grupo INNER JOIN act_detalle_al_grupo
                                            ON act_detalle_al_grupo.numGrupo=act_insc_grupo.numGrupo
                                    WHERE act_insc_grupo.idActividad = ".$_POST['idActividad']."
                                    AND act_insc_grupo.idSeccion = '".$this->session->seccion."'";
                $query_num_grupo = $this->db->query($sql_num_grupo);
                $fila_num = $query_num_grupo->row();

                foreach($_POST['alumno'] as $fila){
                    $sql_buscar_alumno = "SELECT act_detalle_al_grupo.*
                                            FROM act_detalle_al_grupo INNER JOIN act_insc_grupo
                                                ON act_detalle_al_grupo.numGrupo=act_insc_grupo.numGrupo
                                            WHERE act_detalle_al_grupo.nia = '".$fila."' AND act_insc_grupo.idActividad = ".$_POST['idActividad'];
                    $query_buscar_alumno = $this->db->query($sql_buscar_alumno);

                    if(!$query_buscar_alumno->num_rows()){
                        $sql_insertar_alumno = "INSERT INTO act_detalle_al_grupo VALUES (".$fila_num->numGrupo.",'".$fila."')";
                        $this->db->query($sql_insertar_alumno);

                        redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']);
                    }else{
                        $error = 4;
                        redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']."&error=".$error);
                    }
                }

            }
            else{
                $error = 2;
                redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']."&error=".$error);
            }


        }


    }


    public function borrarAlumnoGrupo(){
        $data = array(
            'idActividad' => $this->input->get('actividad'),
            'NIA' => $this->input->get('alumno'),
            'maxClase' => $this->input->get('maxClase')

        );

        $num_grupo = $this->input->get('numGrupo');

        $query_borrar_alumno = "DELETE FROM act_detalle_al_grupo WHERE numGrupo = ".$num_grupo." AND nia = '".$this->input->get("usuario")."'";
        $this->db->query($query_borrar_alumno);

        $sql_num_grupo = "SELECT DISTINCT act_insc_grupo.numGrupo
                                    FROM act_insc_grupo INNER JOIN act_detalle_al_grupo
                                        ON act_detalle_al_grupo.numGrupo=act_insc_grupo.numGrupo
                                WHERE act_insc_grupo.idActividad = ".$_GET['actividad']." AND act_insc_grupo.idSeccion = '".$this->session->seccion."'";
        $query_grupo = $this->db->query($sql_num_grupo);
        $fila_grupo = $query_grupo->row();

        $sql_lista_alumnos = "SELECT alumnos.*
                                                FROM act_detalle_al_grupo INNER JOIN alumnos
                                                    ON alumnos.nia=act_detalle_al_grupo.nia
                                            WHERE numGrupo = ".$num_grupo;
        $query_apuntados = $this->db->query($sql_lista_alumnos);

        if(!$query_apuntados->num_rows()){
            $sql_borrar_grupo = "DELETE FROM act_insc_grupo WHERE numGrupo = ".$num_grupo;
            $query_borrar_grupo = $this->db->query($sql_borrar_grupo);
            if($query_borrar_grupo){
                redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']);
            }
        }
        else{
            redirect(base_url()."index.php/Tutor/formAlumnosGrupo?actividad=".$data['idActividad']."&maxClase=".$data['maxClase']);
        }



    }

    public function operacionActSeccion(){
        $data = array(
           'actividad' => $this->input->post('actividad')
        );

        if($this->input->post('borrar')){
            $sql = "DELETE FROM act_insc_grupo WHERE idActividad = ".$data['actividad']." AND idSeccion = '".$this->session->seccion."'";
            $query = $this->db->query($sql);
            redirect(base_url()."index.php/Tutor/altaInscripcion?actSeccion=S");
        }
        else{
            redirect(base_url()."index.php/Tutor/altaInscripcion");
        }
    }

    public function generarPDF(){

        $data = array(
            'momento' => $this->input->post('momento')
        );

        $sql_cat_id="SELECT act_categorias.idCategoria
                                    FROM act_categorias INNER JOIN act_cursos
                                    ON act_cursos.idCategoria=act_categorias.idCategoria
                                    INNER JOIN secciones
                                    ON secciones.codCurso=act_cursos.codCurso
                                    WHERE secciones.idSeccion LIKE '".$_SESSION['seccion']."'";

        $query_cat_id = $this->db->query($sql_cat_id);

        if($query_cat_id->num_rows()){

            $fila_cat = $query_cat_id->row();

            $sql_existe = "SELECT DISTINCT act_actividad.idActividad, act_actividad.nombreActividad, act_actividad.sexo
                        FROM act_actividad INNER JOIN act_actividad_cat
                            ON act_actividad.idActividad=act_actividad_cat.idActividad
                            INNER JOIN act_categorias
                            ON act_categorias.idCategoria=act_actividad_cat.idCategoria
                            INNER JOIN act_cursos
                            ON act_categorias.idCategoria=act_cursos.idCategoria
                            INNER JOIN secciones
                            ON secciones.codCurso=act_cursos.codCurso
                            INNER JOIN act_momento
                            ON act_actividad.momento=act_momento.idMomento
                        WHERE act_actividad_cat.idCategoria LIKE '".$fila_cat->idCategoria."' AND idMomento = ".$data['momento'];

            $query_existe = $this->db->query($sql_existe);
            if($query_existe->num_rows()){
                $this->ModelPDFAlumnos->generarPDFTutor($data);
            }
            else{
                redirect(base_url()."index.php/Tutor/mostrarAlumnosApuntados?error=no_act");
            }
        }
        else{
            redirect(base_url()."index.php/Tutor/mostrarAlumnosApuntados?error=no_cat");
        }





    }


}