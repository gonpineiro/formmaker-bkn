<?php
if (isset($_POST) && $_POST['type'] === 'respuesta') {
    if ($_POST['idForm'] != null) {
        $data = [
            'idForm' => $_POST['idForm'],
            'fecha' => date('Y-m-d H:i:s'),
            'nombre' => $_POST['nombre'],
            'respuestas' => $_POST["formObject"],
        ];
        try {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            cargarRespuestaJson($_POST['idForm'],  $json);
            $msg = 'El formulario se envió correctamente';
        } catch (\Throwable $th) {
            $msg = 'Hubo un problema con el envío del formulario';
        }
    } else {
        $msg = 'Hubo un problema con el envío del formulario';
    }
    echo json_encode(['msg' => $msg]);
    exit();
}
