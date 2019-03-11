<?php
namespace es\ucm\fdi\aw\mensajes;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioRespuesta extends Form
{

  private $idMensajePadre;

  public function __construct($idMensajePadre)
  {
    parent::__construct('formRespuesta', $idMensajePadre);
    $this->idMensajePadre = $idMensajePadre;
  }
  
  protected function generaCamposFormulario ($datos, $errores = array())
  {
    $mensaje = 'Mensaje';
    if ($datos) {
      $mensaje = $datos['mensaje'] ?? $mensaje;
    }


    $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
    $errorMensaje = self::createMensajeError($errores, 'mensaje', 'span', array('class' => 'error'));

    $maxSize = Mensaje::MAX_SIZE;
    $camposFormulario=<<<EOF
      <input type="hidden" name="idMensajePadre" value="$this->idMensajePadre" />
      <fieldset>
        <legend>Respuesta</legend>
        $htmlErroresGlobales
        <p><label>Mensaje:</label> <input type="text" name="mensaje" value="$mensaje" maxlength="$maxSize"/> $errorMensaje</p>
        <button type="submit">Añadir</button>
      </fieldset>
    EOF;
    return $camposFormulario;
  }

  /**
   * Procesa los datos del formulario.
   */
  protected function procesaFormulario($datos)
  {

    $result = array();
    $ok = true;
    $mensaje = $datos['mensaje'] ?? null ;
    if ( ! $mensaje ||  mb_strlen($mensaje) == 0 || mb_strlen($mensaje) > 140 ) {
      $result['mensaje'] = 'La longitud del mensaje debe ser entre 1 o 140 caracteres.';
      $ok = false;
    }
    $idMensajePadre = $datos['idMensajePadre'] ?? null ;
    if ( ! $idMensajePadre ) {
      $result[] = 'No se ha podido añadir la respuesta.';
      $ok = false;
    }

    if ( $ok ) {
      $app = Aplicacion::getSingleton();
      $mensaje = Mensaje::crea($app->idUsuario(), $mensaje, $idMensajePadre);
      $mensaje->guarda();
      if ( $mensaje ) {
        $result = $app->resuelve('/mensajes.php?id='.$idMensajePadre);
      }else {
        $result[] = 'No se ha podido añadir el mensaje.';
      }
    }
    return $result;
  }
}
