<?php
if (isset($_POST['type']) && $_POST['type'] === 'get') {
    $formulario = $formController->getJson($_POST['id']);
    if ($formulario) {
        $formulario['error'] = null;
        echo json_encode($formulario, true);
        exit();
    } else {
        $error = [
            'error' =>  'Recurso no encontrado',
            'idForm' => $_POST['id'],
        ];
        echo json_encode($error, true);
        exit();
    }
}
