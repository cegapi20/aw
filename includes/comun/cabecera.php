<?php
use es\ucm\fdi\aw;

function mostrarSaludo() {
  $html = '';
  $app = aw\Aplicacion::getSingleton();
  $nombreUsuario = $app->nombreUsuario();
	if ($app->usuarioLogueado()) {
    $logoutUrl = $app->resuelve('/logout.php');
		$html = "Bienvenido, ${nombreUsuario}.<a href='${logoutUrl}'>(salir)</a>";
	} else {
    $loginUrl = $app->resuelve('/login.php');
		$html = "Usuario desconocido. <a href='${loginUrl}'>Login</a>";
	}

  return $html;
}

?>
<div id="cabecera">
	<h1>Mi gran página web</h1>
	<div class="saludo">
	  <?=	mostrarSaludo() ?>
	</div>
</div>

