<?php

use App\Classes\DatabaseTable;
use App\Classes\JokeController;

try {

    include __DIR__ . '/../includes/DatabaseConnection.php';
    include __DIR__ . '/../classes/DatabaseTable.php';
    include __DIR__ . '/../controllers/JokeController.php';

    $jokeTable = new DatabaseTable($pdo, 'joke', 'id');
    $authorTable = new DatabaseTable($pdo, 'author', 'id');
    $jokeController = new JokeController($jokeTable, $authorTable);

    $action = $_GET['action'] ?? 'home';

    $page = $jokeController->$action();

    $title = $page['title'];

    $output = ob_start();

    include __DIR__ . '/../templates/' . $page['template'];

    $output = ob_get_clean();
} catch (PDOException $e) {

    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . ' in ' .
        $e->getFile() . ':' . $e->getLine();
}

include __DIR__ . '/../templates/layout.html.php';
