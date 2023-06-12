<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Portada';
$login = resuelve('/login.php');
$register = resuelve('/registro.php');
$avatar_path = $app->test();
$avatarUsuario = $app->avatarUsuario();
$contenidoPrincipal=<<<EOS
  <h1>Página principal</h1>
  <p><img src="${avatar_path}"></img> Bienvenido  </p>
  <img src="${avatarUsuario}"></img>
  <a href="${login}">Login</a>
  <a href="${register}">Registro</a>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);