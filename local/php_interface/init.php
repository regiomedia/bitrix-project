<?php

require_once realpath(__DIR__).'/../../vendor/autoload.php';

Arrilot\BitrixModels\ServiceProvider::register();
Arrilot\BitrixModels\ServiceProvider::registerEloquent();

include_once 'events.php';