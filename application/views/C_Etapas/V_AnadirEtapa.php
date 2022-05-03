<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Añadir Etapa</title>
		<script>
			// Tendrá que haber en esta función tantas líneas como distintos id de texto de ajax haya en la página.
			function pruebaInicial()
			{
				buscarCSU('<?php echo base_url() ;?>', 'Etapas', '', 'codEtapa', 'infoAjax', 'codEtapa');
				buscarCSU('<?php echo base_url() ;?>', 'Etapas', '', 'nombre', 'infoAjax2', 'nombre');
			}
		</script>
	</head>
	<body onload="pruebaInicial()">
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'  ;?>
						<h3>Gestión EVG - Añadir Etapas</h3>
					</div>
					<div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-6">
						<?php echo "<button onclick=\"location.href ='" . base_url() . "main'\" id=\"icon-grid\" class=\"btn mr-2\" data-toggle=\"popover\" data-content=\"Grid Aplicaciones\"><i class=\"fas fa-th\"></i></button>"; ?>
						<?php
							$picture = $this -> session -> userdata('profile_pic');
							echo '<img id="profile_picture" src="'.$picture.'" alt="Google Profile Picture" class="img-fluid rounded-circle"/>';
						?>
						<?php echo "<button onclick=\"confirmar('¿Seguro que quieres cerrar sesión?','".base_url()."Auth/logout', 'Cerrar Sesión', 'Cancelar', 'Cerrar')\" data-toggle=\"modal\" data-target=\"#myModal\" id=\"icon-logout\" class=\"btn\"><i class=\"fa fa-sign-out-alt\"></i></button>" ;?>
					</div>
				</header>
			</div>
			<div class="row">
				<?php include_once('application/views/Plantilla/asideGestor.php') ?>
				<div class="general">
					<button type="button" id="sidebarCollapse" class="btn btn-sidebar">
						<i class="fas fa-bars" data-toggle="popover" data-content="Mostrar menú"></i>
						<i class="fas fa-times" data-toggle="popover" data-content="Ocultar menú"></i>
					</button>
					<?php echo "<button onclick=\"location.href ='" . base_url() . "stages'\" class=\"btn btn-secondary\" data-toggle=\"popover\" data-content=\"Volver atrás\"><i class=\"fas fa-arrow-left\"></i></button>"; ?>
					<div class="gestion-apps animate__animated animate__zoomIn">
						<?php
							$codEtapa = array
							(
									'id'=>'codEtapa',
									'name'=>'codEtapa',
									'oninput'=>"buscarCSU('".base_url()."', 'Etapas', this.value, 'codEtapa', 'infoAjax', 'codEtapa', 'Ya existe una etapa con el código ')",
									'placeholder'=>'Código',
									'required'=>'required',
									'class'=>'form-control'
							);

							$nombre = array
							(
									'id'=>'nombre',
									'name'=>'nombre',
									'oninput'=>"buscarCSU('".base_url()."', 'Etapas', this.value, 'nombre', 'infoAjax2', 'nombre', 'Ya existe una etapa con el nombre ')",
									'placeholder'=>'Nombre',
									'required'=>'required',
									'class'=>'form-control'
							);

							$idCoordinador = array
							(
									'name'=>'idCoordinador',
									'options'=> $this->usuarios,
									'class'=>'form-control'
							);

							$enviar = array
							(
									'type'=>'submit',
									'name'=>'enviar',
									'value'=>'ENVIAR',
									'disabled'=>'disabled',
									'class'=>'form-control'
							);
						?>

						<?php echo validation_errors(); ?>
						<?php echo form_open(base_url().'C_GestionEVG/anadirEtapa'); ?>
						<?php echo form_input($codEtapa); ?>
						<?php echo '<small id="infoAjax" class="form-text text-muted"></small>'; ?></br>
						<?php echo form_input($nombre); ?>
						<?php echo '<small id="infoAjax2" class="form-text text-muted"></small>'; ?></br>
						<?php echo form_label('Coordinador'); ?></br>
						<?php echo form_dropdown($idCoordinador); ?></br>
						<?php echo form_submit($enviar); ?>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
