<?php

$routes = new \Klein\Klein();

session_save_path(__DIR__.'/../tmp');

$sessionID = $routes->service()->startSession();

include_once __DIR__.'/site.php';

include_once __DIR__.'/admin.php';

$routes->dispatch();
