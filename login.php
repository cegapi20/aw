<?php

require_once __DIR__.'/includes/config.php';

$formLogin = new \es\ucm\fdi\aw\usuarios\FormularioLogin();
$formLogin = $formLogin->gestiona();


$tituloPagina = 'Login';
$contenidoPagina=<<<EOF
  	<h1>Acceso al sistema</h1>
    $formLogin
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPagina' => $contenidoPagina];
$app->generaVista('/plantillas/plantilla.php', $params);