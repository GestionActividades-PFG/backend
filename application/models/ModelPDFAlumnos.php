<?php

/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 31/05/2017
 * Time: 23:07
 */
class ModelPDFAlumnos extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('pdf');
    }

    public function generarPDFCoordinador($data){

            $pdf = new pdf('P','mm','A4',true,'UTF-8',false);

            $sql_momento = "SELECT * FROM act_momento WHERE idMomento = ".$data['momento'];
            $query_momento = $this->db->query($sql_momento);
            $fila_momento = $query_momento->row();

            $pdf->SetTitle('Alumnos inscritos a '.$fila_momento->nombreMomento);
            $pdf->SetHeaderMargin(30);
            $pdf->SetTopMargin(20);
            $pdf->setFooterMargin(20);
            $pdf->SetAutoPageBreak(true);


            $cabecera = "<h2>".$fila_momento->nombreMomento."</h2><br>";

            $pdf->AddPage();
            $pdf->SetDisplayMode('real', 'default');
            $pdf->writeHTML($cabecera,true, false, true, false, '');

            $sql_categoria = "SELECT * FROM act_categorias";
            $query_categoria = $this->db->query($sql_categoria);

            foreach($query_categoria->result() as $fila_categoria){


                $html = "<p>Categoria ".$fila_categoria->idCategoria.":</p>";
                $html .= "<br>";
                $pdf->writeHTML($html,true, false, true, false, '');

                $sql_sacar_act_cat = "SELECT DISTINCT *
                                        FROM act_momento INNER JOIN act_actividad
                                            ON act_momento.idMomento=act_actividad.momento
                                        INNER JOIN act_actividad_cat
                                            ON act_actividad.idActividad=act_actividad_cat.idActividad
                                    WHERE act_momento.idMomento = ".$fila_momento->idMomento." AND act_actividad_cat.idCategoria LIKE '".$fila_categoria->idCategoria."'";

                $query_sacar_act_cat = $this->db->query($sql_sacar_act_cat);

                if(!$query_sacar_act_cat->num_rows()){
                    $html = "<p>No hay actividades creadas para la categoria.</p>";
                    $pdf->writeHTML($html,true, false, true, false, '');
                    $pdf->AddPage();
                }

                foreach($query_sacar_act_cat->result() as $fila_actividad){

                    if($fila_actividad->sexo == "M"){
                        $html = "<p>".$fila_actividad->nombreActividad." - Masculino</p>";
                    }
                    else if($fila_actividad->sexo == "F"){
                        $html = "<p>".$fila_actividad->nombreActividad." - Femenino</p>";
                    }
                    else{
                        $html = "<p>".$fila_actividad->nombreActividad."</p>";
                    }


                    if($fila_actividad->tipoAct=='G'){

                        $sql_tipo_grupo = "SELECT * FROM act_grupo WHERE idActividad =".$fila_actividad->idActividad;
                        $query_tipo_grupo = $this->db->query($sql_tipo_grupo);
                        $fila_tipo_grupo = $query_tipo_grupo->row();

                        if($fila_tipo_grupo->alumnos == "S"){

                            $sql_cursos = "SELECT * FROM act_cursos WHERE idCategoria LIKE '".$fila_categoria->idCategoria."'";
                            $query_cursos = $this->db->query($sql_cursos);

                            foreach($query_cursos->result() as $fila_cursos){

                                $sql_sec = "SELECT * FROM secciones WHERE codCurso = ".$fila_cursos->codCurso;
                                $query_sec = $this->db->query($sql_sec);

                                foreach($query_sec->result() as $fila_sec){

                                    $sql_num_grupo = "SELECT * FROM act_insc_grupo WHERE idActividad = ".$fila_actividad->idActividad." AND idSeccion LIKE '".$fila_sec->idSeccion."'";
                                    $query_num_grupo = $this->db->query($sql_num_grupo);

                                    if($query_num_grupo->num_rows()){
                                        foreach($query_num_grupo->result() as $fila_num_grupo){

                                            $sql_sacar_seccion_nombre = "SELECT * FROM secciones WHERE idSeccion LIKE '".$fila_num_grupo->idSeccion."'";
                                            $query_sacar_seccion_nombre = $this->db->query($sql_sacar_seccion_nombre);
                                            $fila_seccion_nombre = $query_sacar_seccion_nombre->row();

                                            $html .= "<p>".$fila_seccion_nombre->nombre."</p>";

                                            $sql_alumnos = "SELECT alumnos.nombreCompleto, secciones.nombre
                                                            FROM act_detalle_al_grupo INNER JOIN alumnos
                                                                ON act_detalle_al_grupo.nia=alumnos.nia
                                                            INNER JOIN secciones
                                                                ON alumnos.idSeccion=secciones.idSeccion
                                                        WHERE act_detalle_al_grupo.numGrupo = ".$fila_num_grupo->numGrupo;
                                            $query_alumnos = $this->db->query($sql_alumnos);

                                            foreach($query_alumnos->result() as $fila_alumno){
                                                $html .= "<p>".$fila_alumno->nombreCompleto."</p>";

                                            }
                                        }
                                    }
                                    else{
                                        $html .= "<p>No hay alumnos inscritos en esta actividad</p>";
                                    }



                                }
                            }
                        }
                        else{
                            $html .= "<p>Secciones apuntados a esta actividad: </p>";
                            $sql_cursos = "SELECT * FROM act_cursos WHERE idCategoria LIKE '".$fila_categoria->idCategoria."'";
                            $query_cursos = $this->db->query($sql_cursos);
                            foreach($query_cursos->result() as $fila_cursos){

                                $sql_sec = "SELECT * FROM secciones WHERE codCurso = ".$fila_cursos->codCurso;
                                $query_sec = $this->db->query($sql_sec);

                                foreach($query_sec->result() as $fila_sec){

                                    $sql_num_grupo = "SELECT * FROM act_insc_grupo WHERE idActividad = ".$fila_actividad->idActividad." AND idSeccion LIKE '".$fila_sec->idSeccion."'";
                                    $query_num_grupo = $this->db->query($sql_num_grupo);
                                    if($query_num_grupo->num_rows()){
                                        foreach($query_num_grupo->result() as $fila_num_grupo){

                                            $sql_seccion = "SELECT * FROM secciones WHERE idSeccion LIKE '".$fila_sec->idSeccion."'";
                                            $query_seccion = $this->db->query($sql_seccion);

                                            foreach($query_seccion->result() as $fila_seccion_grupo){

                                                $html .= "<p>".$fila_seccion_grupo->nombre."</p>";

                                            }

                                        }
                                    }
                                    else{
                                        $html .= "<p>No hay ninguna seccion apuntada</p>";
                                    }



                                }

                            }
                        }

                    }
                    else{
                        $sql_cursos = "SELECT * FROM act_cursos WHERE idCategoria LIKE '".$fila_categoria->idCategoria."'";
                        $query_cursos = $this->db->query($sql_cursos);




                        foreach($query_cursos->result() as $fila_cursos){

                            $sql_sec = "SELECT * FROM secciones WHERE codCurso = ".$fila_cursos->codCurso;
                            $query_sec = $this->db->query($sql_sec);


                            foreach ($query_sec->result() as $fila_sec){
                                $sql_alumnos_apuntados = "SELECT alumnos.nombreCompleto
                                                        FROM act_individual_al INNER JOIN alumnos
                                                            ON act_individual_al.NIA=alumnos.nia
                                                    WHERE alumnos.idSeccion = '".$fila_sec->idSeccion."' AND idActividad = ".$fila_actividad->idActividad."";
                                $query_apuntados = $this->db->query($sql_alumnos_apuntados);


                                $html .= "<p>".$fila_sec->nombre."</p>";

                                if($query_apuntados->num_rows()){
                                    foreach($query_apuntados->result() as $fila_apuntados){

                                        $html .= "<p>".$fila_apuntados->nombreCompleto."</p>";

                                    }
                                }
                                else{
                                    $html .= "<p>Esta seccion no ha apuntado ningun alumno en esta actividad.</p>";
                                }


                            }

                        }
                    }
                    $pdf->writeHTML($html,true, false, true, false, '');
                    $pdf->AddPage();

                }
            }
            $pdf->Output('alumnos_inscritos.pdf', 'I');

    }

    public function generarPDFTutor($data){

        $pdf = new pdf('P','mm','A4',true,'UTF-8',false);

        $sql_momento = "SELECT * FROM act_momento WHERE idMomento = ".$data['momento'];
        $query_momento = $this->db->query($sql_momento);
        $fila_momento = $query_momento->row();

        $pdf->SetTitle('Alumnos inscritos a '.$fila_momento->nombreMomento);
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);


        $cabecera = "<h2>".$fila_momento->nombreMomento."</h2><br>";

        $pdf->AddPage();
        $pdf->SetDisplayMode('real', 'default');
        $pdf->writeHTML($cabecera,true, false, true, false, '');

        $sql_actividad = "SELECT DISTINCT *
                            FROM act_actividad INNER JOIN act_actividad_cat
                                ON act_actividad.idActividad=act_actividad_cat.idActividad
                             INNER JOIN act_categorias
                                ON act_categorias.idCategoria=act_actividad_cat.idCategoria
                             INNER JOIN act_cursos
                                ON act_cursos.idCategoria=act_categorias.idCategoria
                                INNER JOIN secciones
                                ON act_cursos.codCurso=secciones.codCurso
                        WHERE momento = ".$fila_momento->idMomento." AND secciones.idSeccion LIKE '".$this->session->seccion."'";

        $query_actividad = $this->db->query($sql_actividad);

        foreach($query_actividad->result() as $fila_actividad){


            if($fila_actividad->sexo == "M"){
                $html = "<p>".$fila_actividad->nombreActividad." - Masculino</p>";
            }
            else if($fila_actividad->sexo == "F"){
                $html = "<p>".$fila_actividad->nombreActividad." - Femenino</p>";
            }
            else if($fila_actividad->sexo == NULL){
                $html = "<p>".$fila_actividad->nombreActividad."</p>";
            }
            $html .= "<ul>";
            if($fila_actividad->tipoAct == "G"){

                $sql_tipo_grupo = "SELECT * FROM act_grupo WHERE idActividad =".$fila_actividad->idActividad;
                $query_tipo_grupo = $this->db->query($sql_tipo_grupo);
                $fila_tipo_grupo = $query_tipo_grupo->row();

                if($fila_tipo_grupo->alumnos == "S"){

                    $sql_insc_grupo = "SELECT * FROM act_insc_grupo WHERE idActividad = ".$fila_actividad->idActividad." AND idSeccion = '".$this->session->seccion."'";
                    $query_insc_grupo = $this->db->query($sql_insc_grupo);
                    $fila_grupo = $query_insc_grupo->row();

                    if($query_insc_grupo->num_rows()){
                        $sql_alumnos = "SELECT * FROM act_detalle_al_grupo WHERE numGrupo = ".$fila_grupo->numGrupo;
                        $query_alumnos = $this->db->query($sql_alumnos);

                        if($query_alumnos->num_rows()){
                            foreach($query_alumnos->result() as $fila_alumnos){

                                $sql_alumno = "SELECT * FROM alumnos WHERE nia LIKE '".$fila_alumnos->nia."'";
                                $query_alumno = $this->db->query($sql_alumno);
                                $fila_alumno = $query_alumno->row();

                                $html .= '<li>'.$fila_alumno->nombreCompleto.'</li>';

                            }
                        }
                    }

                    else{
                        $html .= '<li>No hay alumnos apuntados a esta actividad.</li>';
                    }



                }
                else{

                    $sql_insc_grupo = "SELECT * FROM act_insc_grupo WHERE idActividad = ".$fila_actividad->idActividad." AND idSeccion = '".$this->session->seccion."'";
                    $query_insc_grupo = $this->db->query($sql_insc_grupo);

                    if($query_insc_grupo->num_rows()){

                        $html .= '<li>Esta seccion se ha apuntado a esta actividad</li>';

                    }
                    else{
                        $html .= '<li>Esta seccion no se ha apuntado a esta actividad</li>';
                    }

                }

            }
            else{

                $sql_sacar_alumnos = "SELECT * FROM alumnos WHERE idSeccion LIKE '".$this->session->seccion."'";
                $query_sacar_alumnos = $this->db->query($sql_sacar_alumnos);
                $alumnos_apuntados = 0;
                if($query_sacar_alumnos->num_rows()){
                    foreach($query_sacar_alumnos->result() as $fila_alumnos){

                        $sql_alumno = "SELECT * FROM act_individual_al INNER JOIN alumnos ON alumnos.nia=act_individual_al.NIA WHERE alumnos.NIA LIKE '".$fila_alumnos->nia."' AND idActividad = ".$fila_actividad->idActividad;
                        $query_alumno = $this->db->query($sql_alumno);

                        if($query_alumno->num_rows()){

                            foreach($query_alumno->result() as $fila_al){

                                $html .= '<li>'.$fila_al->nombreCompleto.'</li>';
                                $alumnos_apuntados++;
                            }

                        }

                    }
                    if($alumnos_apuntados <= 0){
                        $html .= '<li>No hay alumnos apuntados a esta actividad</li>';
                    }
                }
                else{
                    $html .= '<li>No hay alumnos en tu seccion</li>';
                }


            }
            $html .= "</ul>";
            $html .= "<br/>";
            $pdf->writeHTML($html,true, false, true, false, '');

        }

        $pdf->Output('alumnos_inscritos.pdf', 'I');
    }
}