<!DOCTYPE html>
<?php
if(!$this->session->has_userdata('tutor')){
    redirect(base_url().'index.php/Tutor/accesoDenegado');
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mostrar alumnos - Tutor</title>
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
            <h3>Ver alumnos que participan</h3>
            <?php

                if(isset($_GET["error"])){
                    echo '<div id="error_alta" class="alert alert-danger">';
                    if($_GET["error"] == "no_act"){
                        echo '<p>ERROR: No hay actividades en este momento. Habla con el coordinador de actividades.</p>';
                    }
                    if($_GET["error"] == "no_cat"){
                        echo '<p>ERROR: Tu seccion no esta asignado a un curso. Habla con el coordinador de actividades.</p>';
                    }
                    echo '</div>';
                }

                $sql = "SELECT * FROM act_momento";
                $query = $this->db->query($sql);

                echo form_open('Tutor/generarPDF');
                $momentos = array();

                if($query->num_rows()){
                    foreach($query->result() as $row_mom){
                        $momentos[$row_mom->idMomento] = $row_mom->nombreMomento;
                    }
                    echo '<div>';
                    echo '<p>Selecciona el momento en el que quieres mirar que actividades tienen alumnos.</p>';
                    echo form_dropdown('momento',$momentos,'','id="lista_momentos" class="form-control"');
                    echo '</div>';
                    echo '<div>';
                    echo form_submit('','Generar PDF','class="btn btn-success buttons-separator"');
                    echo '</div>';
                }
                else{
                    echo '<div id="error_alta" class="alert alert-danger">';
                    echo '<p>No hay momentos creados. Consulta con el coordinador de actividades.</p>';
                    echo '</div>';
                }


                echo form_close();
            ?>
        </article>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>

</body>
</html>