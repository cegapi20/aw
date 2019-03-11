<?php

require_once __DIR__.'/includes/config.php';

$formRegistro = new \es\ucm\fdi\aw\usuarios\FormularioRegistro();
$formRegistro = $formRegistro->gestiona();


$tituloPagina = 'Registro';
$contenidoPagina=<<<EOF
  	<h1>Registro de usuario</h1>
    $formRegistro
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPagina' => $contenidoPagina];
$app->generaVista('/plantillas/plantilla.php', $params);