<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Form;

class FormularioRegistro extends Form
{
    public function __construct() {
        parent::__construct('formRegistro');
    }
    
    protected function generaCamposFormulario($datos, $errores = array())
    {
        $username = $datos['username'] ?? '';
        $nombre = $datos['nombre'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($errores);
        $errorUsername = self::createMensajeError($errores, 'username', 'span', array('class' => 'error'));
        $errorPassword = self::createMensajeError($errores, 'password', 'span', array('class' => 'error'));
        $errorPassword2 = self::createMensajeError($errores, 'password2', 'span', array('class' => 'error'));

        $html = <<<EOS
            <fieldset>
                $htmlErroresGlobales
                <div class="grupo-control">
                    <label>Nombre de usuario:</label> <input class="control" type="email" name="username" value="$username" />$errorUsername
                </div>
                <div class="grupo-control">
                    <label>Password:</label> <input class="control" type="password" name="password" />$errorPassword
                </div>
                <div class="grupo-control">
                    <label>Vuelve a introducir el Password:</label> <input class="control" type="password" name="password2" />$errorPassword2
                </div>
                <div class="grupo-control"><button type="submit" name="registro">Registrar</button></div>
            </fieldset>
        EOS;
        return $html;
    }
    

    protected function procesaFormulario($datos)
    {
        $result = array();
        
        $username = $datos['username'] ?? null;
        
        if ( !$username || ! mb_ereg_match(FormularioLogin::HTML5_EMAIL_REGEXP, $username) ) {
        $result['username'] = 'El nombre de usuario no es válido';
        $ok = false;
        }
        
        $password = $datos['password'] ?? null;
        if ( empty($password) || mb_strlen($password) < 5 ) {
            $result['password'] = "El password tiene que tener una longitud de al menos 5 caracteres.";
        }
        $password2 = $datos['password2'] ?? null;
        if ( empty($password2) || strcmp($password, $password2) !== 0 ) {
            $result['password2'] = "Los passwords deben coincidir";
        }
        
        if (count($result) === 0) {
            $user = Usuario::crea($username, $password, 'user');
            if ($user) {
                // SEGURIDAD: Forzamos que se genere una nueva cookie de sesión por si la han capturado antes de hacer login
                session_regenerate_id(true);

                $app = Aplicacion::getSingleton();
                $app->login($user);

                $mensajes = ['Se ha registrado exitosamente', "Bienvenido $nombre"];
                $app =  Aplicacion::getSingleton();
                $app->putAtributoPeticion('mensajes', $mensajes);
                $result = $app->resuelve('/index.php');
            } else {
                $result[] = "El usuario ya existe";
            }
        }
        return $result;
    }
}