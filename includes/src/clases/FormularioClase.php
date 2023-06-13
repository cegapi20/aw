<?php

namespace es\ucm\fdi\aw\clases;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioClase extends Formulario
{
    public function __construct()
    {
        parent::__construct('formClase', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/misClases.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $acronimo = $datos['acronimo'] ?? '';
        $nombre = $datos['nombre'] ?? '';
        $acronimo_titulacion = $datos['acronimo_titulacion'] ?? '';
        $profesor_id =$datos['profesor_id'] ?? '';
        $estudiantes =$datos['estudiantes'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['acronimo', 'nombre', 'acronimo_titulacion', 'profesor_id','estudiantes'], $this->errores, 'span', array('class' => 'error'));

        $acronimos_titulaciones = Clase::ACRONIMOS_TITULACIONES;
        $options='';
        foreach ($acronimos_titulaciones as $key => $tr) {
            $options .=  '<option value="'.$key.'" >'.$tr.'</option>';
        }
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos para el registro de la clase</legend>
            <div>
                <label for="acronimo">Acronimo:</label>
                <input id="acronimo" type="text" name="acronimo" value="$acronimo" />
                {$erroresCampos['acronimo']}
            </div>

            <div>
                <label for="nombre">Nombre:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" />
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="acronimo_titulacion">Titulación:</label>
                <select id="acronimo_titulacion" name="acronimo_titulacion" value="GIC">
                    {$options}
                </select> </br>
                {$erroresCampos['acronimo_titulacion']}
            </div>
            <div>
                <label for="estudiantes">Lista de estudiantes</label>
                <textarea id="estudiantes" name="estudiantes" rows="10" cols="50" value="$estudiantes"></textarea>
                </br>
                {$erroresCampos['estudiantes']}
            </div>

            <div>
                <button type="submit" name="registro">Registrar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }


    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $acronimo = trim($datos['acronimo'] ?? '');
        $acronimo = filter_var($acronimo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$acronimo || mb_strlen($acronimo) < 1 || mb_strlen($acronimo) > 6 ) {
            $this->errores['acronimo'] = 'El acronimo tiene  que tener una longitud entre 1-6 caracteres.';
        }

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$nombre || mb_strlen($nombre) < 5 || mb_strlen($nombre) > 30) {
            $this->errores['nombre'] = 'El nombre tiene que tener una longitud de al menos 5 caracteres.';
        }

        $acronimo_titulacion = trim($datos['acronimo_titulacion'] ?? '');
        $acronimo_titulacion = filter_var($acronimo_titulacion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $estudiantes = $datos['estudiantes']?? '';
        // $estudiantes = preg_split('/\s+/', $estudiantes);
        // $estudiantes = explode("\r\n.", json_encode($estudiantes));
        $estudiantes = preg_split('/\s+/', $estudiantes, -1, PREG_SPLIT_NO_EMPTY);
        if(count($estudiantes)<1){
            $this->errores['estudiantes'] = 'Añade al menos 1 estudiante.';
        }
        if (count($this->errores) === 0) {
                $clase = Clase::crea($acronimo, $nombre, $acronimo_titulacion,4,$estudiantes);
        }
    }
}
