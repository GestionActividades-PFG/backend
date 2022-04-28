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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?= base_url()?>assets/js/formularioTiposPerfil.js"></script>
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
                <?= form_open('Gestor/modificarPerfilExistente')?>
                <?php
                    $sql_perfil = "SELECT * FROM perfiles WHERE idPerfil = ".$_GET['perfil'];
                    $query_perfil = $this->db->query($sql_perfil);
                    $fila_perfil = $query_perfil->row();
                ?>
                <?php echo form_hidden('id',$_GET['perfil'])?>
                <label>Nombre abreviado (hasta 4 caracteres)</label>
                <?php echo form_input('nombre',$fila_perfil->nombrePerfil,'class="form-control" id="nombre_abreviado"')?>
                <label>Descripcion</label>
                <?php echo form_input('descripcion',$fila_perfil->descripcion,'class="form-control" id="descripcion"')?>
                <?php echo form_button('enviar','Modificar perfil','class="btn btn-success buttons-separator" id="boton_enviar"')?>
                <?= form_close()?>
            </div>

        </article>
    </div>
    <!-- /CUERPO DE LA PÁGINA -->
</div>

</body>
</html>