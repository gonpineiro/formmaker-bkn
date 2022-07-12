<?php
/*if (isset($_POST['type']) && $_POST['type'] === 'get-all-form') {
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
}*/

if (isset($_POST['type']) && $_POST['type'] === 'get-all-form') {
    
    (isset($_POST['dni']) ? $forms = $formController->getJsonForms($_POST['dni']) : $forms = $formController->getJsonForms() );
    
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
