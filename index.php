<?php


// Autoload para cargar automáticamente las clases
require_once 'vendor/autoload.php';

use Fenner\Blog\Setup\Router;

$routes = require_once 'routes.php';

// Crea una instancia de Router
$router = new Router();

// Agrega las rutas al enrutador
foreach ($routes as $url => $controllerMethod) {
    $router->get($url, $controllerMethod);
}

// Obtiene la ruta de la URL y el método HTTP
$requestUrl = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Enrutar la solicitud
$router->route($requestUrl, $requestMethod);

