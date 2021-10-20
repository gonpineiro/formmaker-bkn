<?php
if (isset($_POST['type']) && $_POST['type'] === 'post') {
    $formController->store($_POST['formulario']);
    echo json_encode($_POST['formulario'], true);
    exit();
}
