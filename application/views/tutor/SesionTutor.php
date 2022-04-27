<!DOCTYPE html>
<?php
    if(!$this->session->has_userdata('tutor')){
        redirect(base_url().'index.php/Tutor/accesoDenegado');
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panel Tutor</title>
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
            <p>
                Conectado como <?php echo $this->session->nombre;?>. Bienvenido a la aplicación.
            </p>
        </article>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>

</body>
</html>