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
        <?php

        ?>
        <div class="col-md-6"><img src="<?= base_url()?>assets/imagenes/logotipo.png"> </div>
        <div class="col-md-6"><h1>ACTIVIDADES</h1></div>
        <div class="col-md-12">
            <p>Hemos detectado de que no hay ningun usuario gestor en la base de datos. Y es necesario uno para poder administrar permisos sobre esta aplicacion. Por favor, rellene los campos para crear el usuario gestor.</p>
            <?=form_open("Instalacion/crearUsuarioGestion")?>
            <div>
                <label>Nombre de usuario</label>
                <?php
                echo form_input('nombre','');
                ?>
            </div>
            <div>
                <label>Contraseña</label>
                <?php
                echo form_password('pass','');
                ?>
            </div>
            <div>
                <label>Confirmar contraseña</label>
                <?php
                echo form_password('pass2','');
                ?>
            </div>
            <div>
                <?php
                echo form_submit('enviar','Crear usuario de gestion');
                ?>
            </div>
            <div align="left">
                <p>Progreso: 80%</p>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
            </div>


            <?=form_close()?>
        </div>

    </div>
    </body>
</html>