<?php

class RespuestaController
{
    /* Guarda una respuesta */
    public function store($res)
    {
        $form = new Respuesta();
        $form->set(...array_values($res));
        return $form->save();
    }

    /* Busca todas las respuesta */
    public static function index($param = [], $ops = [])
    {
        return Respuesta::list($param, $ops);
    }

    /* Busca una respuesta */
    public static function get($params)
    {
        return Respuesta::get($params);
    }

    /* Actualiza una respuesta */
    public static function update($res, $id)
    {
        return Respuesta::update($res, $id);
    }
}
