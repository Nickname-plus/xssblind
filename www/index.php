<?php

require_once __DIR__ . '/src/Autoload.php';

define('version', '3.5');
define('debug', false);

if (debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

if (PHP_VERSION_ID < 70100) {
    echo 'PHP 7.1 or up is required to use ezXSS';
    exit();
}

$requestUrl = explode('?', $_SERVER['REQUEST_URI'])[0];

if (strpos($requestUrl, '/manage/') === 0 || strpos($requestUrl, '/manage') === 0) {
    $path = str_replace('/manage/', '', explode('?', $_SERVER['REQUEST_URI'])[0]);

    if (explode('/', $path)[0] == 'report') {
        $path = explode('/', $path)[0];
    }

    if ($path == 'request') {
        $request = new Request();
        echo $request->json();
    } else {
        $route = new Route();
        echo $route->template($path);
    }
} else {
    $route = new Route();

    if ($requestUrl == '/callback') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo $route->callback(file_get_contents('php://input'));
        }
    } else {
        header('Content-Type: application/x-javascript');
        echo $route->jsPayload();
    }
}
