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
    public static function getJsonForms()
    {
        $formularios = scandir(ROOT_PATH . 'formularios');
        unset($formularios[0]);
        unset($formularios[1]);

        $forms = [];
        foreach ($formularios as $form) {
            $path = ROOT_PATH . "formularios/$form";
            $string = file_get_contents($path);
            $json = json_decode($string, true);
            array_push($forms, [
                'id' => $json['id'],
                'nombre' => $json['nombre']
            ]);
        }
        return $forms;
    }

    /* Actualiza un form */
    public static function update($res, $id)
    {
        return Form::update($res, $id);
    }
}
