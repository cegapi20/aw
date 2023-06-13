<?php

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Crear clase';

$formClase = new \es\ucm\fdi\aw\clases\FormularioClase();
$formClase = $formClase->gestiona();


$contenidoPrincipal = <<<EOS
  <h1>Formulario para crear una clase</h1>
  $formClase

EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
