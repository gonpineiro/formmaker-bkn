<?php
if (isset($_POST) && $_POST['type'] === 'post') {
    $formController->store($_POST['formulario']);
    echo json_encode($_POST['formulario'], true);
    exit();
}
