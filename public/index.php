<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies
// require_once __DIR__ . '/../app/bootstrap.php'; // Bootstrap the application
require_once __DIR__ . '/../app/router/routes.php'; // Load routes



// Get the full request URI (e.g. "/path/to/page?foo=bar")
// $requestUri = $_SERVER['REQUEST_URI'];

// // Remove the query string
// $uri = parse_url($requestUri, PHP_URL_PATH);

// // Remove base path if needed (e.g. if your app is in a subfolder)
// $scriptName = dirname($_SERVER['SCRIPT_NAME']);
// $path = '/' . ltrim(str_replace($scriptName, '', $uri), '/');

// // Now $path contains your route info
// echo "Route: " . $path;



// $basePath = dirname($_SERVER['SCRIPT_NAME']); // usually '/'

// $uri = $_SERVER['REQUEST_URI'];
// $path = parse_url($uri, PHP_URL_PATH);

// // Remove base path and index.php if they exist
// $path = preg_replace('#^' . preg_quote($basePath, '#') . '(index\.php)?/?#', '', $path);

// // Now $path contains your route
// // echo "Path: " . $path . "<br>";
// // echo "Base Path: " . $basePath . "<br>";
// // echo "Request URI: " . $uri . "<br>";
// // var_dump($_SERVER['REQUEST_URI']);

// // Simple routing example
// switch ($path) {
//     case '':
//     case '/':
//         require __DIR__ . '/../app/views/index.php';
//         break;
//     case '/about':
//         require __DIR__ . '/../app/views/about.php';
//         break;
//     default:
//         http_response_code(404);
//         echo "404 - Not Found";
//         break;
// }