<?php

return array (
    'maximaster' => array(
        'value' => array(
            'tools' => array(
                'twig' => array(
                    // Режим отладки выключен
                    'debug' => env('DEBUG', false),

                    //Кодировка соответствует кодировке продукта
                    'charset' => SITE_CHARSET,

                    //кеш хранится в уникальной директории. Должен быть полный абсолютный путь
                    'cache' => $_SERVER['DOCUMENT_ROOT'] . '/bitrix/cache/maximaster/tools.twig',

                    //Автообновление включается только в момент очистки кеша
                    'auto_reload' => isset( $_GET[ 'clear_cache' ] ) && strtoupper($_GET[ 'clear_cache' ]) == 'Y',

                    //Автоэскейп отключен, т.к. битрикс по-умолчанию его сам делает
                    'autoescape' => false,

                    // Переменные arResult будут доступны не в result, а напрямую
                    'extract_result' => false,
                )
            )
        )
    ),

    'monolog' => array(
        'value' => array(
            'handlers' => array(
                'default' => array(
                    'class' => '\Monolog\Handler\StreamHandler',
                    'level' => 'DEBUG',
                    'stream' => env('LOG_FILE_PATH')
                ),
                'feedback_event_log' => array(
                    'class' => '\Bex\Monolog\Handler\BitrixHandler',
                    'level' => 'DEBUG',
                    'event' => 'TYPE_FOR_EVENT_LOG',
                    'module' => 'awesome.module'
                ),
            ),
            'loggers' => array(
                'app' => array(
                    'handlers'=> array('default'),
                ),
                'feedback' => array(
                    'handlers'=> array('feedback_event_log'),
                )
            )
        ),
        'readonly' => false
    )
);
