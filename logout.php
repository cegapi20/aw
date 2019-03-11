<?php
require_once __DIR__.'/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: '. $app->resuelve('/index.php'));
} else {
  $formLogout = new  \es\ucm\fdi\aw\usuarios\FormularioLogout();
  $formLogout->gestiona();
}