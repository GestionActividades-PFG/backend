<!DOCTYPE html>
<?php
    if(!$this->session->has_userdata('coordinador')){
        redirect(base_url().'index.php/Coordinador/accesoDenegado');
    }
?>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>Gestionar categorias - Actividades EVG</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link type="text/css" href="<?= base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="<?= base_url()?>assets/css/comun.css" rel="stylesheet">
    <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/js/formularioCategoria.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

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
                <h3>Gestionar categorias</h3>
                <?php

                    if(isset($_GET['correcto'])){

                        if($_GET['correcto'] == "1"){
                            echo '<div class="alert alert-success">';
                            echo '<p>Se ha creado la categoria con exito</p>';
                            echo '</div>';
                        }
                        if($_GET['correcto'] == "2"){
                            echo '<div class="alert alert-success">';
                            echo '<p>Se ha modificado la categoria con exito</p>';
                            echo '</div>';
                        }
                        if($_GET['correcto'] == "3"){
                            echo '<div class="alert alert-success">';
                            echo '<p>Se ha borrado la categoria con exito</p>';
                            echo '</div>';
                        }

                    }

                    if(isset($_GET['error'])){

                        if($_GET['error'] == "1"){
                            echo '<div class="alert alert-danger">';
                            echo '<p>ERROR: No se ha creado la categoria, intenteló de nuevo.</p>';
                            echo '</div>';
                        }
                        if($_GET['error'] == "duplicado"){
                            echo '<div class="alert alert-danger">';
                            echo '<p>ERROR: No se puede meter la misma letra de categoria mas de una vez.</p>';
                            echo '</div>';
                        }
                        if($_GET['error'] == "2"){
                            echo '<div class="alert alert-danger">';
                            echo '<p>ERROR: No se ha modificado la categoria, intenteló de nuevo.</p>';
                            echo '</div>';
                        }
                        if($_GET['error'] == "3"){
                            echo '<div class="alert alert-danger">';
                            echo '<p>ERROR: La letra de categoria debe de ser una letra.</p>';
                            echo '</div>';
                        }

                    }

                    $sql_categorias = "SELECT * FROM act_categorias";
                    $query_categorias = $this->db->query($sql_categorias);

                    if($query_categorias->num_rows()){
                        echo '<table class="table table-striped">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<td>Letra de la categoria</td>';
                        echo '<td>Nombre de la categoria</td>';
                        echo '<td></td>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach($query_categorias->result() as $fila_cat){
                            echo '<tr>';
                            echo '<td>'.$fila_cat->idCategoria.'</td>';
                            echo '<td>'.$fila_cat->nombreCategoria.'</td>';
                            echo '<td><a href="'.base_url().'index.php/Coordinador/modificarCategoria?categoria='.$fila_cat->idCategoria.'">Modificar</a></td>';
                            echo '<td><a href="'.base_url().'index.php/Coordinador/menuBorrarCategoria?categoria='.$fila_cat->idCategoria.'">Borrar</a></td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    }
                    else{
                        echo '<div class="alert alert-warning">';
                        echo '<p>No hay categorias creados. Crea uno rellenando la casilla y pulsa el boton "Crear categoria"</p>';
                        echo '</div>';
                    }
                ?>
                <?= form_open('Coordinador/crearCategoria')?>
                <div class="form-group">
                    <label>Letra de la categoria</label>
                    <?php
                    echo form_input('idCategoria','','class="form-control" id="label_letra"');
                    echo '<label>Nombre de la categoria</label>';
                    echo form_input('nombre','','class="form-control" id="label_nombre"');
                    echo form_button('enviar','Crear categoria','id="boton-crear" class="btn btn-success buttons-separator"');
                    ?>
                </div>

                <?= form_close()?>
            </div>
        </div>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>
</body>
</html>