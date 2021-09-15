<?php

$url = WEBLOGIN . '/api/getUserByToken/';
try {

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . $_SESSION['token'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $usuario = json_decode($response, true);
    if ($response && $usuario['error'] == null) {
        $datos_personales = $usuario['datosPersonales'];
        $usuario['idusuario_weblogin'] = $usuario['referenciaID'];
        $usuario["correoElectronico"] = $datos_personales["correoElectronico"];
        $usuario["celular"] = $datos_personales["celular"];
        $usuario["wapPersonasId"] = $datos_personales["referenciaID"];
        $usuario["celularVerificado"] = 1;
        $usuario["razonSocial"] = $datos_personales["razonSocial"];
        $usuario["tipoDoc"] = "DNI";
        $usuario["documento"] = $datos_personales["documento"];
        $usuario["genero"] = $datos_personales["genero"]["textID"];
        $usuario["fechaNacimiento"] = $datos_personales["fechaDeNacimiento"];
        //! Posiblemente un array_filter sea mas limpio
        foreach ($usuario["apps"] as $numero => $app) {
            if ($app['id'] == intval($_SESSION['app'])) {
                if ($app['userProfiles'] != $app['standardType']) {
                    if (isset($app['userProfiles'])) {
                        $_SESSION['perfilUsuario'] = $app['userProfiles'];
                    }
                }
                break;
            }
        }
        $_SESSION['usuario'] = $usuario;
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
