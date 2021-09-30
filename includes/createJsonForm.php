<?php
if (isset($_POST) && $_POST['type'] === 'post-form-json') {
    unset($_POST['token']);
    unset($_POST['type']);
    try {
        $json = json_encode($_POST, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        cargarFormularioJson($json);
        $msg = 'El formulario se creo correctamente';
    } catch (\Throwable $th) {
        $msg = 'Hubo un problema en la creacion del formulario';
    }
    echo json_encode(['msg' => $msg, 'error' => null]);
    exit();
}
