<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio Sesión</title>
    <link type="text/css" href="<?= base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="<?= base_url()?>assets/css/comun.css" rel="stylesheet">
    <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
<div id="contenedor" class="row container text-center " >
    <div class="col-md-6"><img src="<?= base_url()?>assets/imagenes/logotipo.png"> </div>
    <div class="col-md-6"><h1>INSTALACIÓN</h1></div>
    <div class="col-md-12">
        <h3>Creación de datos de ejemplo</h3>
        <div>
            <p>Se va a hacer una inserción de datos de ejemplo para tí...</p>
            <a href="<?php echo base_url()?>index.php/Instalacion/Instalacion3" class="btn btn-success">Siguiente</a>
            <div>
                <p>Progreso: 60%</p>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

</div>
</body>
</html>