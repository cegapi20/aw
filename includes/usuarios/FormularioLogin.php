<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioLogin extends Form
{

  const HTML5_EMAIL_REGEXP = '^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$';

  public function __construct()
  {
    parent::__construct('formLogin');
  }
  
  protected function generaCamposFormulario ($datos, $errores = array())
  {
    $username = 'user@example.org';
    $password = 'userpass';
    if ($datos) {
      $username = isset($datos['username']) ? $datos['username'] : $username;
      /* Similar a la comparación anterior pero con el operador ?? de PHP 7 */
      $password = $datos['password'] ?? $password;
    }

    // Se generan los mensajes de error si existen.
    $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
    $errorUsername = self::createMensajeError($errores, 'username', 'span', array('class' => 'error'));
    $errorPassword = self::createMensajeError($errores, 'password', 'span', array('class' => 'error'));

    $camposFormulario=<<<EOS
    <fieldset>
      <legend>Usuario y contraseña</legend>
      $htmlErroresGlobales
      <p><label>Name:</label> <input type="email" name="username" value="$username"/>$errorUsername</p>
      <p><label>Password:</label> <input type="password" name="password" value="$password"/>$errorPassword</p>
      <button type="submit">Entrar</button>
    </fieldset>
    EOS;
    return $camposFormulario;
  }

  /**
   * Procesa los datos del formulario.
   */
  protected function procesaFormulario($datos)
  {
    $result = array();
    $ok = true;

    $username = $datos['username'] ?? '' ;
    if ( !$username || ! mb_ereg_match(self::HTML5_EMAIL_REGEXP, $username) ) {
      $result['username'] = 'El nombre de usuario no es válido';
      $ok = false;
    }

    $password = $datos['password'] ?? '' ;
    if ( ! $password ||  mb_strlen($password) < 4 ) {
      $result['password'] = 'La contraseña no es válida';
      $ok = false;
    }

    if ( $ok ) {
      $user = Usuario::login($username, $password);
      if ( $user ) {
        // SEGURIDAD: Forzamos que se genere una nueva cookie de sesión por si la han capturado antes de hacer login
        session_regenerate_id(true);
        $app =  Aplicacion::getSingleton();
        $app->login($user);
        $result = $app->resuelve('/index.php');
      }else {
        $result[] = 'El usuario o la contraseña es incorrecta';
      }
    }
    return $result;
  }
}
