<?php include_once('application/views/Plantilla/header.php'); ?>
<html>
	<head>
		<title>EVG - Aplicaciones</title>
	</head>
	<body>
		<div id="principal" class="container-fluid animate__animated animate__fadeIn">
			<div class="row">
				<header class="col-12">
					<div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-6">
						<?php echo '<a href="'.base_url().'main"><img id="logo-evg" src="'.base_url().'uploads/iconos/escudo-evg.png" alt="Escudo EVG" class="img-fluid"/></a>'  ;?>
						<h3>APLICACIONES</h3>
					</div>
					<div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-6">
						<?php
							$picture = $this -> session -> userdata('profile_pic');
							echo '<img id="profile_picture" src="'.$picture.'" alt="Google Profile Picture" class="img-fluid rounded-circle"/>';
						?>
						<?php echo "<button onclick=\"confirmar('¿Seguro que quieres cerrar sesión?','".base_url()."Auth/logout', 'Cerrar Sesión', 'Cancelar', 'Cerrar')\" data-toggle=\"modal\" data-target=\"#myModal\" id=\"icon-logout\" class=\"btn\"><i class=\"fa fa-sign-out-alt\"></i></button>" ;?>
					</div>
				</header>
			</div>
			<div id="principal" class="container-fluid animate__animated animate__pulse">
				<div id="gridAplicaciones" class="row row-cols-4">
					<?php
						if(empty($this -> aplicaciones))
							echo 'Aún no tiene aplicaciones disponibles';
						else
							foreach($this -> aplicaciones as $valor)
							{
								echo
								'
									<div class="col box one">
										<a href="'.$valor['url'].'">
											<h1>'.$valor['nombre'].'</h1>';
									if(isset($valor["icono"]))
										echo
										'
											<div class="poster p1">
												<img src="uploads/iconos/'.$valor['icono'].'" id="icono-app"/>
											</div>
										</a>
									</div>							
								';
							}
					?>
				</div>
			</div>
		</div>
	</body>
</html>
