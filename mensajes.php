<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\mensajes\Mensaje;
use es\ucm\fdi\aw\mensajes\FormularioRespuesta;
use es\ucm\fdi\aw\mensajes\FormularioMensaje;
use es\ucm\fdi\aw\mensajes\FormularioBorraMensaje;
use es\ucm\fdi\aw\Aplicacion;

/*
 * Funciones de apoyo
 */

function listaMensajesPaginados($idMensajePadre = NULL, $numPorPagina = 5, $numPagina = 1)
{
    $app = Aplicacion::getSingleton();

    $html = '<ul>';
    // Pedimos un mensaje más allá de la página actual para saber si hay más páginas
    $mensajes = Mensaje::buscaPorMensajePadrePaginado($idMensajePadre, $numPorPagina+1, $numPagina-1);
    $numMensajes = count($mensajes);
    $haySiguientePagina = false;
    if ($numMensajes > $numPorPagina) {
        $numMensajes = $numPorPagina;
        $haySiguientePagina = true;
    }
    $idx = 0;
    while($idx < $numMensajes) {
        $mensaje = $mensajes[$idx];
        $href = $app->resuelve('/mensajes.php?id=' . $mensaje->id . '&numPagina=1&numPorPagina='. $numPorPagina);
        $username = $mensaje->autor->username();
        $html .= '<li>';
        $html .= "<a href=\"$href\">$mensaje->mensaje ($username) ($mensaje->fechaHora)</a>";
        if ($app->usuarioLogueado() && ($app->idUsuario() == $mensaje->idAutor|| $app->tieneRol('admin'))) {
            $raizMensajesParam = '';
            if ($idMensajePadre) {
                $raizMensajesParam = '&idRaizMensajes='. $idMensajePadre;
            }

            $formBorraMensaje = new FormularioBorraMensaje($mensaje->id, $idMensajePadre);
            $htmlFormBorraMensaje = $formBorraMensaje->gestiona();

            $html .= <<<EOS
                <a class="boton" href="editarMensaje.php?id={$mensaje->id}{$raizMensajesParam}">Editar</a>
                $htmlFormBorraMensaje
            EOS;
        }
        $html .= '</li>';
        $idx++;
    }
    $html .= '</ul>';

    // Controles de paginacion
    $clasesPrevia='deshabilitado';
    $clasesSiguiente = 'deshabilitado';
    $hrefPrevia = '';
    $hrefSiguiente = '';

    if ($numPagina > 1) {
        // Seguro que hay mensajes anteriores
        $paginaPrevia = $numPagina - 1;
        $clasesPrevia = '';
        $hrefPrevia = 'href="' . $app->resuelve('/mensajes.php?id=').$idMensajePadre . '&numPagina='. $paginaPrevia . '"&numPorPagina='. $numPorPagina . '"';
    }

    if ($haySiguientePagina) {
        // Puede que haya mensajes posteriores
        $paginaSiguiente = $numPagina + 1;
        $clasesSiguiente = '';
        $hrefSiguiente = 'href="' . $app->resuelve('/mensajes.php?id=').$idMensajePadre . '&numPagina='. $paginaSiguiente . '&numPorPagina='. $numPorPagina . '"';
    }

    $html .=<<<EOS
        <div>
            Página: $numPagina, <a class="boton $clasesPrevia" $hrefPrevia>Previa</a><a class="boton $clasesSiguiente" $hrefSiguiente>Siguiente</a>
        </div>
    EOS;

    return $html;
}


/*
 * Procesando la petición
 */

$idMensaje = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$numPagina = filter_input(INPUT_GET, 'numPagina', FILTER_SANITIZE_NUMBER_INT) ?? 1;
$numPorPagina = filter_input(INPUT_GET, 'numPorPagina', FILTER_SANITIZE_NUMBER_INT) ?? 5;

// Gestionamos si se ha enviado el formulario de borrado de mensajes
$formBorraMensaje = new FormularioBorraMensaje();
$formBorraMensaje->gestiona();

// Gestionamos si se ha enviado formulario de respuesta de un mensaje existente
$formRespuestaMensaje =  new FormularioRespuesta($idMensaje);
$htmlFormRespuestaMensaje = $formRespuestaMensaje->gestiona();

// Gestionamos si se ha enviado formulario de crear nuevo mensaje
$formNuevoMensaje = new FormularioMensaje();
$htmlFormNuevoMensaje = $formNuevoMensaje->gestiona();


// Generamos la vista si no se está enviando ningún formulario

$cabecera = '';
if ($idMensaje) {
    $mensaje = Mensaje::buscaPorId($idMensaje);
    $cabecera = "<h1>Mensaje: $mensaje->mensaje</h1>";
} else {
    $cabecera = "<h1>Mensajes</h1>";
}

$htmlMensajes = listaMensajesPaginados($idMensaje, $numPorPagina, $numPagina);

$htmlFormMensaje = '';
if ($app->tieneRol('user')) {
    if ($idMensaje) {
        $htmlFormMensaje = $htmlFormRespuestaMensaje;
    } else {
        $htmlFormMensaje = $htmlFormNuevoMensaje;
    }
}

$tituloPagina = 'Mensajes';
$contenidoPagina=<<<EOF
  	$cabecera
    $htmlMensajes
    $htmlFormMensaje
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPagina' => $contenidoPagina];
$app->generaVista('/plantillas/plantilla.php', $params);