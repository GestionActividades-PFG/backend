<?php


class ModelCategorias extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function insertCategoria($data){

        $sql = "INSERT INTO act_categorias VALUES('".$data['idCategoria']."','".$data['nombre']."')";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return $this->db->_error_number();
        }

    }

    public function updateCategoria($data){

        $sql = "UPDATE act_categorias SET nombreCategoria = '".$data['categoria']."' WHERE idCategoria LIKE '".$data['id']."'";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }
        else{
            return false;
        }

    }

    public function deleteCategoria($data_categoria){

        $sql_cursos = "SELECT * FROM act_cursos WHERE idCategoria = '".$data_categoria['idCategoria']."'";
        $query_cursos = $this->db->query($sql_cursos);

        foreach($query_cursos->result() as $fila){
            $sql_deasignar = "UPDATE secciones SET codCurso = NULL WHERE codCurso = ".$fila->codCurso;
            $this->db->query($sql_deasignar);
        }

        $sql = "DELETE FROM act_categorias WHERE idCategoria LIKE '".$data_categoria['idCategoria']."'";
        $query = $this->db->query($sql);

        if($query){
            return true;
        }else{
            return false;
        }

    }

}