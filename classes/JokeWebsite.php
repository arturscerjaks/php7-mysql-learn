<?php

/**Holds Internet Joke Database-specific configurations: default route, controllers & necessary tables */

namespace App\Classes;

use App\Controllers\{JokeController,AuthorController};
use App\Classes\DatabaseTable;

class JokeWebsite
{
    public function getDefaultRoute()
    {
        return 'joke/home';
    }

    public function getController(string $controllerName)
    {
        include __DIR__ . '/../includes/DatabaseConnection.php';
        include __DIR__ . '/../classes/DatabaseTable.php';
        include __DIR__ . '/../controllers/JokeController.php';
        include __DIR__ . '/../controllers/AuthorController.php';

        $jokeTable = new DatabaseTable($pdo, 'joke', 'id');
        $authorTable = new DatabaseTable($pdo, 'author', 'id');

        if ($controllerName === 'joke') {
            $controller = new JokeController($jokeTable, $authorTable);
        } else if ($controllerName === 'author') {
            $controller = new AuthorController($authorTable);
        }

        return $controller;
    }
}
