<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Admin';
$contenidoPagina='';

if ($app->tieneRol('admin')) {
  $contenidoPagina=<<<EOS
    <h1>Consola de administración</h1>
    <p>Aquí estarían todos los controles de administración</p>
  EOS;
} else {
  $contenidoPagina=<<<EOS
  <h1>Acceso Denegado!</h1>
  <p>No tienes permisos suficientes para administrar la web.</p>
  EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPagina' => $contenidoPagina];
$app->generaVista('/plantillas/plantilla.php', $params);