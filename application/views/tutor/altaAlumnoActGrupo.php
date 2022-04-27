<!DOCTYPE html>
<?php
if(!$this->session->has_userdata('tutor')){
    redirect(base_url().'index.php/Tutor/accesoDenegado');
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inscribir alumnos - Tutor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link type="text/css" href="<?= base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="<?= base_url()?>assets/css/comun.css" rel="stylesheet">
    <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>

    </script>
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
                <div id="title-cdi">TUTOR</div>
            </div>
            <div class="col-md-3 col-sm-3">
                <?php
                    if($this->session->has_userdata('tutor')){
                        echo '<a class=" btn btn-primary btn-success" disabled="disabled">T</a>';
                    }
                    if($this->session->has_userdata('coordinador')){
                        echo '<a class=" btn btn-primary btn-success" href="'.base_url().'index.php/Coordinador" >C</a>';
                    }
                ?>
            </div>
        </div>
    </header>
    <!-- /CABECERA -->

    <!-- CUERPO DE LA PÁGINA -->
    <div class="row">
        <aside class="col-md-3">
            <!--
            *
            * Estos botones son simplemente de ejemplo
            *
            -->
            <a href="<?php echo base_url()?>index.php/Tutor" class="btn btn-success menu-buttons" role="button">Inicio</a>
            <a href="<?php echo base_url()?>index.php/Tutor/altaInscripcion" class="btn btn-success menu-buttons" role="button">Inscribir alumnos</a>
            <a href="<?php echo base_url()?>index.php/Tutor/mostrarAlumnosApuntados" class="btn btn-success menu-buttons" role="button">Ver alumnos que participan</a>
            <a href="<?php echo base_url()?>index.php/Tutor/CerrarSesion" class="btn btn-success menu-buttons" role="button">Cerrar sesión</a>
        </aside>
        <article class="col-md-9 articulo">
            <h2>Inscribir alumnos a la actividad</h2>
            <p>Esta actividad es de grupo, asi que puedes seleccionar que alumnos quieres que participen</p>
            <?php
                //Mensajes de error
                if(isset($_GET['error'])){
                    echo '<div id="error_alta" class="alert alert-danger">';
                    if($_GET['error']==1){
                        echo '<p>ERROR: Ya esta apuntado ese alumno a la actividad.</p>';
                    }
                    if($_GET['error']==2){
                        echo '<p>ERROR: Ya no se puede apuntar mas alumnos de tu seccion a esa actividad.</p>';
                    }
                    if($_GET['error']==3){
                        echo '<p>ERROR: Tu seccion ya se ha apuntado a esa actividad.</p>';
                    }
                    if($_GET['error']==4){
                        echo '<p>ERROR: Alguno/s de tus alumnos que has seleccionado ya estan inscritos a esa actividad.</p>';
                    }
                    if($_GET['error']==5){
                        echo '<p>ERROR: Estás seleccionando mas alumnos de los que pide de maximo la actividad.</p>';
                    }
                    if($_GET['error']==6){
                        echo '<p>ERROR: El alumno no tiene el mismo sexo que pide la actividad.</p>';
                    }
                    echo'</div>';
                }

                $sql_existe = "SELECT * FROM act_insc_grupo WHERE idActividad = ".$_GET['actividad']." AND idSeccion = '".$_SESSION['seccion']."'";
                $query_existe = $this->db->query($sql_existe);

                if(!$query_existe->num_rows()){
                    echo '<div class="alert alert-warning">';
                    echo '<p>No hay alumnos inscritos en esta actividad</p>';
                    echo '</div>';
                }else{
                    $sql_num_grupo = "SELECT DISTINCT act_insc_grupo.numGrupo
                                    FROM act_insc_grupo INNER JOIN act_detalle_al_grupo
                                        ON act_detalle_al_grupo.numGrupo=act_insc_grupo.numGrupo
                                WHERE act_insc_grupo.idActividad = ".$_GET['actividad']." AND act_insc_grupo.idSeccion = '".$_SESSION['seccion']."'";

                    $query_num_grupo = $this->db->query($sql_num_grupo);

                    $fila_num = $query_num_grupo->row();

                    $sql_lista_alumnos = "SELECT alumnos.*
                                                FROM act_detalle_al_grupo INNER JOIN alumnos
                                                    ON alumnos.nia=act_detalle_al_grupo.nia
                                            WHERE numGrupo = ".$fila_num->numGrupo;

                    $query_apuntados = $this->db->query($sql_lista_alumnos);

                    if($query_apuntados->num_rows() > 0){
                        echo '<table class="table table-striped">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<td>Alumnos inscritos</td>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        foreach($query_apuntados->result() as $fila_ins){
                            echo '<tr>';
                            echo '<td>'.$fila_ins->nombreCompleto.'</td>';
                            echo '<td><a href="borrarAlumnoGrupo?usuario='.$fila_ins->nia.'&actividad='.$_GET['actividad'].'&maxClase='.$_GET['maxClase'].'&numGrupo='.$fila_num->numGrupo.'">Borrar</a></td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    }
                }


            ?>
            <?=form_open("Tutor/inscribirGrupoAlumnos",'id="formulario_select_act"',"")?>
            <div class="form-group">
                <h3>Selecciona a un alumno que quieres que vaya</h3>
                <?php



                    $sql_alumnos="SELECT * FROM alumnos WHERE idSeccion LIKE '".$_SESSION["seccion"]."'";
                    $query_alumnos=$this->db->query($sql_alumnos);

                    $alumnos = array();

                    if($query_alumnos->num_rows()){

                        foreach($query_alumnos->result() as $fila_alumnos){
                            $alumnos[$fila_alumnos->nia]=$fila_alumnos->nombreCompleto;
                        }

                        echo form_multiselect('alumno[]',$alumnos,0,'id="lista_alumnos" class="form-control"');
                    }else{
                        echo '<div class="alert alert-danger">';
                        echo '<p>No hay alumnos para inscribir en esta actividad</p>';
                        echo '</div>';
                    }


                    echo form_hidden('idActividad',$_GET['actividad']);
                    echo form_hidden('maxClase',$_GET['maxClase']);
                ?>
            </div>
            <div id="detalle_actividades">

            </div>
            <div id="enviar_actividad">
                <?php
                echo form_submit('enviar','Inscribir alumno','id="enviar_actividad" class="btn btn-success"');
                echo form_submit('volver','Volver a la lista de actividades','id="enviar_actividad" class="btn btn-success"');
                ?>
            </div>

            <?=form_close()?>
        </article>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>

</body>
</html>