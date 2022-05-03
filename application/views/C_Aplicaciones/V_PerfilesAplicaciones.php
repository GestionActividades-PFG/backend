<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Perfiles Aplicación</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'  ;?>
						<?php echo '<h3>Perfiles Aplicación - '.$nombreApp.'</h3>'; ?>
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
				<?php include_once('application/views/Plantilla/asideAdmin.php') ?>
				<div class="general">
					<button type="button" id="sidebarCollapse" class="btn btn-sidebar">
						<i class="fas fa-bars" data-toggle="popover" data-content="Mostrar menú"></i>
						<i class="fas fa-times" data-toggle="popover" data-content="Ocultar menú"></i>
					</button>
					<?php echo "<button onclick=\"location.href ='" . base_url() . "apps'\" class=\"btn btn-secondary\" data-toggle=\"popover\" data-content=\"Volver atrás\"><i class=\"fas fa-arrow-left\"></i></button>"; ?>
					<div class="gestiones animate__animated animate__zoomIn">
						<div class="gestion-apps">
							<h3 class="font-weight-bolder">QUITAR acceso AL PERFIL</h3>
							<?php
								if(isset($this->perfilesAplicacion))
									foreach($this->perfilesAplicacion as $indice => $valor)
										echo '<div class="operaciones"><span>'.$valor.'</span><a href="'.base_url().'C_AdministracionEVG/quitarPerfilAplicacion/'.$idAplicacion.'/'.$indice.'"><button class="btn btn-outline-danger">Quitar</button></a></div>';
								else
									echo 'No hay perfiles con acceso a la aplicación';
							?>
						</div>
						<div class="gestion-apps">
							<h3 class="font-weight-bolder">AÑADIR acceso AL PERFIL</h3>
							<?php
								if(isset($this->perfilesNoAplicacion))
									foreach($this->perfilesNoAplicacion as $indice => $valor)
										echo '<div class="operaciones"><span>'.$valor.'</span><a ondblclick="location.href=\''.base_url().'C_AdministracionEVG/perfilesAplicacion/'.$idAplicacion.'\'" href="'.base_url().'C_AdministracionEVG/anadirPerfilAplicacion/'.$idAplicacion.'/'.$indice.'"><button class="btn btn-outline-success">Añadir</button></a></div>';
								else
									echo 'No hay perfiles disponibles para añadir';
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
