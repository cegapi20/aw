<?php

namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion as App;

class Usuario
{
  const ROLES = array('user' => 1, 'admin' => 2);

  public static function login($username, $password)
  {
    $user = self::buscaUsuario($username);
    if ($user && $user->compruebaPassword($password)) {
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      $query = sprintf("SELECT R.nombre FROM RolesUsuario RU, Roles R WHERE RU.rol = R.id AND RU.usuario=%s", $conn->real_escape_string($user->id));
      $rs = $conn->query($query);
      if ($rs) {
        while($fila = $rs->fetch_assoc()) { 
          $user->addRol($fila['nombre']);
        }
        $rs->free();
      }
      return $user;
    }    
    return false;
  }

  public static function buscaUsuario($username)
  {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Usuarios WHERE username='%s'", $conn->real_escape_string($username));
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows == 1) {
      $fila = $rs->fetch_assoc();
      $user = new Usuario($fila['username'], $fila['password'], $fila['id']);
      $rs->free();

      return $user;
    }
    return false;
  }

  public static function buscaPorId($idUsuario)
  {
    $app = App::getSingleton();
    $conn = $app->conexionBd();
    $query = sprintf("SELECT * FROM Usuarios WHERE id=%d", $idUsuario);
    $rs = $conn->query($query);
    if ($rs && $rs->num_rows == 1) {
      $fila = $rs->fetch_assoc();
      $user = new Usuario($fila['username'], $fila['password'], $fila['id']);
      $rs->free();

      return $user;
    }
    return false;
  }
    
  public static function crea($nombreUsuario, $password, $rol)
  {
      $user = new Usuario($nombreUsuario, self::hashPassword($password));
      $user->addRol($rol);
      return self::guarda($user);
  }
  
  private static function hashPassword($password)
  {
      return password_hash($password, PASSWORD_DEFAULT);
  }
  
  public static function guarda($usuario)
  {
      if ($usuario->id !== null) {
          return self::actualiza($usuario);
      }
      return self::inserta($usuario);
  }
  
  private static function inserta($usuario)
  {
      $result = false;
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      $query=sprintf("INSERT INTO Usuarios(username, password) VALUES('%s', '%s')"
          , $conn->real_escape_string($usuario->username)
          , $conn->real_escape_string($usuario->password)
      );
      if($conn->query($query)) {
          $usuario->id = $conn->insert_id;
          $result = self::guardaRoles($usuario);
        } else {
          error_log("Error al insertar en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error));
      }
      return $result;
  }
  
  private static function actualiza($usuario)
  {
      $result = false;
      $app = App::getSingleton();
      $conn = $app->conexionBd();
      $query=sprintf("UPDATE Usuarios U SET username = '%s', password='%s' WHERE U.id=%i"
          , $conn->real_escape_string($usuario->username)
          , $conn->real_escape_string($usuario->password)
      );
      if ( $conn->query($query) ) {
          if ( $conn->affected_rows != 1) {
              error_log("No se ha podido actualizar el usuario: " . $usuario->id);
          } else {
              $result = self::guardaRoles($usuario);
          }
      } else {
          error_log("Error al a en la BD: (" . $conn->errno . ") " . utf8_encode($conn->error));
      }
      
      return $result;
  }
  
  private static function guardaRoles($usuario)
  {
      $result = false;
      $app = App::getSingleton();
      $conn = $app->conexionBd();


      // Borramos todos los roles del usuario (si los tiene)
      $query = sprintf("DELETE FROM RolesUsuario WHERE usuario = %d"
          , $usuario->id
      );
      if (!$conn->query($query)){
          error_log("No se han podido borrar los roles existentes (" . $conn->errno . ") " . utf8_encode($conn->error));
      } else {
          // Convertimos los roles a ids
          $idRoles = array();
          foreach($usuario->roles as $nombreRol) {
              $idRoles [] = self::ROLES[$nombreRol];
          }

          // Insertamos los roles
          $ok = true;
          foreach($idRoles as $rol) {
              $query = sprintf("INSERT INTO RolesUsuario (usuario, rol) VALUES (%d, %d)"
                  , $usuario->id
                  , $rol
              );
              if (!$conn->query($query)){
                  error_log("No se han podido borrar los roles existentes (" . $conn->errno . ") " . utf8_encode($conn->error));
                  $ok = false;
                  break;
              }
          }

          if ($ok ) {
              $result = $usuario;
          }
      }

      return $result;
  }

  private $id;

  private $username;

  private $password;

  private $roles;

  private function __construct($username, $password, $id = NULL)
  {
    $this->id = $id;
    $this->username = $username;
    $this->password = $password;
    $this->roles = [];
  }

  public function id()
  {
    return $this->id;
  }

  public function addRol($role)
  {
    $this->roles[] = $role;
  }

  public function roles()
  {
    return $this->roles;
  }

  public function username()
  {
    return $this->username;
  }

  public function compruebaPassword($password)
  {
    return password_verify($password, $this->password);
  }

  public function cambiaPassword($nuevoPassword)
  {
    $this->password = self::hashPassword($nuevoPassword);
  }
}
