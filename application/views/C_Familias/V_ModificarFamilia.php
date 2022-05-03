<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>Gestión EVG</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'  ;?>
						<?php echo "<h3>Modificar Familia Profesional - ".$this->datosFamilia[0]['nombre']."</h3>"; ?>
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
					<?php echo "<button onclick=\"location.href ='" . base_url() . "families'\" class=\"btn btn-secondary\" data-toggle=\"popover\" data-content=\"Volver atrás\"><i class=\"fas fa-arrow-left\"></i></button>"; ?>
					<div class="gestion-apps animate__animated animate__zoomIn">
						<?php
							$nombre = array
							(
									'id'=>'nombre',
									'name'=>'nombre',
									'oninput'=>"buscarCSU('".base_url()."', 'FP_FamiliasProfesionales', this.value, 'nombre', 'infoAjax', 'nombre', 'Ya existe una familia profesional con el nombre ', '".$this->datosFamilia[0]['nombre']."')",
									'value'=>$this->datosFamilia[0]['nombre'],
									'required'=>'required',
									'class'=>'form-control'
							);

							$departamento = array
							(
									'name'=>'departamento',
									'options'=>$this->departamentos,
									'selected' => $idDepartamento,
									'class'=>'form-control'
							);

							$enviar = array
							(
									'type'=>'submit',
									'name'=>'enviar',
									'value'=>'ENVIAR',
									'class'=>'form-control'
							);
						?>

						<?php echo validation_errors(); ?>
						<?php echo form_open(base_url().'C_GestionEVG/modificarFamilia/'.$idFamilia); ?>
						<?php echo form_label('Nombre'); ?>
						<?php echo form_input($nombre); ?>
						<?php echo '<small id="infoAjax" class="form-text text-muted"></small>'; ?></br>
						<?php echo form_label('Departamento'); ?>
						<?php echo form_dropdown($departamento); ?></br>
						<?php echo form_submit($enviar); ?>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
