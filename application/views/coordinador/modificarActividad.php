<!DOCTYPE html>
<?php
    if(!$this->session->has_userdata('coordinador')){
        redirect(base_url().'index.php/Coordinador/accesoDenegado');
    }
?>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <title>Modificar actividad - Actividades EVG</title>
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
    <script>


    </script>
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
                <h3>Modificar actividad</h3>
                <?php
                    $sql = "SELECT * FROM act_actividades WHERE idActividad = ".$_GET['actividad'];
                    $query_actividad = $this->db->query($sql);
                    $fila = $query_actividad->row();
                ?>
                <form action="<?php echo base_url().'index.php/Coordinador/modificarActividadProceso'?>" method="POST" enctype="multipart/form-data">
                    <div class="alert alert-info">
                        <p>Los campos con asterisco, son necesarios rellenarlos para crear la actividad</p>
                    </div>
                    <div class="form-group">
                        <div>
                            <label>Nombre de la actividad*</label>
                            <input type="text" name="nombreActividad" id="campo_nombre" class="form-control" value="<?php echo $fila->nombreActividad ?>"/>
                        </div>
                        <div>
                            <label>Monitor</label>
                            <input type="text" name="monitor" class="form-control" value="<?php echo $fila->monitor ?>"/>
                        </div>
                        <div>
                            <label>Sexo</label>
                            <ul>

                                <?php
                                if($fila->sexo == "M"){
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value="M" checked="true"/>';
                                    echo '<span>Masculino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value="F"/>';
                                    echo '<span>Femenino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value=""/>';
                                    echo '<span>No especificar</span>';
                                    echo '</li>';
                                }
                                else if($fila->sexo == "F"){
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value="M"/>';
                                    echo '<span>Masculino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value="F" checked="true"/>';
                                    echo '<span>Femenino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value=""/>';
                                    echo '<span>No especificar</span>';
                                    echo '</li>';

                                }
                                else {
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value="M"/>';
                                    echo '<span>Masculino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value="F"/>';
                                    echo '<span>Femenino</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" name="sexo" value="" checked="true"/>';
                                    echo '<span>No especificar</span>';
                                    echo '</li>';
                                }

                                ?>

                            </ul>
                        </div>
                        <div>
                            <label>¿Va a ser un concurso?*</label>
                            <ul>
                                <?php
                                if($fila->concurso == "S"){
                                    echo '<li>';
                                    echo '<input type="radio" class="campo_concurso" name="concurso" checked="true" value="S"/>';
                                    echo '<span>Sí</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" class="campo_concurso" name="concurso" value="N"/>';
                                    echo '<span>No</span>';
                                    echo '</li>';



                                }
                                else{
                                    echo '<li>';
                                    echo '<input type="radio" class="campo_concurso" name="concurso" value="S"/>';
                                    echo '<span>Sí</span>';
                                    echo '</li>';
                                    echo '<li>';
                                    echo '<input type="radio" class="campo_concurso" name="concurso" checked="true" value="N"/>';
                                    echo '<span>No</span>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div>
                            <label>Subir archivo de las bases</label>
                            <input type="file" name="archivo" class="form-control"/>
                        </div>
                        <div>
                            <label>Fecha de inicio*</label>
                            <input type="text" name="fechaInicio" class="form-control datepicker" id="fecha_inicio" data-provide="datepicker" value="<?php echo $fila->fechaInicio ?>"/>
                            <label>Fecha máxima*</label>
                            <input type="text" name="fechaFin" class="form-control datepicker" id="fecha_fin" data-provide="datepicker" value="<?php echo $fila->fechaFin ?>"/>
                        </div>
                        <div>
                            <label>Max. clase*</label>
                            <input type="text" id="max_clase" name="maxClase" class="form-control" value="<?php echo $fila->maxClase;?>"/>
                        </div>
                        <div>
                            <label>Tipo de actividad*</label>
                            <?php
                                if($fila->tipoAct == "I"){
                                    echo '<span class="radio-inline">Individual</span>';
                                    echo '<input type="radio" name="tipoAct" class="grupo_tipo_act" value="I" checked="true" id="radio_individual"/>';
                                    echo '<span class="radio-inline">Grupo</span>';
                                    echo '<input type="radio" name="tipoAct" class="grupo_tipo_act" value="G" id="radio_grupo"/>';
                                }
                                else{
                                    echo '<span class="radio-inline">Individual</span>';
                                    echo '<input type="radio" name="tipoAct" class="grupo_tipo_act" value="I" id="radio_individual"/>';
                                    echo '<span class="radio-inline">Grupo</span>';
                                    echo '<input type="radio" name="tipoAct" class="grupo_tipo_act" value="G" checked="true" id="radio_grupo"/>';
                                }
                            ?>
                        </div>
                        <div id="alumnos_o_seccion">

                        </div>
                        <label>Momento en el que se celebra la actividad*</label>
                        <?php
                            $this->db->select('*')->from('act_momentos');
                            $query_momento = $this->db->get();

                            echo '<select name="momento" class="form-control">';
                            foreach($query_momento->result() as $row_mom){
                                if($row_mom->idMomento == $fila->momento){
                                    echo '<option value="'.$row_mom->idMomento.'" class="form-control" selected="selected">'.$row_mom->nombreMomento.'</option>';
                                }
                                else{
                                    echo '<option value="'.$row_mom->idMomento.'" class="form-control">'.$row_mom->nombreMomento.'</option>';
                                }

                            }
                            echo '</select>';


                        ?>
                        <input type="hidden" name="idActividad" value="<?php echo $_GET["actividad"]?>"/>
                        <br/>
                        <input type="button" value="Modificar actividad" class="btn btn-success" id="boton_enviar"/>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>
</body>
</html>