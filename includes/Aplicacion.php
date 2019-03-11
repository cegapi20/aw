<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\usuarios\Usuario;

/**
 * Clase que mantiene el estado global de la aplicación.
 */
class Aplicacion
{
  const ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION = 'attsPeticion';

  private static $instancia;
	
	/**
	 * Devuele una instancia de {@see Aplicacion}.
	 * 
	 * @return Applicacion Obtiene la única instancia de la <code>Aplicacion</code>
	 */
	public static function getSingleton() {
		if (  !self::$instancia instanceof self) {
			self::$instancia = new self;
		}
		return self::$instancia;
	}

	/**
	 * @var array Almacena los datos de configuración de la BD
	 */
	private $bdDatosConexion;

  /**
   * @var string Ruta donde se encuentra instalada la aplicación. Por ejemplo, si
   *             la aplicación está accesible en http://localhost/miApp/, este
   *             parámetro debería de tomar el valor "/miApp".
   */
  private $rutaRaizApp;

  /**
   * @var string Ruta absoluta al directorio "includes" de la aplicación.
   */
  private $dirInstalacion;
  
	/**
	 * Almacena si la Aplicacion ya ha sido inicializada.
	 * 
	 * @var boolean
	 */
	private $inicializada = false;
	
	/**
	 * @var \mysqli Conexión de BD.
	 */
	private $conn;

	/**
	 * @var array Tabla asociativa con los atributos pendientes de la petición.
	 */
	private $atributosPeticion;
 	
	/**
	 * Evita que se pueda instanciar la clase directamente.
	 */
  private function __construct()
  {
  }

  /**
   * Evita que se pueda utilizar el operador clone.
   */
  public function __clone()
  {
    throw new \Exception('No tiene sentido el clonado');
  }

    
  /**
   * Evita que se pueda utilizar serialize().
   */
  public function __sleep()
  {
    throw new \Exception('No tiene sentido el serializar el objeto');
  }
  
  /**
   * Evita que se pueda utilizar unserialize().
   */
  public function __wakeup()
  {
    throw new \Exception('No tiene sentido el deserializar el objeto');
  }
	
	/**
	 * Inicializa la aplicación.
   *
   * Opciones de conexión a la BD:
   * <table>
   *   <thead>
   *     <tr>
   *       <th>Opción</th>
   *       <th>Descripción</th>
   *     </tr>
   *   </thead>
   *   <tbody>
   *     <tr>
   *       <td>host</td>
   *       <td>IP / dominio donde se encuentra el servidor de BD.</td>
   *     </tr>
   *     <tr>
   *       <td>bd</td>
   *       <td>Nombre de la BD que queremos utilizar.</td>
   *     </tr>
   *     <tr>
   *       <td>user</td>
   *       <td>Nombre de usuario con el que nos conectamos a la BD.</td>
   *     </tr>
   *     <tr>
   *       <td>pass</td>
   *       <td>Contraseña para el usuario de la BD.</td>
   *     </tr>
   *   </tbody>
   * </table>
   * 
   * @param array $bdDatosConexion datos de configuración de la BD.
   * 
   * @param string $rutaRaizApp (opcional) Ruta donde se encuentra instalada la aplicación.
   *                            Por ejemplo, si la aplicación está accesible en
   *                            http://localhost/miApp/, este parámetro debería de tomar el
   *                            valor "/miApp".
   * @param string $dirInstalacion (opcional) Ruta absoluta al directorio "includes" de la
   *                               aplicación.
   * 
	 */
  public function init($bdDatosConexion, $rutaRaizApp = '/', $dirInstalacion = __DIR__)
  {
    if ( ! $this->inicializada ) {
      $this->bdDatosConexion = $bdDatosConexion;

      $this->rutaRaizApp = $rutaRaizApp;
      $tamRutaRaizApp = mb_strlen($this->rutaRaizApp);
      if ($tamRutaRaizApp > 0 && mb_substr($this->rutaRaizApp, $tamRutaRaizApp, 1) === '/') {
        $this->rutaRaizApp = mb_substr($this->rutaRaizApp, 0, $tamRutaRaizApp - 1);
      }

      $this->dirInstalacion = $dirInstalacion;
      $tamDirInstalacion = mb_strlen($this->dirInstalacion);
      if ($tamDirInstalacion > 0 && mb_substr($this->dirInstalacion, $tamDirInstalacion, 1) === '/') {
        $this->dirInstalacion = mb_substr($this->dirInstalacion, 0, $tamDirInstalacion - 1);
      }

      $this->conn = null;
      session_start();

      /* Se inicializa los atributos asociados a la petición en base a la sesión y se eliminan para que
        * no estén disponibles después de la gestión de esta petición.
        */
      $this->atributosPeticion = $_SESSION[self::ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION] ?? array();
      unset($_SESSION[self::ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION]);

    	$this->inicializada = true;
    }
  }
	
	/**
	 * Cierre de la aplicación.
	 */
	public function shutdown()
	{
	    $this->compruebaInstanciaInicializada();
	    if ($this->conn !== null && ! $this->conn->connect_errno) {
	        $this->conn->close();
	    }
  }
	
