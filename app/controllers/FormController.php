<?php

class FormController
{
    /* Guarda un formulario */
    public function store($res)
    {
        $form = new Form();
        $form->set(...array_values($res));
        return $form->save();
    }

    /* Busca todos los form */
    public static function index($param = [], $ops = [])
    {
        return Form::list($param, $ops);
    }

    /* Busca un form */
    public static function get($params)
    {
        return Form::get($params);
    }

    /* Busca un form por json*/
    public static function getJson($id)
    {
        $path = ROOT_PATH . "formularios";
        $formularios = scandir($path);
        unset($formularios[0]);
        unset($formularios[1]);

        $string = file_get_contents("$path/$id.json");
        $json = json_decode($string, true);

        return $json;
    }

    /* Busca ids de los json*/
    public static function getJsonForms($dni = null)
    {
        $formularios = scandir(ROOT_PATH . 'formularios');
        unset($formularios[0]);
        unset($formularios[1]);

        $forms = [];
        foreach ($formularios as $form) {
            $agregarForm = true;

            $formPath = ROOT_PATH . "formularios/$form";
            $id = pathinfo($formPath, PATHINFO_FILENAME);
            $string = file_get_contents($formPath);
            $json = json_decode($string, true);

            /*
            print_r($json);
            echo $dni;
            echo $json['dni'];
            */

            ($dni != null && (isset($json['dni']) && $dni != $json['dni']) ? $agregarForm = false : $agregarForm = true );

            if($agregarForm){
                $respuestasPath = ROOT_PATH . "respuestas/$id";
                $respuestas = scandir($respuestasPath);
                $respuestasCount = 0;
                if ($respuestas) {
                    unset($respuestas[0]);
                    unset($respuestas[1]);
                    $respuestasCount = count($respuestas);
                }
    
                array_push($forms, [
                    'id' => $id,
                    'nombre' => $json['nombre'],
                    'estado' => $json['estado'],
                    'respuestas' => $respuestasCount
                ]);
            }
        }
        return $forms;
    }

    /* Actualiza un form */
    public static function update($res, $id)
    {
        return Form::update($res, $id);
    }
}
