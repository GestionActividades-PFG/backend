<?php

/**
 * Created by PhpStorm.
 * User: juan1
 * Date: 30/05/2017
 * Time: 1:26
 */
class ModelMomentos extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function insertMomento($data_momento){
        $sql = "INSERT INTO act_momento VALUES (DEFAULT, '".$data_momento['momento']."')";
        $query = $this->db->query($sql);
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    public function updateMomento($data_momento){
        $sql = "UPDATE act_momento SET nombreMomento = '".$data_momento['nombre']."' WHERE idMomento = ".$data_momento['idMomento'];
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
    }

    public function deleteMomento($data_momento){
        $sql =  "DELETE FROM act_momento WHERE idMomento = ".$data_momento['idMomento'];
        $query = $this->db->query($sql);
        if($query){
            return true;
        }
    }
}