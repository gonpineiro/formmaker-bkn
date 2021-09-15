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
        $string = file_get_contents(ROOT_PATH . '.preguntas.json');
        $json = json_decode($string, true);
        $json = array_filter($json, function ($form) use ($id) {
            return $form['id'] == $id;
        });
        return array_values($json)[0];
    }

    /* Busca ids de los json*/
    public static function getJsonForms()
    {
        $string = file_get_contents(ROOT_PATH . '.preguntas.json');
        $json = json_decode($string, true);
        $forms = [];
        foreach ($json as $item) {
            array_push($forms, [
                'id' => $item['id'],
                'nombre' => $item['nombre']
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
