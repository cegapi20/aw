<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\FormularioLogout;

function mostrarSaludo()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if ($app->usuarioLogueado()) {
        $nombreUsuario = $app->nombreUsuario();

        $formLogout = new FormularioLogout();
        $htmlLogout = $formLogout->gestiona();
        $tratamiento_id = $_SESSION['tratamiento_id'];
        if($tratamiento_id == '1'){
            $html = "Bienvenido, Profesor ${nombreUsuario}. $htmlLogout";
        }else if($tratamiento_id == '2'){
            $html = "Bienvenida, Profesora ${nombreUsuario}. $htmlLogout";
        }
    } else {
        $loginUrl = $app->resuelve('/login.php');
        $registroUrl = $app->resuelve('/registro.php');
        $html = <<<EOS
        Usuario desconocido. <a href="{$loginUrl}">Login</a> <a href="{$registroUrl}">Registro</a>
      EOS;
    }

    return $html;
}

?>
<header>
    <h1><?= $params['cabecera'] ?? 'Mi gran página web' ?></h1>
    <div class="saludo">
        <?= mostrarSaludo(); ?>
    </div>
</header>