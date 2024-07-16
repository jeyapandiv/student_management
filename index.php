<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'controllers/StudentController.php';

$controller = new StudentController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ((isset($uri[2]) && $uri[2] == 'students') ) {
    $controller->getStudents();
} else {
    require_once 'views/index.php';
}

