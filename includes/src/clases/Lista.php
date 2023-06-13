<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\Clase;

class Lista
{
    private $clases = [];

    static public function generaFilasTabla($clases_array){
        $html_filas='';
        foreach ($clases_array as $value) {
            $clase = $value;
            $html_filas .= '<tr>
            <td >'.$value->getAcronimo().'('.$value->getAcronimoTitulacion().')</td>
            <td >'.$value->getNombre().'</td>
            <td > '.count($value->getEstudiantes()).'</td>
            <td > '.$value->getNameEstudiantes().'</td>
          </tr>';
        }
        return $html_filas;
    }

    static public function getClaseAll($profesor_id){
        $clases_array = [];

        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "SELECT *  FROM Clases CL WHERE CL.profesor_id=%d",
            $profesor_id
        );
        $rs = $conn->query($query);
        if ($rs) {
            $clases = $rs->fetch_all(MYSQLI_ASSOC);
            $rs->free();

            $clases_array = [];
            foreach ($clases as $clase) {
                $query2 = sprintf(
                    "SELECT *  FROM EstudiantesClases EC WHERE EC.clase_id=%d",
                    $clase['id']
                );
                $estudiantes_array=[];
                $rs2 = $conn->query($query2);
                if($rs2){
                    $estudiantes = $rs2->fetch_all(MYSQLI_ASSOC);
                    $rs2->free();
                    foreach ($estudiantes as $estudiante) {
                        $estudiantes_array[] = $estudiante['nombre'];
                    }
                }


                $modelClase = new Clase ($clase['acronimo'],$clase['nombre'],$clase['acronimo_titulacion'],$clase['profesor_id'],$clase['id'],$estudiantes_array);
                $clases_array[]= $modelClase;
            }
            return $clases_array;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return false;
    }
}
