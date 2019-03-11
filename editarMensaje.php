<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\mensajes\Mensaje;

$idMensaje = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$idRaizMensajes = filter_input(INPUT_GET, 'idRaizMensajes', FILTER_SANITIZE_NUMBER_INT);

$formEditaMensaje = new es\ucm\fdi\aw\mensajes\FormularioEditaMensaje($idMensaje, $idRaizMensajes);
$htmlFormEditaMensaje = $formEditaMensaje->gestiona();

if (!$idMensaje) {
	$url = $app->resuelve('/mensajes.php');
	if ($idRaizMensajes != null) {
		$url .= "?id={$idRaizMensajes}";
	}
    header('Location: '.$url);
    exit();
}

$mensaje = $formEditaMensaje->getMensaje();
if (!$mensaje) {
	$url = $app->resuelve('/mensajes.php');
	if ($idRaizMensajes != null) {
		$url .= "?id={$idRaizMensajes}";
	}
    header('Location: '.$url);
    exit();
}


$tituloPagina = 'Actualiza Mensaje';

if ( $app->usuarioLogueado() && ($app->idUsuario() == $mensaje->idAutor || $app->tieneRol('admin'))) {
	$contenidoPagina = "<h1>Mensaje: $mensaje->mensaje</h1>";
	$contenidoPagina .= $htmlFormEditaMensaje;
} else {
	$contenidoPagina = <<<EOS
	<h1>No tienes permisos para editar este mensaje</h1>
	EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPagina' => $contenidoPagina];
$app->generaVista('/plantillas/plantilla.php', $params);
