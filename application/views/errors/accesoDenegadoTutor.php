<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Acceso denegado</title>
    <link type="text/css" href="<?= base_url()?>assets/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="<?= base_url()?>assets/css/comun.css" rel="stylesheet">
    <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
<div id="contenedor" class="row container text-center " >
    <div class="col-md-6"><img src="<?= base_url()?>assets/imagenes/logotipo.png"> </div>
    <div class="col-md-6"><h1>ERROR</h1></div>
    <div class="col-md-12">
        <div class="alert alert-danger">
            <p>No tienes permiso para acceder como Tutor. Para obtener permiso, consulte con el administrador del programa</p>
        </div>
    </div>

</div>
</body>
</html>