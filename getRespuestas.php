<?PHP
/* 
localhost/github/formmaker-bkn/getRespuestas.php?formulario=2&nombreForm=encuestaExpo
*/

if (!empty($_GET['formulario']) && !empty($_GET['nombreForm'])) {
    $path = "./respuestas/";
    $fecha = date("Y-m-d H_i_s");
    $carpeta = $path . $_GET['formulario'];
    $nombreForm = $_GET['nombreForm'];
    // nombre archivo json
    $json_filename = "resultados_" . $nombreForm . "-" . $fecha  . '.json';
    // nombre archivo csv
    $csv_filename = "resultados_" . $nombreForm . "-" . $fecha  . '.csv';
    getDatosDeRespuestas($carpeta, $json_filename);
    jsonACSV($json_filename, $csv_filename);
    descargarCsv($csv_filename, $json_filename);
} else {
    echo "No están todos los datos en el get.";
}

function getDatosDeRespuestas($carpeta, $json_filename)
{
    $respuestasJson = [];
    // busco todos los archivos dentro de la carpeta con el id del formulario
    foreach (glob($carpeta . "/*.json") as $filename) {
        $respuestasJson[] = file_get_contents($filename);
    }
    // obtengo solamente los "resultados" dentro de cada json de respuesta
    foreach ($respuestasJson as $unaRespuesta) {
        $json = json_decode($unaRespuesta);
        $respuestas[] = $json->respuestas;
    }
    $results = json_encode($respuestas, JSON_PRETTY_PRINT);
    // se crea el archivo json con solo las respuestas de los encuestados/ usuarios
    if (!file_put_contents($json_filename, $results)) {
        die("no se creó el archivo");
    }
    return;
}



function jsonACSV($json_filename, $csv_filename)
{
    if (($json = file_get_contents($json_filename)) == false)
        die('error leyendo el json');

    $data = json_decode($json, true);
    $fp = fopen($csv_filename, 'w');
    $header = false;
    //print_r($data);
    foreach ($data as $row) {
        //print_r($row);
        $row = limpiarCommas($row);
        if (empty($header)) {
            $header = array_keys($row);
            fputcsv($fp, $header);
            $header = array_flip($header);
        }
        fputcsv($fp, array_merge($header, $row));
    }
    fclose($fp);
    return;
}

function limpiarCommas($unaFila){
    foreach ($unaFila as $key => $unValor) {
        /*echo "\n\n";
        echo "Valor sin modificar: ".$unValor;
        echo "\n";
        echo "Valor sin modificar: ".$unaFila[$key];
        echo "\n\n";*/
        $unValor = str_replace(',', ' ', $unValor);
        $unaFila[$key] = $unValor;
        /*echo "Valor quitando commas: ".$unValor;
        echo "\n";
        echo "Valor quitando commas: ".$unaFila[$key];*/
    }
    return $unaFila;
}

function descargarCsv($csv_filename, $json_filename)
{
    if (file_exists($csv_filename)) {
        $fileName = basename($csv_filename);
        $fileSize = filesize($csv_filename);

        // Output headers.
        header("Cache-Control: private");
        header("Content-Type: application/stream");
        header("Content-Length: " . $fileSize);
        header("Content-Disposition: attachment; filename=" . $fileName);
        //header('Content-Encoding: UTF-8');
        //header('Content-type: text/csv; charset=UTF-8');
        echo "\xEF\xBB\xBF";

        // Output file.
        readfile($csv_filename);
        borrarArchivo($csv_filename);
        borrarArchivo($json_filename);
        exit();
    } else {
        die('El path o ruta no es válida.');
    }
}
function borrarArchivo($archivo)
{
    if (!unlink($archivo)) {
        //echo ("$archivo No se pudo borrar");
    } else {
        //echo ("$archivo se borró");
    }
}
