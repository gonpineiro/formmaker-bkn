<?php
if (isset($_POST) && $_POST['type'] === 'get-all-form') {
    $forms = $formController->getJsonForms();
    if ($forms) {
        $forms['error'] = null;
        echo json_encode($forms, true);
        exit();
    } else {
        $error = [
            'error' =>  'Error a obtener los forms de formularios',
        ];
        echo json_encode($error, true);
        exit();
    }
}
