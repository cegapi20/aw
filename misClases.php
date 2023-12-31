<?php

use es\ucm\fdi\aw\clases\Lista;

require_once __DIR__ . '/includes/config.php';

$tituloPagina = 'Mis Clases';

$profesor_id = $app->idUsuario();
$clases = Lista::getClaseAll($profesor_id);
$filas_html = Lista::generaFilasTabla($clases);
$count_clases = count($clases);


$contenidoPrincipal = <<<EOS
  <h1>Mis clases asociadas $count_clases</h1>
  <p> Lista  “AcronimoAsignatura - NombreAsignatura (AcrónimoTitulacion)”. </p>
  <a href="registerclase.php">Añadir una clase</a>
  <table>
  <thead>
    <tr>
      <th>Acronimo</th>
      <th>Asignatura </th>
      <th>Total</th>
      <th>Alumnos</th>
    </tr>
  </thead>
  <tbody>
  $filas_html
  </tbody>
</table>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
