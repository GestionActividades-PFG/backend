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
        $(document).ready(function(){
            $("#lista_actividades").change(function(){
                var actividad_sel = $("#lista_actividades").val();
                console.log(actividad_sel);
                $("#detalle_actividades").load("cargarDetalles",{'actividad': actividad_sel});
            })

            $("#formulario_select_act").submit(function(){
                if($("#detalle_actividades").html()==""){
                    alert("No puedes continuar si no has seleccionado antes la actividad");
                }
            });

        });
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

                <?php
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
                        echo '<p>ERROR: Alguno/s de tus alumnos ya esta inscrito a esa actividad.</p>';
                    }
                    if($_GET['error']==5){
                        echo '<p>ERROR: El sexo del alumno no es el válido.</p>';
                    }
                    echo '</div>';
                }
                ?>

            <div class="form-group">

                    <?php
                        $sql_alumnos_apuntados = "SELECT alumnos.nia,alumnos.nombreCompleto
                                                        FROM act_individual_al INNER JOIN alumnos
                                                            ON act_individual_al.NIA=alumnos.nia
                                                    WHERE alumnos.idSeccion = '".$_SESSION["seccion"]."' AND idActividad = ".$_GET['actividad']."";
                        $query_apuntados = $this->db->query($sql_alumnos_apuntados);



                        if($query_apuntados->num_rows() <= 0){
                            echo '<div class="alert alert-warning">';
                            echo '<p>No hay alumnos de tu seccion apuntados a esta actividad</p>';
                            echo '</div>';
                        }
                        else{
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
                                echo '<td><a href="borrarAlumnoIndividual?usuario='.$fila_ins->nia.'&actividad='.$_GET['actividad'].'&maxClase='.$_GET['maxClase'].'">Borrar</a></td>';
                                echo '</tr>';
                            }
                            echo '</tbody>';
                            echo '</table>';
                        }



                    ?>
                <h4>Apuntar a un alumno</h4>
                <?=form_open("Tutor/inscribirAlumnoIndividual",'id="formulario_select_act"',"");?>

                <?php

                    $sql_alumnos="SELECT * FROM alumnos WHERE idSeccion LIKE '".$_SESSION["seccion"]."' AND nia NOT IN (SELECT nia FROM act_individual_al WHERE idActividad = ".$_GET['actividad'].")";
                    $query_alumnos=$this->db->query($sql_alumnos);

                    $alumnos = array();

                    if($query_alumnos->num_rows()){
                        foreach($query_alumnos->result() as $fila_alumnos){
                            $alumnos[$fila_alumnos->nia]=$fila_alumnos->nombreCompleto;
                        }
                        echo form_dropdown('alumno',$alumnos,"",'id="lista_alumnos" class="form-control"');
                    }
                    else{
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