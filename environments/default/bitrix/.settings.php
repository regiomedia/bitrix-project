<?php


if (!file_exists($autoloadPath = realpath(__DIR__).'/../vendor/autoload.php')) {

    $autoloadPath = realpath(__DIR__).'/../../current/vendor/autoload.php';
}

require_once($autoloadPath) ;

$dotenv = new Dotenv\Dotenv(realpath(__DIR__.'/../'));
$dotenv->load();

$cache = array (
    'value' => array (
        'type' => 'files',
    ),
    'readonly' => false,
);


if (env('USE_MEMCACHE')) {
    $cache = array(
        'value' =>
            array(
                'type' => 'memcache',
                'memcache' => array(
                    'host' => '127.0.0.1',
                    'port' => '11211',
                    'sid' => $_SERVER["DOCUMENT_ROOT"].'#site01',
                ),
            ),
        'readonly' => false
    );
}


return array (
    'utf_mode' =>
        array (
            'value' => true,
            'readonly' => true,
        ),
    'cache_flags' =>
        array (
            'value' =>
                array (
                    'config_options' => 3600.0,
                    'site_domain' => 3600.0,
                ),
            'readonly' => false,
        ),
    'cookies' =>
        array (
            'value' =>
                array (
                    'secure' => false,
                    'http_only' => true,
                ),
            'readonly' => false,
        ),
    'exception_handling' =>
        array (
            'value' =>
                array (
                    'debug' => env('DEBUG', false),
                    'handled_errors_types' => 4437,
                    'exception_errors_types' => 4437,
                    'ignore_silence' => false,
                    'assertion_throws_exception' => true,
                    'assertion_error_type' => 256,
                    'log' => array(
                        'class_name' => '\Bex\Monolog\ExceptionHandlerLog',
                        'settings' => array(
                            'logger' => 'app'
                        ),
                    ),
                ),
            'readonly' => false,
        ),
    'connections' =>
        array (
            'value' =>
                array (
                    'default' =>
                        array (
                            'className' => '\\Bitrix\\Main\\DB\\MysqliConnection',
                            'host' => env('DB_HOST'),
                            'database' => env('DB_NAME'),
                            'login' => env('DB_LOGIN'),
                            'password' => env('DB_PASSWORD'),
                            'options' => 2.0,
                        ),
                ),
            'readonly' => true,
        ),
    'cache' => $cache,
);
