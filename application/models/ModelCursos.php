<?php

/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 04/06/2017
 * Time: 0:52
 */
class ModelCursos extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function insertCurso($data){
        $sql = "INSERT INTO act_cursos VALUES (DEFAULT, '".$data['nombre']."','".$data['categoria']."')";
        $query = $this->db->query($sql);
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function updateCurso($data){

        $sql = "UPDATE act_cursos SET nombreCurso = '".$data['nombreCurso']."', idCategoria = '".$data['categoria']."' WHERE codCurso = ".$data['idCurso'];
        $query = $this->db->query($sql);
        if($query){
            return true;
        }
        else{
            return false;
        }

    }

    public function deleteCurso($data){

        $sql_sacar_alumnos = "SELECT alumnos.*
                                FROM alumnos INNER JOIN secciones
                                    ON secciones.idSeccion=alumnos.idSeccion
                                INNER JOIN act_cursos
                                    ON secciones.codCurso=act_cursos.codCurso
                            WHERE act_cursos.codCurso = ".$data['idCurso'];

        $query_alumnos = $this->db->query($sql_sacar_alumnos);

        foreach($query_alumnos->result() as $fila_alumnos){

            $sql_borrar_inscripciones_individual = "DELETE FROM act_individual_al WHERE NIA LIKE '".$fila_alumnos->nia."'";
            $this->db->query($sql_borrar_inscripciones_individual);

            $sql_borrar_inscripciones_grupo = "DELETE FROM act_detalle_al_grupo WHERE NIA LIKE '".$fila_alumnos->nia."'";
            $this->db->query($sql_borrar_inscripciones_grupo);

        }

        $sql_sacar_secciones = "SELECT secciones.*
                                        FROM secciones INNER JOIN act_cursos
                                            ON secciones.codCurso=act_cursos.codCurso
                                    WHERE act_cursos.codCurso = ".$data['idCurso'];

        $query_secciones = $this->db->query($sql_sacar_secciones);

        foreach($query_secciones->result() as $fila_sec){

            $sql_borrar_num_grupos = "DELETE FROM act_insc_grupo WHERE idSeccion LIKE '".$fila_sec->idSeccion."'";
            $this->db->query($sql_borrar_num_grupos);

        }

        $sql_deasignar_curso = "UPDATE secciones SET codCurso = NULL WHERE codCurso = ".$data['idCurso'];
        $this->db->query($sql_deasignar_curso);

        $sql_borrar_curso = "DELETE FROM act_cursos WHERE codCurso = ".$data['idCurso'];
        $this->db->query($sql_borrar_curso);


    }

    public function insertCursoSeccion($data){

        $sql = "UPDATE secciones SET codCurso = ".$data['curso']." WHERE idSeccion LIKE '".$data['seccion']."'";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return false;
        }



    }

    public function deleteCursoSeccion($data){

        $sql = "UPDATE secciones SET codCurso = NULL WHERE idSeccion LIKE '".$data['seccion']."'";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return false;
        }

    }

}