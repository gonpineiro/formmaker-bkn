<?php

include('./app/config/config.php');
if (!PROD) {
    header('Access-Control-Allow-Origin: http://localhost:3000');
}
header('Access-Control-Allow-Headers: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

$formController = new FormController();

if (isset($_POST) && $_POST['token'] === TOKEN) {

    /* Crear un formulario */
    include('./includes/createDbForm.php');

    /* Creando formulario | post-form-json */
    include('./includes/createJsonForm.php');

    /* Respuesta de un formulario | respuesta*/
    include('./includes/createJsonResponse.php');

    /* Obtenemos un formulario | get */
    include('./includes/getJsonForm.php');

    /* Obtenemos todos los formularios | get-all-form */
    include('./includes/getAllJsonForms.php');
}
exit();
