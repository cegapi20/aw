<?php
namespace es\ucm\fdi\aw\mensajes;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioMensaje extends Form
{

  public function __construct()
  {
    parent::__construct('formMensaje');
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
      <fieldset>
        <legend>Nuevo mensaje</legend>
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
    $mensaje = $datos['mensaje'] ?? '' ;
    if ( ! $mensaje ||  mb_strlen($mensaje) == 0 || mb_strlen($mensaje) > 140 ) {
      $result['mensaje'] = 'La longitud del mensaje debe ser entre 1 o 140 caracteres.';
      $ok = false;
    }

    if ( $ok ) {
      $app = Aplicacion::getSingleton();
      $mensaje = Mensaje::crea($app->idUsuario(), $mensaje);
      $mensaje->guarda();
      if ( $mensaje ) {
        $result = $app->resuelve('/mensajes.php');
      }else {
        $result[] = 'No se ha podido añadir el mensaje.';
      }
    }
    return $result;
  }
}
