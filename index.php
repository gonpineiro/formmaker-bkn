<?php

include('./app/config/config.php');
if (!PROD) {
    header('Access-Control-Allow-Origin: http://localhost:3000');
}
header('Access-Control-Allow-Headers: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$formController = new FormController();

if (isset($_POST) &&  $_POST['token'] === TOKEN) {

    /* Crear un formulario */
    if (isset($_POST) && $_POST['type'] === 'post') {
        $formController->store($_POST['formulario']);
        echo json_encode($_POST['formulario'], true);
    }

    /* Creando formulario */
    if (isset($_POST) && $_POST['type'] === 'post-form-json') {
        unset($_POST['token']);
        unset($_POST['type']);
        try {
            $json = json_encode($_POST, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            cargarFormularioJson($json);
            $msg = 'El formulario se creo correctamente';
        } catch (\Throwable $th) {
            $msg = 'Hubo un problema en la creacion del formulario';
        }
        echo json_encode(['msg' => $msg]);
        exit();
    }

    /* Respuesta de un formulario */
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

    /* Obtenemos un formulario */
    if (isset($_POST) && $_POST['type'] === 'get') {
        $formulario = $formController->getJson($_POST['id']);
        if ($formulario) {
            $formulario['error'] = null;
            echo json_encode($formulario, true);
        } else {
            $error = [
                'error' =>  'Recurso no encontrado',
                'idForm' => $_POST['id'],
            ];
            echo json_encode($error, true);
        }
    }

    /* Obtenemos todos los formularios */
    if (isset($_POST) && $_POST['type'] === 'get-all-form') {
        $forms = $formController->getJsonForms();
        if ($forms) {
            $forms['error'] = null;
            echo json_encode($forms, true);
        } else {
            $error = [
                'error' =>  'Error a obtener los forms de formularios',
            ];
            echo json_encode($error, true);
        }
    }
}
exit();
