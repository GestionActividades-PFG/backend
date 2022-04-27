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
            $usuario = array(
                'name' => 'usuario'
            );
           $pass = array(
               'name' => 'pass',
               'type' => 'password'
           );
           ?>
           <div class="col-md-6"><img src="<?= base_url()?>assets/imagenes/logotipo.png"> </div>
           <div class="col-md-6"><h1>ACTIVIDADES</h1></div>
           <div class="col-md-12">
               <?php
                if(isset($_GET['error']) && $_GET['error'] == "sesion"){
                    echo '<div class="alert alert-danger">';
                    echo '<p>Error en el inicio de sesion. El usuario y/o la contraseña no coinciden.</p>';
                    echo '</div>';
                }
               if(isset($_GET['error']) && $_GET['error'] == "permisos"){
                   echo '<div class="alert alert-danger">';
                   echo '<p>Error en el inicio de sesion. No tienes permisos de tutor o coordinador, consulta con el gestor del programa.</p>';
                   echo '</div>';
               }
               ?>
                <?=form_open("Sesion")?>
                   <div class="col-md-12 form-group">
                       <?= form_label('Usuario: ','usuario','class="control-label col-md-5"')?>

                       <?= form_input($usuario)?>


                   </div>
                   <div class="col-md-12 form-group">
                       <?= form_label('Contraseña: ','pass','')?>

                       <?= form_input($pass)?>


                   </div>
                   <div class="col-md-12 espacios">
                       <?= form_submit('', 'Entrar','class="btn btn-success button-separator"')?>
                   </div>
                <?=form_close()?>
           </div>

       </div>
    </body>
</html>