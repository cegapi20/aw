<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();

$usuarioLogueado = $app->usuarioLogueado();
?>
<nav id="sidebarIzq">
	<h3>Navegación</h3>
	<ul>
		<li><a href="<?= $app->resuelve('/index.php')?>">Inicio</a></li>
		<li><a href="<?= $app->resuelve('/contenido.php')?>">Ver contenido</a></li>
		<!-- <li><a href="<?= $app->resuelve('/admin.php')?>">Administrar</a></li> -->
		<?php 
			if($usuarioLogueado){
				?>
				<li><a href="<?= $app->resuelve('/misClases.php')?>">Mis clases</a></li>
				<?php
			}
		?>
	</ul>
</nav>
