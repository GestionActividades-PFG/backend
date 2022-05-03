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
					<?php echo "<a href='".base_url()."apps'>Aplicaciones</a>"; ?>
				</li>
				<li>
					<?php echo "<a href='".base_url()."profiles'>Perfiles</a>"; ?>
				</li>
			</ul>
		</nav>
	</div>
</aside>
