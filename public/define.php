<?php
ini_set('xdebug.max_nesting_level', 500);

define('VERSION', '1.7.4');

define('NCACHE', 'cache=' . sha1(date('Ymd') . VERSION));

define('PATH_UPLOAD', 'uploads/');
define('PATH_FILE', 'file/');
define('PATH_FILE_RELATORIO', PATH_FILE . 'relatorio/');
define('PATH_FILE_IMPORTACAO', PATH_FILE . 'importacao/');

define('PAGINATION_PAGES', 10);

define('AWS_S3_URL', 'https://s3-us-west-2.amazonaws.com/ciesglobal/');

define('CACHE_SHORT', '10');
define('CACHE_MID', '60');
define('CACHE_LONG', '360');
define('CACHE_DAY', '1440');
define('CACHE_WEEK', '10080');
define('DATE_INICIO_SISTEMA', '2017-03-01 00:00:00');

$_env = "PRODUCAO";

if (empty($_SERVER['SERVER_NAME']) && !empty($_SERVER['HTTP_HOST'])) {
    $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];
}

if (!empty($_SERVER['SERVER_NAME'])) {
    $uri = explode(".", $_SERVER['SERVER_NAME']);
    switch ($uri[0]) {
        case "local" :
        case "dev" :
            $_env = "DEV";
            break;
        case "homolog" :
        case "homologacao" :
        case "sandbox" :
            $_env = "TESTE";
            break;
        case "sistema" :
            $_env = "PRODUCAO";
            break;
        default :
            $_env = "PRODUCAO";
            break;
    }
}

define('ENV_SISTEMA', $_env);

if ($_env != "PRODUCAO") {
    ini_set('display_errors', 1);
    ini_set('display_startup_erros', 1);
    error_reporting(E_ALL);
}