	/**
	 * Comprueba si la aplicación está inicializada. Si no lo está muestra un mensaje y termina la ejecución.
	 */
	private function compruebaInstanciaInicializada()
	{
    if (! $this->inicializada ) {
      echo "Aplicacion no inicializa";
      exit();
    }
	}

  public function resuelve($path = '')
  {
    $this->compruebaInstanciaInicializada();
    $rutaRaizAppLongitudPrefijo = mb_strlen($this->rutaRaizApp);
    if( mb_substr($path, 0, $rutaRaizAppLongitudPrefijo) === $this->rutaRaizApp ) {
      return $path;
    }

    if (mb_strlen($path) > 0 && mb_substr($path, 0, 1) !== '/') {
      $path = '/' . $path;
    }

    return $this->rutaRaizApp . $path;
  }

  public function doInclude($path = '')
  {
    $this->compruebaInstanciaInicializada();
    $params = array();
    $this->doIncludeInterna($path, $params);
  }
  
  private function doIncludeInterna($path, &$params)
  {
    $this->compruebaInstanciaInicializada();

    if (mb_strlen($path) > 0 && mb_substr($path, 0, 1) !== '/') {
      $path = '/' . $path;
    }

    include($this->dirInstalacion . $path);
  }
  
  public function generaVista(string $rutaVista, &$params)
  {
    $this->compruebaInstanciaInicializada();
    $params['app'] = $this;
    $this->doIncludeInterna($rutaVista, $params);
  }

  public function login(Usuario $user)
  {
    $this->compruebaInstanciaInicializada();
    $_SESSION['login'] = true;
    $_SESSION['nombre'] = $user->username();
    $_SESSION['idUsuario'] = $user->id();
    $_SESSION['roles'] = $user->roles();
  }

  public function logout()
  {
    $this->compruebaInstanciaInicializada();
    //Doble seguridad: unset + destroy
    unset($_SESSION['login']);
    unset($_SESSION['nombre']);
    unset($_SESSION['idUsuario']);
    unset($_SESSION['roles']);


    session_destroy();
    session_start();
  }

  public function usuarioLogueado()
  {
    $this->compruebaInstanciaInicializada();
    return ($_SESSION['login'] ?? false) === true;
  }

  public function nombreUsuario()
  {
    $this->compruebaInstanciaInicializada();
    return $_SESSION['nombre'] ?? '';
  }

  public function idUsuario()
  {
    $this->compruebaInstanciaInicializada();
    return $_SESSION['idUsuario'] ?? '';
  }

  public function tieneRol($rol)
  {
    $this->compruebaInstanciaInicializada();
    $roles = $_SESSION['roles'] ?? array();
    if (! in_array($rol, $roles)) {
      return false;
    }

    return true;
  }

	/**
	 * Devuelve una conexión a la BD. Se encarga de que exista como mucho una conexión a la BD por petición.
	 * 
	 * @return \mysqli Conexión a MySQL.
	 */
  public function conexionBd()
  {
    $this->compruebaInstanciaInicializada();
    if (! $this->conn ) {
      $bdHost = $this->bdDatosConexion['host'];
      $bdUser = $this->bdDatosConexion['user'];
      $bdPass = $this->bdDatosConexion['pass'];
      $bd = $this->bdDatosConexion['bd'];

      $this->conn = new \mysqli($bdHost, $bdUser, $bdPass, $bd);
      if ( $this->conn->connect_errno ) {
        echo "Error de conexión a la BD: (" . $this->conn->connect_errno . ") " . utf8_encode($this->conn->connect_error);
        exit();
      }
      if ( ! $this->conn->set_charset("utf8mb4")) {
        echo "Error al configurar la codificación de la BD: (" . $this->conn->errno . ") " . utf8_encode($this->conn->error);
        exit();
      }
    }
    return $this->conn;
  }

	/**
	 * Añade un atributo <code>$valor</code> para que esté disponible en la siguiente petición bajo la clave <code>$clave</code>.
	 * 
	 * @param string $clave Clave bajo la que almacenar el atributo.
	 * @param any    $valor Valor a almacenar como atributo de la petición.
	 * 
	 */
	public function putAtributoPeticion($clave, $valor)
	{
	  $this->compruebaInstanciaInicializada();
		$atts = null;
		if (isset($_SESSION[self::ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION])) {
			$atts = &$_SESSION[self::ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION];
		} else {
			$atts = array();
			$_SESSION[self::ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION] = &$atts;
		}
		$atts[$clave] = $valor;
	}

	/**
	 * Devuelve un atributo establecido en la petición actual o en la petición justamente anterior.
	 * 
	 * 
	 * @param string $clave Clave sobre la que buscar el atributo.
	 * 
	 * @return any Attributo asociado a la sesión bajo la clave <code>$clave</code> o <code>null</code> si no existe.
	 */
	public function getAtributoPeticion($clave)
	{
    $this->compruebaInstanciaInicializada();
		$result = $this->atributosPeticion[$clave] ?? null;
		if(is_null($result) && isset($_SESSION[self::ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION])) {
			$result = $_SESSION[self::ATTRIBUTO_SESSION_ATTRIBUTOS_PETICION][$clave] ?? null;
		}
		return $result;
	}
}
