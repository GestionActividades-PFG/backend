<?php

/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 05/06/2017
 * Time: 23:23
 */
class Gestor extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->helper("form");
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('ModelPerfiles');
        $this->load->library('bcrypt');//cargamos la librería
        $this->load->library('session');
    }


    public function index()
    {
        $this->load->view('indexGestor');
    }

    public function SesionGestor(){
        $this->load->view('gestor/SesionGestor');
    }

    public function GestionarPerfiles(){
        $this->load->view('gestor/gestionarPerfiles');
    }

    public function GestionarPerfilesUsuarios(){
        $this->load->view('gestor/gestionarPerfilesUsuarios');
    }

    public function AsignarPerfilUsuario(){
        $this->load->view('gestor/asignarPerfilUsuario');
    }

    public function ModificarPerfil(){
        $this->load->view('gestor/modificarPerfil');
    }

    public function BorrarPerfil(){
        $this->load->view('gestor/borrarPerfil');
    }

    public function CambiarPerfilUsuario(){
        $this->load->view('gestor/cambiarPerfilUsuario');
    }

    public function accesoDenegado(){
        $this->load->view('errors/accesoDenegadoGestor');
    }

    public function InicioSesion(){
        $data = array(
            'usuario' => $this->input->post('usuario'),
            'pass' => $this->input->post('pass'),
        );

        if(empty($data['usuario']) || empty($data['pass'])){
            redirect(base_url()."index.php/Gestor?error=sesion");
        }else{
            $this->db->select('*')->from('gestion')->where('nombre', $data['usuario']);

            $query_session = $this->db->get();
            $resultado_ses = $query_session->row();

            if(password_verify($data['pass'],$resultado_ses->pass)){
                $this->session->set_userdata('gestor',1);
                redirect(base_url()."index.php/Gestor/SesionGestor");
            }
            else{
                redirect(base_url()."index.php/Gestor?error=sesion");
            }


        }

    }

    public function crearPerfil(){
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'descripcion' => $this->input->post('descripcion')
        );

        $correcto = $this->ModelPerfiles->insertPerfil($data);

        if($correcto == true){
            redirect(base_url()."index.php/Gestor/GestionarPerfiles");
        }

    }

    public function modificarPerfilExistente(){
        $data = array(
            'id' => $this->input->post('id'),
            'nombre' => $this->input->post('nombre'),
            'descripcion' => $this->input->post('descripcion')
        );

        $correcto = $this->ModelPerfiles->updatePerfil($data);

        if($correcto == true){
            redirect(base_url()."index.php/Gestor/GestionarPerfiles");
        }
    }

    public function borrarPerfilExistente(){

        $enviar = $this->input->post('enviar');
        $volver = $this->input->post('volver');

        if($enviar != ""){
            $data = array(
                'id' => $this->input->post('id')
            );

            $correcto = $this->ModelPerfiles->deletePerfil($data);

            if($correcto == true){
                redirect(base_url()."index.php/Gestor/GestionarPerfiles");
            }
        }
        else if($volver != ""){

            redirect(base_url()."index.php/Gestor/GestionarPerfiles");

        }

    }

    public function actionAsignarPerfilUsuario(){

        $data = array(
            'profesor' => $this->input->post('profesor'),
            'perfil' => $this->input->post('perfil')
        );

        $correcto = $this->ModelPerfiles->insertPerfilUsuarios($data);

        if($correcto == true){
            redirect(base_url()."index.php/Gestor/GestionarPerfilesUsuarios");
        }
        else{
            echo $correcto;
        }

    }

    public function cambiarUsuario(){
        $data_perfiles = array();


        foreach ($this->input->post('perfil') as $fila => $valor){

            $data_perfiles[] = $valor;

        }

        $data = array(
            'usuario' => $this->input->post('usuario')
        );

        $correcto = $this->ModelPerfiles->updatePerfilUsuarios($data,$data_perfiles);

        if($correcto == true){
            redirect(base_url()."index.php/Gestor/GestionarPerfilesUsuarios");
        }
        else{
            echo $correcto;
        }

    }

    public function BorrarUsuario(){
        $data = array(
            'usuario' => $this->input->get('usuario'),
            'perfil' => $this->input->get('perfil')
        );

        $correcto = $this->ModelPerfiles->deletePerfilUsuarios($data);

        if($correcto == true){
            redirect(base_url()."index.php/Gestor/GestionarPerfilesUsuarios");
        }
        else{
            echo $correcto;
        }
    }
}