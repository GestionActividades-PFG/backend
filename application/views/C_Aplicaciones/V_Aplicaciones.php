<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Gestión de Aplicaciones</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'; ?>
						<h3>Administración EVG - Aplicaciones</h3>
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
					<?php echo "<button onclick=\"location.href ='" . base_url() . "add-app'\" class=\"btn btn-success\" data-toggle=\"popover\" data-content=\"Añadir Aplicación\"><i class=\"fas fa-plus-square\"></i></button>"; ?>
					<div class="gestion-apps animate__animated animate__slideInLeft">
						<?php
							foreach($this->listaApps as $indice => $valor)
								echo
									"
										<div class=\"fila\">
											<h3>".$valor."</h3>
											<button onclick=\"location.href ='" . base_url() . "profiles-app/".$indice."'\" class=\"btn btn-info\" data-toggle=\"popover\" data-content=\"Perfiles Aplicación\"><i class=\"fas fa-user-circle\"></i></button>
											<button onclick=\"location.href ='" . base_url() . "update-app/".$indice."'\" class=\"btn btn-warning\" data-toggle=\"popover\" data-content=\"Editar Aplicación\"><i class=\"fas fa-edit\"></i></button>
											<button onclick=\"modalCheck('¿Seguro que quieres desvincular la aplicación: <b>".$valor."</b>?. Esta acción solo desvincula la aplicación, puede volver a añadirla de nuevo cuando quiera, desde el botón, <i>Añadir Aplicación</i>.', '".base_url()."delete-app/".$indice."', 'Desvincular Aplicación', '¿Desea desvincular la aplicación: <b>".$valor. "</b>?', 'Cancelar', 'Desvincular')\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#check\"><i class=\"fas fa-trash\"></i></button>													
										</div>						
									"
								;
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
