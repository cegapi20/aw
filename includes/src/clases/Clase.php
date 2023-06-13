<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Clase
{
    use MagicProperties;

    public const ACRONIMOS_TITULACIONES = [
        "GIC" => "Grado de ingeniería de COMPUTADORES",
        "GII" => "Grado de ingeniería de INFORMATICA",
    ];

    public static function crea($acronimo,$nombre,$acronimo_titulacion,$profesor_id,$estudiantes){
        $clase = new Clase($acronimo,$nombre,$acronimo_titulacion,$profesor_id,null,$estudiantes);
        return $clase->guarda();
    }

    public function guarda()
    {
        return self::inserta($this);
    }

   
   
    public static function buscaPorId($id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM Clases WHERE id=%d", $id);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Clase($fila['acronimo'], $fila['nombre'], $fila['acronimo_titulacion'], $fila['profesor_id'],$fila['id'],null);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function inserta($clase)
    {



        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
       
        $query = sprintf(
            "INSERT INTO Clases(acronimo, nombre, acronimo_titulacion,profesor_id) VALUES ('%s', '%s', '%s','%d')",
            $conn->real_escape_string($clase->acronimo),
            $conn->real_escape_string($clase->nombre),
            $conn->real_escape_string($clase->acronimo_titulacion),
            $conn->real_escape_string($clase->profesor_id)
        );
        if ($conn->query($query)) {
            $clase->id = $conn->insert_id;
            $result = $clase;
            
            foreach($clase->estudiantes as $estudiante){
                $query2 = sprintf(
                    "INSERT INTO EstudiantesClases(clase_id, nombre) VALUES ('%d', '%s')",
                    $conn->real_escape_string($clase->id),
                    $conn->real_escape_string($estudiante)
                );

                if($conn->query($query2)){
                    $conn->insert_id;
                }
            }

        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }


    private static function borra($clase)
    {
        return self::borraPorId($clase->id);
    }

    private static function borraPorId($id)
    {
        if (!$id) {
            return false;
        }
        /* Los roles se borran en cascada por la FK
         * $result = self::borraRoles($usuario) !== false;
         */
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "DELETE FROM Clases U WHERE U.id = %d",
            $id
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    private $id;

    private $acronimo;

    private $nombre;

    private $acronimo_titulacion;

    private $profesor_id;

    private $estudiantes;


    public function __construct($acronimo, $nombre, $acronimo_titulacion,$profesor_id,$id=null,$estudiantes=[])
    {
        $this->id = $id;
        $this->acronimo = $acronimo;
        $this->nombre = $nombre;
        $this->acronimo_titulacion = $acronimo_titulacion;
        $this->profesor_id = $profesor_id;
        $this->estudiantes =$estudiantes;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getAcronimo()
    {
        return $this->acronimo;
    }
    public function getAcronimoTitulacion()
    {
        return $this->acronimo_titulacion;
    }
    public function getEstudiantes()
    {
        return $this->estudiantes;
    }
    public function getNameEstudiantes()
    {
        return implode("</br>",$this->estudiantes);
        // return $this->estudiantes;
    }






    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }
}
