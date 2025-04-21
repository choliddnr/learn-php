<?php

namespace App\Router;
class Router
{
    private static $routes = [];



    public static function add($path, $method, $controller, $function, $args = [])
    {
        $segments = explode('/', $path);
        $segments = array_values(array_filter($segments, function ($segment) {
            return !empty($segment);
        }));

        $paths = [];
        foreach ($segments as $segment) {
            $path = [];
            $path['is_param'] = str_starts_with($segment, ':');
            $path['name'] = str_starts_with($segment, ':') ? substr($segment, 1) : $segment;
            $paths[] = $path;
        }
        self::$routes[] = [
            'method' => strtoupper($method),
            'paths' => $paths,
            'controller' => $controller,
            'function' => $function,
        ];

    }


    public static function dispatch($path, $method)
    {

        // echo "path: " . $path . "<br>";
        // echo "method: " . $method . "<br>";

        // $uri_path = null;
        $params = [];
        $controller = null;
        $function = null;

        foreach (self::$routes as $route) {

            if ($route['method'] !== strtoupper($method)) {
                continue; // Skip if method doesn't match
            }

            $routePaths = $route['paths'];
            $path_segments = explode('/', $path);
            $path_segments = array_values(array_filter($path_segments, function ($segment) {
                return !empty($segment);
            }));
            if (count($routePaths) !== count($path_segments))
                continue;

            if ($path === '/' && count($routePaths) === 0) {
                $controller = new $route['controller'];
                $function = $route['function'];
                return $controller->$function();
            } elseif (count($routePaths) === 0) {
                continue;
            } else {

                $params = [];

                for ($i = 0; $i < count($routePaths); $i++) {
                    if ($routePaths[$i]['is_param']) {
                        if (isset($path_segments[$i])) {
                            $params[$routePaths[$i]['name']] = $path_segments[$i];
                        } else {
                            $params = [];
                            break;
                        }
                    } else {
                        if ($routePaths[$i]['name'] !== $path_segments[$i]) {
                            break;
                        }
                    }
                    if ($i === count($routePaths) - 1) {

                        // echo "Controller: " . $route['controller'] . "<br>";
                        // echo "Function: " . $route['function'] . "<br>";
                        // echo "Params: ";
                        // print_r($params);
                        $controller = new $route['controller']();
                        $function = $route['function'];
                        return $controller->$function(...$params);
                    }

                }
            }

        }
        http_response_code(404);
        echo "<br/>404 - Not Found";

    }

    public static function run()
    {
        $basePath = dirname($_SERVER['SCRIPT_NAME']); // usually '/'

        $uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($uri, PHP_URL_PATH);

        // Remove base path and index.php if they exist
        $path = preg_replace('#^' . preg_quote($basePath, '#') . '(index\.php)?/?#', '', $path);
        // Now $path contains your route   

        $method = $_SERVER['REQUEST_METHOD'];
        // dump();

        self::dispatch($path, $method);
    }
    public static function getRoutes()
    {
        return self::$routes;
    }
}