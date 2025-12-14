<?php


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Core/bootstrap.php';


use Bramus\Router\Router;

$router = new Router();

require __DIR__ . '/../config/routes/web.php';
require __DIR__ . '/../config/routes/api.php';


// Ejecutar router
$router->run();
