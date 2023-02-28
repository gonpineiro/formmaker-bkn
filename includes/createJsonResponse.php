<?php

if (isset($_POST['type']) && $_POST['type'] === 'respuesta') {
    $succesMsg = 'El formulario se envió correctamente';
    $errorMsg = 'Hubo un problema con el envío del formulario';
    if ($_POST['idForm'] != null) {
        $idForm = $_POST['idForm'];
        $path = ROOT_PATH . "formularios\\$idForm.json";
        $form = json_decode(file_get_contents($path), true);

        $sendMail = "Correo NO enviado | No se configuro email o mensaje en el formulario";
        if (isset($_POST["formObject"]['Mail']) && isset($form['bodyEmail'])) {
            $nombreForm = $form['nombre'];
            $email = $_POST["formObject"]['Mail'];
            $result = enviarMailApi($email, $form['bodyEmail'], $nombreForm);
            if ($result['error'] == null) {
                $sendMail = 'Correo enviado';
            } else {
                $sendMail = "Correo NO enviado | " . $result['error'];
            }
        }

        $_POST["formObject"]['sendEmail'] = $sendMail;
        $data = [
            'idForm' => $idForm,
            'fecha' => date('Y-m-d H:i:s'),
            'nombre' => $_POST['nombre'],
            'respuestas' => $_POST["formObject"],
        ];
        try {
            //agregado para evitar que las ñ rompan el json_encode
            $json = json_encode(utf8ize($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            cargarRespuestaJson($idForm,  $json);
            $msg = $succesMsg;
        } catch (\Throwable $th) {
            $msg = $errorMsg;
        }
    } else {
        $msg = $errorMsg;
    }

    echo json_encode(['msg' => $msg]);
    exit();
}
