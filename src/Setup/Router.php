<?php

namespace Fenner\Blog\Setup;

class Router
{
    private $routes = [];

    public function get($url, $controllerMethod)
    {
        $this->routes[$url] = $controllerMethod;
    }

    public function route($url)
    {
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'];
        $queryParams = [];

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }

        if (array_key_exists($path, $this->routes)) {
            $controllerMethod = $this->routes[$path];
            $parts = explode('@', $controllerMethod);
            $controllerName = $parts[0];
            $methodName = $parts[1];

            $controllerClass = "Fenner\\Blog\\Controllers\\$controllerName";


            if (class_exists($controllerClass)) {
  
                $controller = new $controllerClass();

                if (method_exists($controller, $methodName)) {
                    $controller->$methodName($queryParams);
                } else {
                    echo "Método '$methodName' no encontrado en el controlador '$controllerClass'";
                }
            } else {
                echo "Clase de controlador '$controllerClass' no encontrada";
            }
        } else {
            echo "Ruta no encontrada";
        }
    }
}
