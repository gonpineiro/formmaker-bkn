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

/* AppID */
define('APPID', PROD ? 53 : 55);

/* Token de seguridad */
define('TOKEN', $_ENV['TOKEN']);

/* Configuracion de URLs */
define('WEBLOGIN', PROD ? 'https://weblogin.muninqn.gov.ar' : 'http://200.85.183.194:90');

/* Configuracion base de datos */
define('DB_HOST', PROD ? $_ENV['DB_HOST'] : '128.53.15.3');
define('DB_USER',  PROD ? $_ENV['DB_USER'] : 'userturnos');
define('DB_PASS',  PROD ? $_ENV['DB_PASS'] : 'turnero16');
define('DB_NAME',  PROD ? $_ENV['DB_NAME'] : 'infoprueba');
define('DB_PORT',  PROD ? $_ENV['DB_PORT'] : '3306');
define('DB_CHARSET',  PROD ? $_ENV['DB_CHARSET'] : 'utf8');

/* Configuracion del path fIle */
define('PATH_FILE_LOCAL', $_ENV['PATH_FILE_LOCAL'] == 'true' ? true : false);

/* Configuración de tablas */
define('FORMULARIOS', $_ENV['DB_FORMULARIO_TABLE']);
define('RESPUESTAS', $_ENV['DB_RESPUESTAS_TABLE']);
define('LOG', $_ENV['DB_LOG_TABLE']);
