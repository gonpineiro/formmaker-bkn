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
                cargarJsonFile($_POST['idForm'], json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $msg = 'El formulario se envió correctamente';
            } catch (\Throwable $th) {
                $msg = 'Hubo un problema con el envío del formulario';
            }
        } else {
            $msg = 'Hubo un problema con el envío del formulario';
        }

        echo json_encode(['msg' => $msg]);
    }

    /* Consultar un formulario */
    /* if (isset($_POST) && $_POST['type'] === 'get') {
        $formulario = $formController->get(['id' => $_POST['id']]);
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
    } */

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
