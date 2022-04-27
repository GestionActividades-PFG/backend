<?php

/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 06/06/2017
 * Time: 1:03
 */
class ModelPerfiles extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function insertPerfil($data){
        $sql = "INSERT INTO perfiles VALUES (DEFAULT,'".$data['nombre']."','".$data['descripcion']."')";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function updatePerfil($data){
        $sql = "UPDATE perfiles SET nombrePerfil='".$data['nombre']."', descripcion = '".$data['descripcion']."' WHERE idPerfil = ".$data['id'];
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function deletePerfil($data){
        $sql = "DELETE FROM perfiles WHERE idPerfil = ".$data['id'];
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function insertPerfilUsuarios($data){
        $sql = "INSERT INTO perfiles_profesor VALUES(".$data['profesor'].",".$data['perfil'].")";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return $this->db->_error_number();
        }
    }

    public function updatePerfilUsuarios($data,$data_perfiles){
        $sql_borrar = "DELETE FROM perfiles_profesor WHERE idUsuario = ".$data['usuario'];
        $this->db->query($sql_borrar);

        foreach($data_perfiles as $fila => $valor){

            $sql_update = "INSERT INTO perfiles_profesor VALUES (".$data['usuario'].",".$valor.")";
            $query_update = $this->db->query($sql_update);

        }

        return true;
    }

    public function deletePerfilUsuarios($data){
        $sql = "DELETE FROM perfiles_profesor WHERE idUsuario = ".$data['usuario']." AND idPerfil = ".$data['perfil'];
        $query = $this->db->query($sql);
        if($query){
            return true;
        }
        else{
            return $this->db->_error_number();
        }
    }
}