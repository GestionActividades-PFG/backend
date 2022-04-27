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
            <h2>Inscribir alumnos a actividades</h2>
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
                        echo'</div>';
                    }

                if(isset($_GET['actSeccion'])){
                    echo '<div id="borrado" class="alert alert-success">';
                    if($_GET['actSeccion']=="S"){
                        echo '<p>Has borrado la inscripcion de tu seccion a la actividad con exito.</p>';
                    }
                    if($_GET['actSeccion']=="A"){
                        echo '<p>Tu seccion se ha apuntado a la actividad con éxito.</p>';
                    }

                    echo'</div>';
                }
                ?>


            <?=form_open("Tutor/prepararFormAlumnos",'id="formulario_select_act"',"")?>
            <div class="form-group">

                <h3>Selecciona una actividad</h3>
                <?php

                    //Validar act_actividades_cat


                    $sql_cat_id="SELECT act_categorias.idCategoria
                                    FROM act_categorias INNER JOIN act_cursos
                                    ON act_cursos.idCategoria=act_categorias.idCategoria
                                    INNER JOIN secciones
                                    ON secciones.codCurso=act_cursos.codCurso
                                    WHERE secciones.idSeccion LIKE '".$_SESSION['seccion']."'";

                    $query_cat_id = $this->db->query($sql_cat_id);

                    if($query_cat_id->num_rows()){
                        $ret = $query_cat_id->row();

                        $sql_cat = "SELECT DISTINCT act_actividad.idActividad, act_actividad.nombreActividad, act_actividad.sexo
                        FROM act_actividad INNER JOIN act_actividad_cat
                            ON act_actividad.idActividad=act_actividad_cat.idActividad
                            INNER JOIN act_categorias
                            ON act_categorias.idCategoria=act_actividad_cat.idCategoria
                            INNER JOIN act_cursos
                            ON act_categorias.idCategoria=act_cursos.idCategoria
                            INNER JOIN secciones
                            ON secciones.codCurso=act_cursos.codCurso
                        WHERE act_actividad_cat.idCategoria LIKE '".$ret->idCategoria."' AND (DATE(now()) BETWEEN fechaInicio AND fechaFin)";

                        $query_cat = $this->db->query($sql_cat);


                        $actividades = array();

                        $actividades[0]='Selecciona una actividad';

                        if($query_cat->num_rows()){
                            foreach ($query_cat->result() as $row_cat){

                                //Validar act_actividades

                                if(is_null($row_cat->sexo) ){
                                    $actividades[$row_cat->idActividad]=$row_cat->nombreActividad;
                                }
                                else
                                {
                                    if($row_cat->sexo=='M'){
                                        $actividades[$row_cat->idActividad]=$row_cat->nombreActividad.' - Masculino';
                                    }
                                    if($row_cat->sexo=='F'){
                                        $actividades[$row_cat->idActividad]=$row_cat->nombreActividad.' - Femenino';
                                    }

                                }


                            }
                            echo form_dropdown('actividad',$actividades,'NULL','id="lista_actividades" class="form-control"');
                        }
                        else{
                            echo '<div id="error_alta" class="alert alert-danger">';
                            echo '<p>ERROR: No hay actividades creadas. Habla con el coordinador de actividades.</p>';
                            echo '</div>';
                        }


                    }
                    else{
                        echo '<div id="error_alta" class="alert alert-danger">';
                        echo '<p>ERROR: Tu seccion no ha sido asignado a ningun curso, prueba a hablar con el coordinador de actividades.</p>';
                        echo '</div>';
                    }



                ?>
            </div>
            <div id="detalle_actividades">

            </div>
            <div id="enviar_actividad">
                <?php

                ?>
            </div>

            <?=form_close()?>
        </article>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>

</body>
</html>