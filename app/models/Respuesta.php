<?php

/**
 * This is the model class for table "Respuestas".
 *
 * @property int $id_formulario
 * @property string $deleted_at
 * 
 */
class Respuesta
{
    public $id_formulario;
    public $deleted_at;

    public function __construct()
    {
        $this->id_formulario = "";
        $this->deleted_at = "";
    }

    public function set($id_formulario = null, $deleted_at = null)
    {
        $this->id_formulario = $id_formulario;
        $this->deleted_at = $deleted_at;
    }

    public function save()
    {
        $array = json_decode(json_encode($this), true);
        $conn = new BaseDatos();
        $result = $conn->store(RESPUESTAS, $array);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error al guardar una respuesta';
            cargarLog($this->id_usuario, null, $error, get_class(), __FUNCTION__);
        }
        return $result;
    }

    public static function list($param = [], $ops = [])
    {
        $conn = new BaseDatos();
        $solicitud = $conn->search(RESPUESTAS, $param, $ops);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error al listar las respuestas';
            cargarLog(null, null, $error, get_class(), __FUNCTION__);
        }
        return $solicitud;
    }

    public static function get($params)
    {
        $conn = new BaseDatos();
        $result = $conn->search(RESPUESTAS, $params);
        $solicitud = $conn->fetch_assoc($result);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error a obtener una respuesta: ' . $params[0];
            cargarLog(null, null, $error, get_class(), __FUNCTION__);
        }
        return $solicitud;
    }

    public static function update($res, $id)
    {
        $conn = new BaseDatos();
        $result = $conn->update(RESPUESTAS, $res, $id);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error a modificar una respuesta ' . $id;
            cargarLog(null, $id, $error, get_class(), __FUNCTION__);
        }
        return $result;
    }
}
