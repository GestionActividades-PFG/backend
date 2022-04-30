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
        $sql = "INSERT INTO act_momentos(nombre, fechaInicio_Inscripcion) VALUES ('$data_momento[momento]', DEFAULT)";
        if($this->db->query($sql)) return true;
        return false;
    }

    public function updateMomento($data_momento){
        $sql = "UPDATE act_momentos SET nombre = '$data_momento[nombre]' WHERE idMomento = $data_momento[idMomento]";
        $query = $this->db->query($sql);

        if($query) return true;
        return false;
    }

    public function deleteMomento($data_momento){
        $sql =  "DELETE FROM act_momentos WHERE idMomento = $data_momento[idMomento]";
        
        $query = $this->db->query($sql);

        if($query) return true;
        return false;
    }

    public function getMomentos() {
        $sql = "SELECT * FROM act_momentos";

        $query = $this->db->query($sql);

        if($query) return $query;
        return false;
    }
}