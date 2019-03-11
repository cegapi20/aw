<?php
require_once __DIR__.'/plantilla_utils.php';
$mensajes = mensajesPeticionAnterior();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="<?= $params['app']->resuelve('/css/estilo.css') ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?= $params['tituloPagina'] ?></title>
</head>
<body>
<?= $mensajes ?>
<div id="contenedor">
<?php
  $params['app']->doInclude('comun/cabecera.php');
  $params['app']->doInclude('comun/sidebarIzq.php');
?>
  <main>
    <article>
<?= $params['contenidoPagina'] ?>
    </article>
  </main>
<?php
  $params['app']->doInclude('comun/sidebarDer.php');
  $params['app']->doInclude('comun/pie.php');
?>
</div>
</body>
</html>
