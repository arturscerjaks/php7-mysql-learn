<?php

use App\Classes\DatabaseTable;
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

    $jokeTable = new DatabaseTable($pdo, 'joke', 'id');
    $authorTable = new DatabaseTable($pdo, 'author', 'id');
    $jokeController = new JokeController($jokeTable, $authorTable);

    $action = $_GET['action'] ?? 'home';

    if ($action == strtolower($action)) {
        $jokeController->$action();
    } else {
        http_response_code(301);
        header('location: index.php?action=' . strtolower($action));
        exit;
    }

    $page = $jokeController->$action();

    $title = $page['title'];

    $variables = $page['variables'] ?? [];
    $output = loadTemplate($page['template'], $variables);
} catch (PDOException $e) {

    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . ' in ' .
        $e->getFile() . ':' . $e->getLine();
}

include __DIR__ . '/../templates/layout.html.php';
