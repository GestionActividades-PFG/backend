<!DOCTYPE html>
<?php
    if(!$this->session->has_userdata('coordinador')){
        redirect(base_url().'index.php/Coordinador/accesoDenegado');
    }
?>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <title>Asignar cursos a secciones - Actividades EVG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link type="text/css" href="<?= base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="<?= base_url()?>assets/css/comun.css" rel="stylesheet">
    <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
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
                                            echo '<a href="'.base_url().'/index.php/Coordinador/menuCrearActividad">Crear actividad</a>'
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'/index.php/Coordinador/menuModificarActividad">Gestionar actividades</a>'
                                            ?>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'/index.php/Coordinador/menuAsignarActividades">Asignar categoria a actividades</a>'
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            echo '<a href="'.base_url().'/index.php/Coordinador/mostrarAlumnosApuntados">Mirar alumnos inscritos</a>'
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
                                            echo '<a href="'.base_url().'/index.php/Coordinador/menuMomentos">Gestionar Momentos</a>'
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
                <div class="form-group">
                    <?=form_open('Coordinador/cambiarCursoSeccion')?>
                    <?php
                        $sql_seccion = "SELECT * FROM secciones WHERE idSeccion LIKE '".$_GET['seccion']."'";
                        $query_seccion = $this->db->query($sql_seccion);
                        $fila_seccion = $query_seccion->row();
                    ?>
                    <label>Asignando curso a la seccion: <?php echo $fila_seccion->nombre?></label>
                    <?php
                        echo form_hidden('seccion',$_GET['seccion']);

                        $sql_cursos = "SELECT * FROM act_cursos";
                        $query_cursos = $this->db->query($sql_cursos);

                        $cursos = array();

                        if($query_cursos->num_rows()){
                            foreach($query_cursos->result() as $fila){
                                $cursos[$fila->codCurso] = $fila->nombreCurso;
                            }
                            if(isset($_GET['curso'])){
                                echo form_dropdown('curso',$cursos,$_GET['curso'],'class="form-control"');
                            }
                            else{
                                echo form_dropdown('curso',$cursos,'','class="form-control"');
                            }
                            
                            echo form_submit('enviar','Asignar curso a sección','class="btn btn-success buttons-separator"');
                        }
                        else{
                            echo '<div class="alert alert-warning">';
                            echo '<p>No hay categorias creados. Crea uno rellenando la casilla y pulsa el boton "Crear categoria"</p>';
                            echo '</div>';
                        }


                    ?>
                    <?=form_close()?>
                </div>

            </div>
        </div>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>
</body>
</html>