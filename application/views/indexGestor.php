<!DOCTYPE html>
<?php
    if(isset($_SESSION['nombre'])){
        session_destroy();
    }
?>
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
            $usuario = array(
                'name' => 'usuario'

            );
           $pass = array(
               'name' => 'pass',
               'type' => 'password'

           );
           ?>
           <div class="col-md-6"><img src="<?= base_url()?>assets/imagenes/logotipo.png"> </div>
           <div class="col-md-6"><h1>GESTOR</h1></div>
           <div class="col-md-12">
               <?php
                if(isset($_GET['error']) && $_GET['error'] == "sesion"){
                    echo '<div class="alert alert-danger">';
                    echo '<p>Error en el inicio de sesion. El usuario y/o la contraseña no coinciden.</p>';
                    echo '</div>';
                }
               ?>
                <?=form_open("Gestor/InicioSesion")?>
                   <div class="col-md-12 form-group">
                       <?= form_label('Usuario: ','usuario')?>
                       <?= form_input($usuario)?>
                   </div>
                   <div class="col-md-12 form-group">
                       <?= form_label('Contraseña: ','pass')?>
                       <?= form_input($pass)?>
                   </div>
                   <div class="col-md-12 espacios">
                       <?= form_submit('', 'Entrar','class="btn btn-success"')?>
                   </div>
                <?=form_close()?>
           </div>

       </div>
    </body>
</html>