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
        <h3>Crear tablas</h3>
        <div class="alert alert-info">
            <p>En este proceso, se crearan las tablas en la base de datos necesarias para hacer funcionar el programa.</p>
        </div>
        <a href="<?php echo base_url()?>index.php/Instalacion/FileInstalation" class="btn btn-success">Siguiente</a>
        <div align="left">
            <p>Progreso: 0%</p>
        </div>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

</div>
</body>
</html>