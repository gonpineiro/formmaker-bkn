<?php

function data_submitted()
{
    $_AAux = array();
    if (!empty($_REQUEST)) {
        $_AAux = $_REQUEST;
    }
    if (count($_AAux)) {
        foreach ($_AAux as $indice => $valor) {
            if ($valor == "") {
                $_AAux[$indice] = 'null';
            }
        }
    }
    return $_AAux;
}
function verEstructura($e)
{
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}

function console_log($data)
{
    echo "<script>";
    echo "console.log('$data')";
    echo "</script>";
}

function verificarSesion()
{
    if (!isset($_SESSION['usuario'])) {
        header('https://weblogin.muninqn.gov.ar');
        exit();
    }
}

function enviarMailApi($address, $body, $subject)
{
    $post_fields = json_encode(['address' => $address, 'subject' => $subject, 'htmlBody' => $body]);

    $uri = "https://weblogin.muninqn.gov.ar/api/Mail";
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

function enviarMailRechazado($address, $solicitante, $observaciones, $idsolicitud)
{
    $body = "<p>Estimado/a " . $solicitante . ", su solicitud (Nº $idsolicitud) para Libreta Sanitaria fue rechazada.</p>
            <p>Motivos: " . $observaciones . "</p>
            <p>Le sugerimos mirar las instrucciones en este <a href='https://www.neuquencapital.gov.ar/wp-content/uploads/2021/05/LIBRETA-SANITARIA-.pdf' target='_blank'>instructivo</a>.</p>
            <p>Cualquier duda o consulta pod&eacute;s enviarnos un email a: <a href='mailto:carnetma@muninqn.gob.ar' target='_blank'>carnetma@muninqn.gob.ar</a></p><p>Direcci&oacute;n Municipal de Calidad Alimentaria</p><p>Municipalidad de Neuquén</p>";


    $subject = "Solicitud de Libreta Sanitaria";
    $post_fields = json_encode(['address' => $address, 'subject' => $subject, 'htmlBody' => $body]);

    $uri = "https://weblogin.muninqn.gov.ar/api/Mail";
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

function getDireccionesParaAdjunto($fileType, $idsolicitud, $adjuntoInputName, $tipo = null)
{
    $path = null;

    if (PATH_FILE_LOCAL) {
        $target_path_local = $tipo != null
            ? "../../../projects_files/formulario_deportes/" . $idsolicitud . "/" . $tipo . "/"
            : "../../../projects_files/formulario_deportes/" . $idsolicitud . "/";
    } else {
        $target_path_local = $tipo != null
            ? "../../../../../../../../../DataServer/replica/projects_files/formulario_deportes/" . $idsolicitud . "/" . $tipo . "/"
            : "../../../../../../../../../DataServer/replica/projects_files/formulario_deportes/" . $idsolicitud . "/";
    }

    if (!file_exists($target_path_local)) {
        mkdir($target_path_local, 0755, true);
    };

    if (!empty($fileType)) {
        $path = $target_path_local . $adjuntoInputName;
        switch ($fileType) {
            case ('image/jpeg'):
                $path = $path . '.jpeg';
                break;
            case ('image/jpg'):
                $path = $path . '.jpg';
                break;
            case ('image/png'):
                $path = $path . '.png';
                break;
            case 'application/pdf':
                $path = $path . '.pdf';
                break;
            case 'image/svg+xml':
                $path = $path . '.svg';
                break;
        }
    };

    return $path;
}

/**
 * Chequea que el tamaño y tipo de archivos subidos sean los correctos
 * JS Alert si no lo son
 * @param int maxsize en mb del archivo, default 200mb
 * @param array formatos aceptados
 * @return bool false si hubo un error en el chequeo de archivos
 */
function checkFile($maxsize = 15, $acceptable = array('application/pdf', 'image/jpeg', 'image/jpg', 'image/gif', 'image/png', 'video/mp4', 'video/mpeg'))
{
    if (isset($_FILES) && !empty($_FILES)) {

        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        $maxsize_multiplied = $maxsize * 1000000;

        foreach ($_FILES as $key => $value) {
            foreach ($value as $key => $array) {
                if ($key == 'size') {
                    foreach ($array as $size) {
                        if (($size >= $maxsize_multiplied) && ($size != 0)) {
                            $_SESSION['errores'] = "Uno de los adjuntos es muy grande. Debe pesar menos de $maxsize megabytes.";
                            return false;
                        }
                    }
                }
                if ($key == 'type') {
                    foreach ($array as $type) {
                        if ((!in_array($type, $acceptable)) && !empty($type)) {
                            $error = "Tipo de archivo invalido. Solamente tipos ";
                            foreach ($acceptable as $val) {
                                $error .= $val . ', ';
                            }
                            $error .= "se aceptan.";
                            $_SESSION['errores'] = $error;
                            return false;
                        }
                    }
                }
                if ($key == 'error') {
                    foreach ($array as $error) {
                        if ($error != 0) {
                            $_SESSION['errores'] =  $phpFileUploadErrors[$error];
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }
}

/**
 * Chequea que el tamaño y tipo de archivos subidos sean los correctos
 * JS Alert si no lo son
 * @param int maxsize en mb del archivo, default 15mb
 * @param array formatos aceptados
 * @return bool false si hubo un error en el chequeo de archivos
 */
function checkFileDp($maxsize = 8, $acceptable = array('application/pdf', 'image/jpeg', 'image/jpg', 'image/gif', 'image/png', 'video/mp4', 'video/mpeg'))
{
    if (isset($_FILES) && !empty($_FILES)) {

        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        $maxsize_multiplied = $maxsize * 1000000;
        foreach ($_FILES as $key => $value) {
            if (($value['size'] >= $maxsize_multiplied) && ($value['size'] != 0)) {
                $_SESSION['errores'] = "Archivo adjunto de " . ucwords($key) . " es muy grande. Debe pesar menos de $maxsize megabytes.";
                return false;
            }
            if ((!in_array($value['type'], $acceptable)) && !empty($value['type'])) {
                $error = "El Tipo de archivo " . ucwords($key)  . " es invalido. Solamente tipos ";
                foreach ($acceptable as $val) {
                    $error .= $val . ', ';
                }
                $error .= "se aceptan.";
                $_SESSION['errores'] = $error;

                return false;
            }
            if ($value['error'] != 0 && !empty($value['type'])) {
                $_SESSION['errores'] = $phpFileUploadErrors[$value['error']];
                return false;
            }
        }
        return true;
    }
}

function utf8_converter($array, $json)
{
    array_walk_recursive($array, function (&$item) {
        $item = utf8_encode($item);
        if ($item == "") $item = null;
    });

    if ($json === true) return json_encode($array);

    return $array;
}

spl_autoload_register(function ($class_name) {
    $directorys = array(
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        CON_PATH . '/'
    );

    foreach ($directorys as $directory) {
        if (file_exists($directory . $class_name . '.php')) {
            include($directory . $class_name . '.php');
            return;
        }
    }
});

/* Obtenemos la imagen de Renaper */
function getImageByRenaper($genero, $dni, $jsonStr = true)
{
    $response = file_get_contents('https://weblogin.muninqn.gov.ar/api/Renaper/waloBackdoor/' . $genero . $dni);
    $json = json_decode($response);
    $imagen = $json->{'docInfo'}->{'imagen'};

    // si la imagen retorna NULL fuerzo su búsqueda con @F al final de la url
    if (is_null($imagen)) {
        $response = file_get_contents('https://weblogin.muninqn.gov.ar/api/Renaper/waloBackdoor/' . $genero . $dni . "@F");
        $json = json_decode($response);
        $imagen = $json->{'docInfo'}->{'imagen'};
    }
    $array['imagen'] = $imagen;
    return utf8_converter($array, $jsonStr);
}

function compararFechas($string, $get, $format = 'Y-m-d')
{
    $now = new DateTime();
    $date = DateTime::createFromFormat($format, $string);

    $array = [
        'now' => $now,
        'date' => $date,
        'dif' => $date->diff($now)->$get,
    ];
    return $array;
}

function convertirABase64($rutaImagen)
{
    $contenidoBinario = file_get_contents($rutaImagen);
    $imagenComoBase64 = base64_encode($contenidoBinario);
    return $imagenComoBase64;
}

function mostrarError($tipo, $detalle = null)
{
    if ($tipo == "store" && $detalle == null) {
        return "Hubo un error con el servidor";
    }

    if ($tipo == 'file' && $detalle != null) {
        return "Guardado del archivo " . $detalle . " fallido, hubo un error con el servidor.";
    }

    if ($tipo == 'dp' && $detalle != null) {
        return "Guardado de adjunto " . $detalle . " fallida, hubo un error con el servidor.";
    }

    if ($tipo == "postFile" && $detalle == null) {
        return "Hubo un error en la carga de los archivos, porfavor intente nuevamente mas tarde.";
    }
}

function formatString(string $string): string
{
    return utf8_encode(trim($string));
}

function formatDate(string $date, string $format = 'd/m/Y'): string
{
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

function verificarUsuario(int $id): void
{
    if (isset($_SESSION['userProfiles']) && $_SESSION['userProfiles'] != $id) {
        header('Location: ' . WEBLOGIN);
        exit();
    }
}

function verFormatoArchivo(string $string): string
{
    $indice = strrpos($string, '.') + 1;
    return substr($string, $indice);
}

function cargarFormularioJson($msg, $uuid): string
{
    $path = FORM_PATH . "/";
    if (!file_exists($path)) mkdir($path, 0755, true);

    $file = fopen($path . $uuid . ".json", 'a') or die("Error creando archivo");
    fwrite($file, $msg) or die("Error escribiendo en el archivo");
    fclose($file);

    return $uuid;
}

function cargarRespuestaJson($idForm, $msg): void
{
    $path = RES_PATH . $idForm . "/";
    if (!file_exists($path)) mkdir($path, 0755, true);

    $file = fopen($path . uniqid() . ".json", 'a') or die("Error creando archivo");
    fwrite($file, $msg) or die("Error escribiendo en el archivo");
    fclose($file);
}
