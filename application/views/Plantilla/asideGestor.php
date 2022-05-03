<aside class="show">
	<div class="wrapper">
		<nav id="sidebar">
			<div class="sidebar-header">
				<?php
					$name = $this -> session -> userdata('name');
					echo "<h5 class='font-weight-bolder'>".$name."</h5>";
				?>
			</div>
			<ul class="list-unstyled components">
				<li>
					<?php echo "<a href='".base_url()."stages'>Etapas</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."courses'>Cursos</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."cycles'>Ciclos</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."sections'>Secciones</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."students'>Alumnos</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."users'>Usuarios</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."departments'>Departamentos</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."families'>Familias Profesionales</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a target='blank' href='".base_url()."tutor-list'>Listado de tutores</a></br>"; ?>
				</li>
				<li>
					<?php echo "<a href =\"#\" onclick=\"modalCheck('¿Seguro que quieres iniciar un nuevo curso?. Se borrarán todos los alumnos. ESTA ACCIÓN ES IRREVERSIBLE','".base_url()."C_GestionEVG/nuevoCurso', 'Iniciar Nuevo Curso', '¿Desea comenzar un nuevo curso?', 'Cancelar', 'Iniciar')\" onkeypress=\"if (event.keyCode===13) modalCheck('¿Seguro que quieres iniciar un nuevo curso? Se borrarán todos los alumnos','".base_url()."C_GestionEVG/nuevoCurso', 'Iniciar Nuevo Curso', 'Cancelar', 'Iniciar')\" tabindex='0' data-toggle=\"modal\" data-target=\"#check\">Comienzo de Curso</a>"; ?>
				</li>
			</ul>
		</nav>
	</div>
</aside>
