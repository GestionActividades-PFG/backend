<!DOCTYPE html>
<?php
if(!$this->session->has_userdata('coordinador')){
    redirect(base_url().'index.php/Coordinador/accesoDenegado');
}
?>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <title>Asignar cursos a secciones - Actividades EVG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link type="text/css" href="<?= base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="<?= base_url()?>assets/css/comun.css" rel="stylesheet">
    <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <style>
        html{height:auto;}
        #ui-datepicker-div {display: none;}

    </style>
</head>
<body>

<div class="container caja">
    <!-- CABECERA -->
    <header>
        <div class="row vertical-align text-center">
            <div class="col-md-6 col-sm-6">
                <img class="img-responsive img-center" src="<?= base_url()?>assets/imagenes/logotipo.png"/>
            </div>
            <div class="col-md-3 col-sm-3">
                <div id="title-cdi">COORDINADOR DE ACTIVIDADES</div>
            </div>
            <div class="col-md-3 col-sm-3">
                <?php
                    if($this->session->has_userdata('tutor')){
                        echo '<a class=" btn btn-primary btn-success" href="'.base_url().'index.php/Tutor" ">T</a>';
                    }
                    if($this->session->has_userdata('coordinador')){
                        echo '<a class=" btn btn-primary btn-success" disabled="disabled">C</a>';
                    }
                ?>
            </div>
        </div>
    </header>
    <!-- /CABECERA -->
    <hr>
    <!-- CUERPO DE LA PÁGINA -->

    <div class="container "  >
        <div class="row " >
            <div class="col-sm-3 col-md-3 " >
                <div class="panel-group " id="accordion" >
                    <div class="panel panel-default">
                        <div class="panel-heading" >
                            <h4 class="panel-title ">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-book text-success"></span>Actividades</a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/menuCrearActividad">Crear actividad</a>'
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/menuModificarActividad">Gestionar actividades</a>'
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/menuAsignarActividades">Asignar categoria a actividades</a>'
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/mostrarAlumnosApuntados">Mirar alumnos inscritos</a>'
                                            ?>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-calendar text-success">
                                </span>Momentos</a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/menuMomentos">Gestionar Momentos</a>'
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><span class="glyphicon glyphicon-pencil text-success">
                                </span>Categorías</a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/gestionarCategorias">Gestionar categorias</a>';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-pencil text-success">
                                </span>Cursos</a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/menuMeterCurso">Crear curso</a>';
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/gestionarCursos">Gestionar cursos</a>';
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php/Coordinador/asignarCursosSecciones">Asignar cursos a secciones</a>';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-pencil text-success">
                                </span>Opciones</a>
                            </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'index.php">Cerrar sesion</a>';
                                            ?>
                                        </td>

                                    </tr>

                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="col-sm-8 col-md-8" id="cuerpo">
                <h3>Asignar cursos a secciones</h3>
                <?php

                    if(isset($_GET['correcto'])){

                        if($_GET['correcto'] == "1"){
                            echo '<div class="alert alert-success">';
                            echo '<p>Se ha asignado la asignación de curso con exito.</p>';
                            echo '</div>';
                        }
                        if($_GET['correcto'] == "2"){
                            echo '<div class="alert alert-success">';
                            echo '<p>Se ha borrado la asignación de curso con exito.</p>';
                            echo '</div>';
                        }

                    }

                    $sql_sec_cur = "SELECT secciones.*, act_cursos.nombreCurso
                                            FROM secciones LEFT JOIN act_cursos
                                                ON secciones.codCurso=act_cursos.codCurso";
                    $query = $this->db->query($sql_sec_cur);

                    if($query->num_rows()){
                        echo '<table class="table table-striped">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<td>Categoria</td>';
                        echo '<td></td>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach($query->result() as $fila){
                            echo '<tr>';
                            echo '<td>'.$fila->nombre.'</td>';
                            if($fila->codCurso == NULL){
                                echo '<td>No asignado</td>';
                                echo '<td colspan="2"><a href="'.base_url().'index.php/Coordinador/asignarCurSec?seccion='.$fila->idSeccion.'">Asignar</a></td>';
                            }
                            else{
                                echo '<td>'.$fila->nombreCurso.'</td>';
                                echo '<td><a href="'.base_url().'index.php/Coordinador/asignarCurSec?seccion='.$fila->idSeccion.'&curso='.$fila->codCurso.'">Modificar</a></td>';
                                echo '<td><a href="'.base_url().'index.php/Coordinador/quitarCursoSeccion?seccion='.$fila->idSeccion.'">Borrar</a></td>';
                            }
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    }
                    else{
                        echo '<div class="alert alert-warning">';
                        echo '<p>No hay secciones creadas.</p>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>
</body>
</html>