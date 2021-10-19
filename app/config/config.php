<?php
session_status() === PHP_SESSION_ACTIVE ?: session_start();

header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate ");

/* Root Path */
include_once 'paths.php';

/* AutoLoad composer & local */
require ROOT_PATH . 'app/utils/funciones.php';
require ROOT_PATH . 'vendor/autoload.php';

/* Carga del DOTENV */
$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

/* Modo produccion: true */
define('PROD', $_ENV['PROD'] == 'true' ? true : false);

/* Entorno */
define('ENV',  $_ENV['APP_ENV']);
switch (ENV) {
    case 'local':
        define('APP_URL', $_ENV['APP_URL_LOCAL']);
        break;
    case 'local':
        define('APP_URL_REPLICA', $_ENV['APP_URL_REPLICA']);
        break;
    case 'local':
        define('APP_URL_PROD', $_ENV['APP_URL_PROD']);
        break;
}

/* AppID */
define('APPID', PROD ? 53 : 55);

/* Token de seguridad */
define('TOKEN', $_ENV['TOKEN']);

/* Configuracion de URLs */
define('WEBLOGIN', PROD ? 'https://weblogin.muninqn.gov.ar' : 'http://200.85.183.194:90');

/* Configuracion del path fIle */
define('PATH_FILE_LOCAL', $_ENV['PATH_FILE_LOCAL'] == 'true' ? true : false);
