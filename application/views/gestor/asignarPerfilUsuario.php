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
            <a href="<?php echo base_url()?>index.php/Sesion/cerrarSesion" class="btn btn-success menu-buttons" role="button">Cerrar sesión</a>
        </aside>
        <article class="col-md-9 articulo">
            <div class="form-group">
                <?= form_open('Gestor/actionAsignarPerfilUsuario')?>
                <h3>Asignar tipo de perfil al usuario</h3>
                <label>Usuario</label>
                <?php
                    $sql_usuarios = "SELECT * FROM profesores";
                    $query_profesores = $this->db->query($sql_usuarios);

                    $profesores = array();
                    if($query_profesores->num_rows()){
                        foreach($query_profesores->result() as $fila){
                            $profesores[$fila->idUsuario] = $fila->nombre;
                        }
                        echo form_dropdown('profesor',$profesores,'','class="form-control"');
                    }
                    else{
                        echo '<div class="alert alert-danger">';
                        echo '<p>No hay usuarios creados.</p>';
                        echo '</div>';
                    }


                    $sql_perfiles = "SELECT * FROM perfiles";
                    $query_perfiles = $this->db->query($sql_perfiles);

                    $perfiles = array();

                    echo '<label>Tipo de perfil</label>';

                    if($query_perfiles->num_rows()){
                        foreach($query_perfiles->result() as $fila){
                            $perfiles[$fila->idPerfil] = $fila->nombrePerfil;
                        }
                        echo form_dropdown('perfil',$perfiles,'','class="form-control"');
                    }
                    else{
                        echo '<div class="alert alert-danger">';
                        echo '<p>No hay tipos de perfiles creados.</p>';
                        echo '</div>';
                    }

                    if($query_perfiles->num_rows() && $query_profesores->num_rows()){
                        echo form_submit('enviar','Asignar tipo de perfil al usuario','class="btn btn-success buttons-separator"');
                    }
                    else{
                        echo form_submit('enviar','Asignar tipo de perfil al usuario','disabled class="btn btn-success buttons-separator"');
                    }
                ?>
                <?php

                ?>
                <?= form_close()?>
            </div>
        </article>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>

</body>
</html>