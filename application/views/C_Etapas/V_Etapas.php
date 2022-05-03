<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Etapas</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'  ;?>
						<h3>Gestión EVG - Etapas</h3>
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
					<?php echo "<button onclick=\"location.href ='" . base_url() . "add-stage'\" class=\"btn btn-success\" data-toggle=\"popover\" data-content=\"Añadir Etapa\"><i class=\"fas fa-plus-square\"></i></button>"; ?>
					<div class="gestion-apps animate__animated animate__slideInLeft">
						<?php
							if(empty($this -> listaEtapas))
								echo "<b>No hay etapas creadas.</b>";
							else
								foreach($this->listaEtapas as $indice => $valor)
									echo
									"
										<div class=\"fila\">
											<h3>".$valor."</h3>
											<button onclick=\"location.href ='" . base_url() . "father-stage/".$indice."'\" class=\"btn btn-info\" data-toggle=\"popover\" data-content=\"Etapa Padre\"><i class=\"fas fa-cog\"></i></button>
											<button onclick=\"location.href ='" . base_url() . "update-stage/".$indice."'\" class=\"btn btn-warning\" data-toggle=\"popover\" data-content=\"Modificar Etapa\"><i class=\"fas fa-edit\"></i></button>
											<button onclick=\"confirmar('¿Seguro que quieres borrar la etapa: <b>".$valor."</b>?', '".base_url()."delete-stage/".$indice."', 'Eliminar Etapa', 'Cancelar', 'Eliminar')\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#myModal\"><i class=\"fas fa-trash\"></i></button>													
										</div>					
									";
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
