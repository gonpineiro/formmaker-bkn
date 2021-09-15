<?php

/**
 * This is the model class for table "Formulario".
 *
 * @property int $id_usuario
 * @property int $string
 * @property int $estado
 * @property string $deleted_at
 * 
 */
class Form
{
    public $id_usuario;
    public $string;
    public $estado;
    public $deleted_at;

    public function __construct()
    {
        $this->id_usuario = "";
        $this->string = "";
        $this->estado = "";
        $this->deleted_at = "";
    }

    public function set($id_usuario = null, $string = null, $estado = null, $deleted_at = null)
    {
        $this->id_usuario = $id_usuario;
        $this->string = $string;
        $this->estado = $estado;
        $this->deleted_at = $deleted_at;
    }

    public function save()
    {
        $array = json_decode(json_encode($this), true);
        $conn = new BaseDatos();
        $result = $conn->store(FORMULARIOS, $array);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error al guardar un formulario';
            cargarLog($this->id_usuario, null, $error, get_class(), __FUNCTION__);
        }
        return $result;
    }

    public static function list($param = [], $ops = [])
    {
        $conn = new BaseDatos();
        $solicitud = $conn->search(FORMULARIOS, $param, $ops);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error al listar los formularios';
            cargarLog(null, null, $error, get_class(), __FUNCTION__);
        }
        return $solicitud;
    }

    public static function get($params)
    {
        $conn = new BaseDatos();
        $result = $conn->search(FORMULARIOS, $params);
        $solicitud = $conn->fetch_assoc($result);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error a obtener un formulario: ' . $params[0];
            cargarLog(null, null, $error, get_class(), __FUNCTION__);
        }
        return $solicitud;
    }

    public static function update($res, $id)
    {
        $conn = new BaseDatos();
        $result = $conn->update(FORMULARIOS, $res, $id);

        /* Guardamos los errores */
        if ($conn->getError()) {
            $error =  $conn->getError() . ' | Error a modificar un formulario ' . $id;
            cargarLog(null, $id, $error, get_class(), __FUNCTION__);
        }
        return $result;
    }
}
