<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioLogout extends Form
{

  public function __construct()
  {
    parent::__construct('formLogout', 1, array('action' => Aplicacion::getSingleton()->resuelve('/logout.php')));
  }
  
  protected function generaCamposFormulario ($datos, $errores = array())
  {

    $camposFormulario=<<<EOS
      <button class="enlace" type="submit">(salir)</button>
    EOS;
    return $camposFormulario;
  }

  /**
   * Procesa los datos del formulario.
   */
  protected function procesaFormulario($datos)
  {
    $app = Aplicacion::getSingleton();

    $app->logout();
    $mensajes = ['Hasta pronto !'];
    $app->putAtributoPeticion('mensajes', $mensajes);
    $result = $app->resuelve('/index.php');

    return $result;
  }
}
