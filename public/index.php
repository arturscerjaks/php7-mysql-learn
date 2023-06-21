<?php

use App\Classes\DatabaseTable;
use App\Controllers\AuthorController;
use App\Controllers\JokeController;

function loadTemplate($templateFileName, $variables)
{
    extract($variables);
    ob_start();
    include __DIR__ . '/../templates/' . $templateFileName;
    return ob_get_clean();
}

try {

    include __DIR__ . '/../includes/DatabaseConnection.php';
    include __DIR__ . '/../classes/DatabaseTable.php';
    include __DIR__ . '/../controllers/JokeController.php';
    include __DIR__ . '/../controllers/AuthorController.php';


    $jokeTable = new DatabaseTable($pdo, 'joke', 'id');
    $authorTable = new DatabaseTable($pdo, 'author', 'id');

    $action = $_GET['action'] ?? 'home';
    $controllerName = $_GET['controller'] ?? 'joke';

    if ($controllerName === 'joke') {
        $controller = new JokeController($jokeTable, $authorTable);
    } else if ($controllerName === 'author') {
        $controller = new AuthorController($authorTable);
    }

    if ($action == strtolower($action) && $controllerName == strtolower($controllerName)) {
        $controller->$action();
    } else {
        http_response_code(301);
        header('location: index.php?controller=' . strtolower($controller) . '&action=' . strtolower($action));
        exit;
    }

    $title = $page['title'];

    $variables = $page['variables'] ?? [];
    $output = loadTemplate($page['template'], $variables);
} catch (PDOException $e) {

    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . ' in ' .
        $e->getFile() . ':' . $e->getLine();
}

include __DIR__ . '/../templates/layout.html.php';
