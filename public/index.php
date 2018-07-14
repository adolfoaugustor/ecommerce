<?php

    spl_autoload_register(function($className) {

        $file = __DIR__.'/../'.str_replace('\\', '/', $className).'.php';

        if (!file_exists($file)) return false;

        include_once __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
    });

    require_once __DIR__.'/../vendor/autoload.php';

    include_once __DIR__.'/../helpers/base.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once __DIR__.'/../routes/web.php';
