<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'controllers/StudentController.php';
$controller = new StudentController();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);


if ((isset($uri[1]) && $uri[1] == 'students') && (isset($uri[2]) && $uri[2] == 'list')) {
    // echo 'list';
    if ($controller !== null) {
        $controller->getStudents();
    } else {
        echo "Controller not initialized properly.";
    }
    
} elseif ((isset($uri[1]) && $uri[1] == 'students') && (isset($uri[2]) && $uri[2] == 'save')) {
    echo 'save';
    $controller->saveStudent();
} elseif ((isset($uri[1]) && $uri[1] == 'students') && (isset($uri[2]) && $uri[2] == 'delete') && isset($uri[3])) {
    $controller->deleteStudent($uri[3]);
} elseif ((isset($uri[1]) && $uri[1] == 'students') && isset($uri[2])) {
    $controller->getStudent($uri[2]);
} else {
    require_once './views/index.php';
}

