<?php

/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 20/05/2017
 * Time: 20:56
 */
class ModelActividades extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function insertActividad($data_actividad){

        $sql = "INSERT INTO `act_actividad` VALUES (NULL, '".$data_actividad['nombreActividad']."', '".$data_actividad['monitor']."', '".$data_actividad['sexo']."', '".$data_actividad['concurso']."', '".$data_actividad['bases']."', '".$data_actividad['fechaInicio']."', '".$data_actividad['fechaFin']."', '".$data_actividad['maxClase']."', '".$data_actividad['tipoAct']."', '".$data_actividad['momento']."');";
        $query = $this->db->query($sql);

        if($query){

            $sql_last_id = "SELECT max(idActividad) as ultimoId FROM act_actividad";
            $query_last_id = $this->db->query($sql_last_id);
            $fila_id = $query_last_id->row();

            $ultimo_id = $fila_id->ultimoId;

            //Para insertar NULL en campos no obligatorios
            $this->setCamposNull($data_actividad,$ultimo_id);

            return true;
        }
        else{
            return false;
        }
    }

    public function updateActividad($data_actividad){

        $sql = "UPDATE act_actividad SET nombreActividad = '".$data_actividad['nombreActividad']."', monitor = '".$data_actividad['monitor']."', sexo = '".$data_actividad['sexo']."', concurso = '".$data_actividad['concurso']."', fechaInicio = '".$data_actividad['fechaInicio']."', fechaFin = '".$data_actividad['fechaFin']."', maxClase = ".$data_actividad['maxClase'].", tipoAct = '".$data_actividad['tipoAct']."', momento = ".$data_actividad['momento']."  WHERE idActividad = ".$data_actividad['idActividad'];
        $query = $this->db->query($sql);

        if($query){

            $sql_borrar_grupo = "DELETE FROM act_grupo WHERE idActividad = ".$data_actividad["idActividad"];
            $this->db->query($sql_borrar_grupo);

            if($data_actividad['bases'] != "")
            {
                $sql_monitor = "UPDATE act_actividad SET urlBases = '".$data_actividad['bases']."' WHERE idActividad = ".$data_actividad['idActividad'];
                $query_bases = $this->db->query($sql_monitor);
                if($query_bases){
                    return true;
                }
                else{
                    return false;
                }
            }
            return true;
        }
        else{
            return false;
        }


    }

    public function deleteActividad($data){
        $sql = "DELETE FROM act_actividad WHERE idActividad = ".$data['actividad'];
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function insertActividadGrupo($data_actividad){
        $sql = "INSERT INTO `act_actividad` VALUES (NULL, '".$data_actividad['nombreActividad']."', '".$data_actividad['monitor']."', '".$data_actividad['sexo']."', '".$data_actividad['concurso']."', '".$data_actividad['bases']."', '".$data_actividad['fechaInicio']."', '".$data_actividad['fechaFin']."', '".$data_actividad['maxClase']."', '".$data_actividad['tipoAct']."', '".$data_actividad['momento']."');";
        $query = $this->db->query($sql);

        if($query){

            if($data_actividad['tipoAct']=="G"){

                $sql_last_id = "SELECT max(idActividad) as ultimoId FROM act_actividad";
                $query_last_id = $this->db->query($sql_last_id);
                $fila_id = $query_last_id->row();

                $ultimo_id = $fila_id->ultimoId;

                $sql_grupo = " INSERT INTO act_grupo VALUES (".$ultimo_id.",'".$data_actividad['alumnos_seccion']."')";
                $query_grupo = $this->db->query($sql_grupo);
            }

            $this->setCamposNull($data_actividad,$ultimo_id);

            return true;
        }
        else{
            return false;
        }
    }

    public function updateActividadGrupo($data_actividad){
        $sql = "UPDATE act_actividad SET nombreActividad = '".$data_actividad['nombreActividad']."', monitor = '".$data_actividad['monitor']."', sexo = '".$data_actividad['sexo']."', concurso = '".$data_actividad['concurso']."', fechaInicio = '".$data_actividad['fechaInicio']."', fechaFin = '".$data_actividad['fechaFin']."', maxClase = ".$data_actividad['maxClase'].", tipoAct = '".$data_actividad['tipoAct']."', momento = ".$data_actividad['momento']."  WHERE idActividad = ".$data_actividad['idActividad'];
        $query = $this->db->query($sql);

        if($query){

            $sql_borrar_individual = "DELETE FROM act_individual_al WHERE idActividad = ".$data_actividad["idActividad"];
            $this->db->query($sql_borrar_individual);

            $sql_existe_act_grupo = "SELECT * FROM act_grupo WHERE idActividad = ".$data_actividad["idActividad"];
            $query_act = $this->db->query($sql_existe_act_grupo);

            if($query_act->num_rows()){
                $fila_act = $query_act->row();
                $sql_update = "UPDATE act_grupo SET alumnos = '".$data_actividad["alumnos_seccion"]."' WHERE idActividad = ".$fila_act->idActividad;
                $this->db->query($sql_update);
            }
            else{
                $sql_insert = "INSERT INTO act_grupo VALUES (".$data_actividad["idActividad"].",'".$data_actividad["alumnos_seccion"]."')";
                $this->db->query($sql_insert);
            }

            $this->setCamposNullUpdate($data_actividad,$data_actividad['idActividad']);

            if($data_actividad['bases'] != "")
            {
                $sql_monitor = "UPDATE act_actividad SET urlBases = '".$data_actividad['bases']."' WHERE idActividad = ".$data_actividad['idActividad'];
                $query_bases = $this->db->query($sql_monitor);
                if($query_bases){

                    return true;

                }
                else{
                    return false;
                }

            }
            if($data_actividad['alumnos_seccion'] == "N"){

                $sql_buscar_num = "SELECT * FROM act_insc_grupo WHERE idActividad = ".$data_actividad['idActividad'];
                $query_buscar_num = $this->db->query($sql_buscar_num);

                if($query_buscar_num){

                    foreach($query_buscar_num->result() as $fila_num){
                        $sql_borrar_alumnos = "DELETE FROM act_detalle_al_grupo WHERE numGrupo = ".$fila_num->numGrupo;
                        $query_borrar_alumnos = $this->db->query($sql_borrar_alumnos);

                        $sql_borrar_num = "DELETE FROM act_insc_grupo WHERE numGrupo = ".$fila_num->numGrupo;
                        $query_borrar_num = $this->db->query($sql_borrar_num);
                    }

                    $sql_update_grupo_alumnos = "UPDATE act_grupo SET alumnos ='".$data_actividad['alumnos_seccion']."' WHERE idActividad = ".$data_actividad['idActividad'];
                    $query_update_grupo_alumnos = $this->db->query($sql_update_grupo_alumnos);

                }
            }
            else{
                $sql_update_grupo_alumnos = "UPDATE act_grupo SET alumnos ='".$data_actividad['alumnos_seccion']."' WHERE idActividad = ".$data_actividad['idActividad'];
                $query_update_grupo_alumnos = $this->db->query($sql_update_grupo_alumnos);
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function insertActividadCategoria($data){
        $sql_validar = "SELECT count(*) as existe FROM act_actividad_cat WHERE idActividad = ".$data['idActividad']." AND idCategoria LIKE '".$data['idCategoria']."'";
        $query_validar = $this->db->query($sql_validar);

        $fila = $query_validar->row();

        if($fila->existe>0){
            return false;
        }
        else{
            $sql = "INSERT INTO act_actividad_cat VALUES (".$data['idActividad'].",'".$data['idCategoria']."')";
            $query = $this->db->query($sql);
            if($query){
                return true;
            }
            else{
                return false;
            }
        }

    }

    public function updateActividadCategoria($data,$data_categorias){

        //Borra las antiguas asignaciones
        $sql_borrar_act_cat = "DELETE FROM act_actividad_cat WHERE idActividad = ".$data["actividad"];
        $this->db->query($sql_borrar_act_cat);

        //Inserta las nuevas asignaciones
        foreach($data_categorias as $fila => $valor){
            $sql_add = "INSERT INTO act_actividad_cat VALUES (".$data["actividad"].",'".$valor."')";
            $this->db->query($sql_add);
        }

        //Borra las inscripciones
        $sql_borrar_alumnos_i = "DELETE FROM act_individual_al WHERE idActividad = ".$data["actividad"];
        $this->db->query($sql_borrar_alumnos_i);

        $sql_borrar_alumnos_g = "DELETE FROM act_insc_grupo WHERE idActividad = ".$data["actividad"];
        $this->db->query($sql_borrar_alumnos_g);

        redirect(base_url()."index.php/Coordinador/menuAsignarActividades?correcto=1");
    }

    //Si algun valor no requerido esta vacio, lo deja a NULL en la base de datos.
    public function setCamposNull($data_actividad,$ultimo_id){

        if($data_actividad['monitor'] == ""){

            $sql_monitor = "UPDATE act_actividad SET monitor = NULL WHERE idActividad = ".$ultimo_id;
            $query_monitor = $this->db->query($sql_monitor);

        }
        if($data_actividad['sexo'] == ""){

            $sql_monitor = "UPDATE act_actividad SET sexo = NULL WHERE idActividad = ".$ultimo_id;
            $query_sexo = $this->db->query($sql_monitor);

        }
        if($data_actividad['bases'] == ""){

            $sql_monitor = "UPDATE act_actividad SET urlBases = NULL WHERE idActividad = ".$ultimo_id;
            $query_bases = $this->db->query($sql_monitor);

        }

    }

    //Si algun valor no requerido esta vacio, lo deja a NULL en la base de datos.
    public function setCamposNullUpdate($data_actividad,$ultimo_id){

        if($data_actividad['monitor'] == ""){

            $sql_monitor = "UPDATE act_actividad SET monitor = NULL WHERE idActividad = ".$ultimo_id;
            $query_monitor = $this->db->query($sql_monitor);

        }
        if($data_actividad['sexo'] == ""){

            $sql_monitor = "UPDATE act_actividad SET sexo = NULL WHERE idActividad = ".$ultimo_id;
            $query_sexo = $this->db->query($sql_monitor);

        }

    }
}