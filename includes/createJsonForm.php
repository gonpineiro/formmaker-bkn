<?php
if (isset($_POST['type']) && $_POST['type'] === 'post-form-json') {
    unset($_POST['token']);
    unset($_POST['type']);
    
    /*
    $contentlength_bytes = mb_strlen(strstr($http, "\r\n\r\n"), 'latin1') - 4;
    echo "sisi: ".$contentlength_bytes;
    die();*/
    try {
        $_POST['fecha'] = date('Y-m-d H:i:s');
        $uuid = uniqid();
        $_POST['url'] = APP_URL . '?idForm=' . $uuid;

        // echo "jaja";
        // die;
        $json = json_encode(utf8ize($_POST), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        cargarFormularioJson($json, $uuid);
        $msg = 'El formulario se creo correctamente';
    } catch (\Throwable $th) {
        $msg = 'Hubo un problema en la creacion del formulario';
        // $msg += "$th";
        // print_r($th);
        // die;
        $uuid = null;
    }
    echo json_encode(['msg' => $msg, 'uuid' => $uuid, 'error' => null]);
    exit();
}
