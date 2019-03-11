<?php
namespace es\ucm\fdi\aw\mensajes;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioEditaMensaje extends Form
{

  private $idMensaje;

  private $idMensajePadre;

  private $mensaje;

  public function __construct($idMensaje=-1, $idMensajePadre = NULL)
  {
    parent::__construct('formEditaMensaje', $idMensaje);
    $this->idMensaje = $idMensaje;
    $this->idMensajePadre = $idMensajePadre;
  }
  
  protected function generaCamposFormulario($datos, $errores = array())
  {

    $mensajePadre = '';
    if ($this->idMensajePadre) {
      $mensajePadre = <<<EOS
        <input type="hidden" name="idMensajePadre" value="{$this->idMensajePadre}" />
      EOS;
    }

    $idMensaje = $datos['idMensaje'] ?? $this->idMensaje;
    $textoMensaje = $datos['mensaje'] ?? null;
    if (! $textoMensaje ) {
      $this->mensaje = Mensaje::buscaPorId($idMensaje);
      if ($this->mensaje != null) {
        $textoMensaje =  $this->mensaje->mensaje;
      }
    }

    $camposFormulario=<<<EOF
    <input type="hidden" name="idMensaje" value="{$this->idMensaje}" />
    $mensajePadre
    <fieldset>
      <div><label for="mensaje">Mensaje: </label><input id="mensaje" type="text" name="mensaje" value="{$textoMensaje}"/></div>
      <div><button type="submit">Actualiza</button></div>
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
    $app = Aplicacion::getSingleton();
    $ok = true;

    $textoMensaje = $datos['mensaje'] ?? '' ;
    if ( ! $textoMensaje ||  mb_strlen($textoMensaje) == 0 || mb_strlen($textoMensaje) > 140 ) {
      $result['mensaje'] = 'La longitud del mensaje debe ser entre 1 o 140 caracteres.';
      $ok = false;
    }

    $idMensaje = $datos['idMensaje'] ?? null ;
    if ( ! $idMensaje ) {
      $result[] = 'No tengo claro que mensaje actualizar.';
      $ok = false;
    }

    if ( $ok ) {
      $mensaje = Mensaje::buscaPorId($idMensaje);
      if ($app->usuarioLogueado() && ($app->idUsuario() == $mensaje->idAutor|| $app->tieneRol('admin'))) {
        $mensaje->mensaje = $textoMensaje;
        $mensaje->guarda();
        
        $idMensajePadre = isset($datos['idMensajePadre']) ?? null ;
        if ($idMensajePadre) {
          $result = $app->resuelve('/mensajes.php?id='.$idMensajePadre);
        } else {
          $result = $app->resuelve('/mensajes.php');
        }

        $app = Aplicacion::getSingleton();
        $app->putAtributoPeticion('mensajes', array('Mensaje actualizado correctamente'));
      }
    }
    return $result;
  }

  public function getMensaje()
  {
    return $this->mensaje;
  }
}
