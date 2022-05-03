<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Perfiles</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'  ;?>
						<?php echo "<h3>Perfiles - ".$nombre."</h3>"?>
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
				<?php include_once('application/views/Plantilla/asideAdmin.php')?>
				<div class="general">
					<button type="button" id="sidebarCollapse" class="btn btn-sidebar">
						<i class="fas fa-bars" data-toggle="popover" data-content="Mostrar menú"></i>
						<i class="fas fa-times" data-toggle="popover" data-content="Ocultar menú"></i>
					</button>
					<?php echo "<button onclick=\"location.href ='" . base_url() . "profiles'\" class=\"btn btn-secondary\" data-toggle=\"popover\" data-content=\"Volver atrás\"><i class=\"fas fa-arrow-left\"></i></button>"; ?>
					<div class="gestiones animate__animated animate__zoomIn">
						<div class="gestion-apps">
							<h3 class="font-weight-bolder">Eliminar Usuarios</h3>
							<?php
								if(isset($this->usuariosPerfil))
									foreach($this->usuariosPerfil as $indice => $valor)
										echo '<div class="mt-2"><b>'.$valor.'</b><a href="'.base_url().'C_AdministracionEVG/quitarUsuarioPerfil/'.$idPerfil.'/'.$indice.'"><button class="btn btn-outline-danger ml-2" data-toggle="popover" data-content="Desvincular Usuario"><i class="fas fa-trash"></i></button></a></div>';
								else
									echo 'No hay usuarios asociados';
							?>
						</div>
						<div class="gestion-apps">
							<h3 class="font-weight-bolder">Añadir Usuarios</h3>
							<?php
								$correo = array
								(
										'name'=>'correo',
										'oninput'=>'buscarUsuarios(\''.base_url().'\','.$idPerfil.', this.value)',
										'onfocusin'=>'document.getElementsByClassName(\'sugerenciaAjax\')[0].style.display=\'block\'',
										'onfocusout'=>'document.getElementsByClassName(\'sugerenciaAjax\')[0].style.display=\'none\'',
										'required'=>'required',
										'autocomplete'=>'off',
										'class'=>'form-control'
								);

								$enviar = array
								(
										'name'=>'enviar',
										'value'=>'ENVIAR',
										'class'=>'form-control'
								);
							?>

							<?php echo validation_errors() ;?>
							<?php echo form_open(base_url().'C_AdministracionEVG/anadirUsuarioPerfil/'.$idPerfil) ;?>
							<?php echo form_label('Correo') ;?>
							<?php echo form_input($correo); ?>
							<?php echo '<small class="form-text text-muted sugerenciaAjax"></small>'; ?></br>
							<?php echo form_submit($enviar); ?>
							<?php echo form_close() ;?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
