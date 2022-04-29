<!DOCTYPE html>
<?php
    if(!$this->session->has_userdata('coordinador')){
        redirect(base_url().'index.php/Coordinador/accesoDenegado');
    }
?>
<html lang="en">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <title>Crear actividad - Actividades EVG</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link type="text/css" href="<?= base_url()?>assets/css/bootstrap.css" rel="stylesheet">
        <link type="text/css" href="<?= base_url()?>assets/css/comun.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="all"
              href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css"    />
        <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="<?= base_url()?>assets/jquery-ui/jquery-ui.css"></script>
        <script src="<?= base_url()?>assets/js/formularioActividad.js"></script>
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
                    <h3>Crear actividad</h3>
                    <div class="alert alert-info">
                        <p>Los campos con asterisco, son necesarios rellenarlos para crear la actividad</p>
                    </div>
                    <?=form_open_multipart("Coordinador/crearActividad")?>
                    <div class="form-group">
                        <div>
                            <label>Nombre de la actividad *</label>
                            <?php
                            echo form_input('nombreActividad','','class="form-control" id="campo_nombre"');
                            ?>
                        </div>
                        <div>
                            <label>Monitor</label>
                            <?php
                            echo form_input('monitor','','class="form-control"');
                            ?>
                        </div>
                        <div>
                            <label>Sexo</label>
                            <ul>
                                <?php
                                    echo '<li>';
                                    echo form_radio('sexo','M','','Masculino');
                                    echo '<span>Masculino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo form_radio('sexo','F','','Femenino');
                                    echo '<span>Femenino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo form_radio('sexo','','true','class="campo_concurso"');
                                    echo '<span>No especificar</span>';
                                    echo '</li>';
                                ?>
                            </ul>
                        </div>
                        <div>
                            <label>¿Va a ser un concurso? *</label>
                            <ul>
                                <?php
                                    echo '<li>';
                                    echo form_radio('concurso','S','','class="campo_concurso"');
                                    echo '<span class="radio-inline">Sí</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo form_radio('concurso','N','','class="campo_concurso"');
                                    echo '<span class="radio-inline">No</span>';
                                    echo '</li>';

                                ?>
                            </ul>
                        </div>
                        <div>
                            <label class="control-label">Subir archivo de las bases</label>
                            <?php
                            echo form_upload('bases','','class="form-control"');
                            ?>
                        </div>
                        <div>
                            <label>Fecha de inicio *</label>
                            <?php
                            echo form_input('fechaInicio','','class="form-control datepicker" data-provide="datepicker" id="fecha_inicio"');
                            ?>
                            <label>Fecha maxima *</label>
                            <?php
                            echo form_input('fechaFin','','class="form-control datepicker" data-provide="datepicker" id="fecha_fin"');
                            ?>
                        </div>
                        <div>
                            <label>Maximo de alumnos que pueden participar por clase *</label>
                            <?php
                            echo form_input('maxClase','','class="form-control" id="max_clase"');
                            ?>
                        </div>
                        <div>
                            <label>Tipo de actividad *</label>
                            <?php
                            echo '<span class="radio-inline">Individual</span>';
                            echo form_radio('tipoAct','I','','class="grupo_tipo_act" id="radio_individual"');
                            echo '<span class="radio-inline">Grupo</span>';
                            echo form_radio('tipoAct','G','','class="grupo_tipo_act" id="radio_grupo"');
                            ?>
                        </div>

                        <div id="alumnos_o_seccion">

                        </div>
                        <label>Momento en el que se celebra la actividad *</label>
                        <?php
                            $this->db->select('*')->from('act_momentos');
                            $query_momento = $this->db->get();

                            $momentos = array();

                            if($query_momento->num_rows()){
                                foreach($query_momento->result() as $row_mom){
                                    $momentos[$row_mom->idMomento] = $row_mom->nombreMomento;
                                }
                                echo form_dropdown('momento',$momentos,'','id="lista_momentos" class="form-control"');
                                echo '<br>';
                                echo form_button('crearActividad','Crear actividad','class="btn btn-success" id="boton_enviar"');
                            }
                            else{
                                echo '<div class="alert alert-danger">';
                                echo '<p>No puedes crear una actividad, si no tienes creada antes los momentos en los que se celebra la actividad</p>';
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