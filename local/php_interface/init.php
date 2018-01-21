<?php

require_once realpath(__DIR__).'/../../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(realpath(__DIR__.'/../../'));
$dotenv->load();

Arrilot\BitrixModels\ServiceProvider::register();
Arrilot\BitrixModels\ServiceProvider::registerEloquent();

Bex\Monolog\MonologAdapter::loadConfiguration();

include_once 'events.php';