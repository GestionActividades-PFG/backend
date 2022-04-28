<?php
    /**
     * Created by PhpStorm.
     * User: juan1
     * Date: 16/05/2017
     * Time: 19:16
     */
    class Sesion extends CI_Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->load->database();
            $this->load->helper('url');
            $this->load->library('bcrypt');//cargamos la librería
            $this->load->library('session');

        }

        public function index()
        {
            $this->obtenerDatos();
        }

        function cerrarSesion() {
            session_start();

            $_SESSION = null;
            
            session_destroy();

            redirect(base_url()."index.php");
        }

        function obtenerDatos()
        {
            //Recogemos los datos del formulario
            $data = array(
                'usuario' => $this->input->post('usuario'),
                'pass' => $this->input->post('pass')
            );

            if(empty($data['usuario']) || empty($data['pass'])){
                //Si los campos de usuario y contraseña estan vacios
                redirect(base_url()."index.php?error=sesion");
            }
            else{
                //Buscamos el usuario en la base de datos
                $sql = "SELECT * FROM profesores WHERE usuario = ? ";

                //Obtenemos la fila
                $query_session = $this->db->query($sql, $data['usuario']);
                $resultado_ses = $query_session->row();

                if(password_verify($data['pass'], $resultado_ses->pass)){
                    //Si el usuario y la contraseña estan correctos
                    $this->session->set_userdata('idUsuario', $resultado_ses->idUsuario);
                    $this->session->set_userdata('nombre', $resultado_ses->nombre);

                    //Para saber si el usuario es el coordinador de actividades
                    $sql_perfiles = "SELECT * FROM perfiles_profesor INNER JOIN perfiles
                                    ON perfiles.idPerfil=perfiles_profesor.idPerfil WHERE idUsuario = ".$resultado_ses->idUsuario."";
                    $query_perfiles=$this->db->query($sql_perfiles);

                    foreach($query_perfiles->result() as $row_perfiles){
                        
                        if($row_perfiles->nombrePerfil == "C_AC" ){
                            //Si el usuario es el coordinador de actividades
                            $this->session->set_userdata('coordinador',1);
                        }
                    }

                    //Para saber si el usuario es tutor de una seccion
                    $sql_sacar_tutor = "SELECT * FROM profesores WHERE idUsuario = ".$resultado_ses->idUsuario;
                    $query_sacar_tutor = $this->db->query($sql_sacar_tutor);
                    $tutor = $query_sacar_tutor->row();

                    if($tutor->idUsuario != ""){
                        //Si el usuario es un tutor
                        $sql_id_seccion = "SELECT * FROM secciones WHERE tutor = ".$resultado_ses->idUsuario."";
                        $query_seccion = $this->db->query($sql_id_seccion);
                        $seccion = $query_seccion->row();

                        if($query_seccion->num_rows()){
                            $this->session->set_userdata('seccion',$seccion->idSeccion);
                            $this->session->set_userdata('tutor',1);
                        }
                    }

                    if($this->session->has_userdata('tutor')){
                        //Si el usuario tiene la variable de tutor, que sea redirigido al menu del tutor
                        redirect('Tutor');
                    }
                    else if($this->session->has_userdata('coordinador')) {
                        //Si el usuario tiene la variable de coordinador, que sea redirigido al menu del coordinador
                        redirect('Coordinador');
                    }
                    else{
                        redirect(base_url()."index.php?error=permisos");
                    }

                }
                else {
                    //Si el usuario y la contraseña no son correctos
                    redirect(base_url()."index.php?error=sesion");
                }
            }
        }
    }


?>