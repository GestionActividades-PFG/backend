<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Alumnos</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'; ?>
						<h3>Gestión EVG - Alumnos</h3>
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
					<?php echo "<button onclick=\"location.href ='" . base_url() . "add-student'\" class=\"btn btn-success\" data-toggle=\"popover\" data-content=\"Añadir Alumno\"><i class=\"fas fa-plus-square\"></i></button>"; ?>
					<?php echo "<button onclick=\"location.href ='" . base_url() . "import-students'\" class=\"btn btn-warning\" data-toggle=\"popover\" data-content=\"Importar Alumnos\"><i class=\"fas fa-file-import\"></i></button>"; ?>
					<button class="btn btn-secondary" data-toggle="modal" data-target="#info"><i class="fas fa-info-circle"></i></button>
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
											<button onclick=\"location.href ='" . base_url() . "sections-stage/".$indice."'\" class=\"btn btn-info\" data-toggle=\"popover\" data-content=\"Ver Secciones Etapa\"><i class=\"fas fa-eye\"></i></button>											
										</div>						
									";
						?>
					</div>
				</div>
			</div>
		</div>
		<!--Bootstrap Modal-->
		<div id="info" class="modal fade" role="dialog" tabindex="-1" data-backdrop="static">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bolder">Información</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="eliminarModal()">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Para acceder a los alumnos de una sección, elige la etapa a la que pertenece y luego la sección.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger font-weight-bolder" data-dismiss="modal" onclick="eliminarModal()">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
		<?php
			if(!is_null($this -> input -> cookie('importedStudents')))
			{
				if($this -> input -> cookie('importedStudents') != 0)
				{
					echo
					'
						<!--Bootstrap Import Modal-->
						<div id="imports" class="modal fade" role="dialog" tabindex="-1" data-backdrop="static">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title font-weight-bolder">Importación Finalizada</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="eliminarModal()">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">';
										echo "<p>Se han importado " . $this -> input -> cookie('importedStudents') . ' usuarios.</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger font-weight-bolder" data-dismiss="modal" onclick="eliminarModal()">Cerrar</button>
									</div>
								</div>
							</div>
						</div>
					';
				}
				else
				{
					echo
					'
						<!--Bootstrap Import Modal-->
						<div id="imports" class="modal fade" role="dialog" tabindex="-1" data-backdrop="static">
							<div class="modal-dialog modal-dialog-centered" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title font-weight-bolder">Importación Finalizada</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="eliminarModal()">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<p>No se han importado usuarios.</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger font-weight-bolder" data-dismiss="modal" onclick="eliminarModal()">Cerrar</button>
									</div>
								</div>
							</div>
						</div>
					';
				}
			}
			delete_cookie('importedStudents');
		?>
	</body>
</html>
