<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Etapas Padre</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'  ;?>
						<?php echo "<h3>Etapas Padre - ".$codEtapa."</h3>"; ?>
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
					<div class="gestiones animate__animated animate__zoomIn">
						<div class="gestion-apps">
							<h3 class="font-weight-bolder">QUITAR ETAPA PADRE</h3>
							<?php
							if(isset($this->etapasPadre))
								foreach($this->etapasPadre as $indice => $valor)
									echo '<div class="operaciones"><span>'.$valor.'</span><a href="'.base_url().'C_GestionEVG/quitarEtapaPadre/'.$idEtapa.'/'.$indice.'"><button  class="btn btn-outline-danger">Quitar</button></a></div>';
							else
								echo 'No hay etapas padre';
							?>
						</div>
						<div class="gestion-apps">
							<h3 class="font-weight-bolder">AÑADIR ETAPA PADRE</h3>
							<?php
								if(isset($this->etapasNoPadre))
									foreach($this->etapasNoPadre as $indice => $valor)
										echo '<div class="operaciones"><span>'.$valor.'</span><a href="'.base_url().'C_GestionEVG/anadirEtapaPadre/'.$idEtapa.'/'.$indice.'"><button class="btn btn-outline-success">Añadir</button></a></div>';
								else
									echo 'No hay etapas padre disponibles';
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
