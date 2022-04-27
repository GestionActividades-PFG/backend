<!DOCTYPE html>
<?php
    if(!$this->session->has_userdata('gestor')){
        redirect(base_url().'index.php/Gestor/accesoDenegado');
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panel Gestor</title>
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
                <div id="title-cdi">GESTOR</div>
            </div>
            <div class="col-md-3 col-sm-3">

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
            <a href="<?php echo base_url()?>index.php/Gestor/SesionGestor" class="btn btn-success menu-buttons" role="button">Inicio</a>
            <a href="<?php echo base_url()?>index.php/Gestor/GestionarPerfiles" class="btn btn-success menu-buttons" role="button">Gestionar tipos de perfil</a>
            <a href="<?php echo base_url()?>index.php/Gestor/GestionarPerfilesUsuarios" class="btn btn-success menu-buttons" role="button">Asignar perfiles a usuarios</a>
            <a href="" class="btn btn-success menu-buttons" role="button">Cerrar sesión</a>
        </aside>
        <article class="col-md-9 articulo">
            <div class="form-group">
                <?= form_open('Gestor/cambiarUsuario')?>
                <h3>Cambiar tipo de perfil al usuario</h3>
                <?php
                    $sql_usuarios = "SELECT * FROM profesores WHERE idUsuario = ".$_GET["usuario"];
                    $query_profesores = $this->db->query($sql_usuarios);

                    $fila_profesores = $query_profesores->row();
                    echo '<h4>Cambiar tipos de perfil a '.$fila_profesores->nombre.'</h4>';

                    $sql_perfil = "SELECT * FROM perfiles ";
                    $query_perfil = $this->db->query($sql_perfil);


                    foreach($query_perfil->result() as $fila_perfiles){


                        $sql = "SELECT * FROM perfiles_profesor WHERE idUsuario = ".$_GET["usuario"]." AND idPerfil = ".$fila_perfiles->idPerfil;
                        $query = $this->db->query($sql);

                        if($query->num_rows()){
                            $fila = $query->row();
                            echo '<p>';
                            echo form_checkbox('perfil[]',$fila_perfiles->idPerfil,'true');
                            echo '<label>'.$fila_perfiles->nombrePerfil."</label>";
                            echo '</p>';
                        }
                        else{
                            $fila = $query->row();
                            echo '<p>';
                            echo form_checkbox('perfil[]',$fila_perfiles->idPerfil,'');
                            echo '<label>'.$fila_perfiles->nombrePerfil."</label>";
                            echo '</p>';
                        }
                    }
                    echo form_hidden('usuario',$_GET["usuario"]);
                    echo form_submit('enviar','Modificar','class="btn btn-success"');
                ?>

                <?= form_close()?>
            </div>
        </article>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>

</body>
</html>