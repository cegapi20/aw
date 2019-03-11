<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Portada';
$contenidoPagina=<<<EOS
  <h1>Página principal</h1>
  <p> Aquí está el contenido público, visible para todos los usuarios. </p>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPagina' => $contenidoPagina];
$app->generaVista('/plantillas/plantilla.php', $params);