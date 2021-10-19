<?php
if (isset($_POST) && $_POST['type'] === 'post-form-json') {
    unset($_POST['token']);
    unset($_POST['type']);
    try {
        $_POST['fecha'] = date('Y-m-d H:i:s');
        $uuid = uniqid();
        $_POST['url'] = APP_URL_PROD . '?idForm=' . $uuid;

        $json = json_encode($_POST, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        cargarFormularioJson($json, $uuid);
        $msg = 'El formulario se creo correctamente';
    } catch (\Throwable $th) {
        $msg = 'Hubo un problema en la creacion del formulario';
        $uuid = null;
    }
    echo json_encode(['msg' => $msg, 'uuid' => $uuid, 'error' => null]);
    exit();
}
