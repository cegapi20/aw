<?php
namespace es\ucm\fdi\aw\mensajes;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioBorraMensaje extends Form
{

  private $idMensaje;

  private $idMensajePadre;

  public function __construct($idMensaje=-1, $idMensajePadre = NULL)
  {
    parent::__construct('formBorraMensaje', $idMensaje);
    $this->idMensaje = $idMensaje;
    $this->idMensajePadre = $idMensajePadre;
  }
  
  protected function generaCamposFormulario ($datos, $errores = array())
  {

    $mensajePadre = '';
    if ($this->idMensajePadre) {
      $mensajePadre = <<<EOS
        <input type="hidden" name="idMensajePadre" value="{$this->idMensajePadre}" />
      EOS;
    }

    $camposFormulario=<<<EOF
      <input type="hidden" name="idMensaje" value="{$this->idMensaje}" />
      $mensajePadre
      <button type="submit" ">Borrar</button>
    EOF;
    return $camposFormulario;
  }

  /**
   * Procesa los datos del formulario.
   */
  protected function procesaFormulario($datos)
  {

    $app = Aplicacion::getSingleton();

    $idMensajePadre = isset($datos['idMensajePadre']) ?? null ;
    if ($idMensajePadre) {
      $result = $app->resuelve('/mensajes.php?id='.$idMensajePadre);
    } else {
      $result = $app->resuelve('/mensajes.php');
    }

    $idMensaje = $datos['idMensaje'] ?? null ;
    if ( $idMensaje ) {
      $mensaje = Mensaje::buscaPorId($idMensaje);
      if ($app->usuarioLogueado() && ($app->idUsuario() == $mensaje->idAutor|| $app->tieneRol('admin'))) {
        Mensaje::borraPorId($idMensaje);
        $app = Aplicacion::getSingleton();
        $app->putAtributoPeticion('mensajes', array('Mensaje borrado exitosamente'));
      }
    }
    return $result;
  }
}
