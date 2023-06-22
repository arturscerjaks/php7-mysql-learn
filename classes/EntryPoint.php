<?php

namespace App\Classes;

use App\Classes\DatabaseTable;
use App\Controllers\{JokeController, AuthorController};
use PDOException;

class EntryPoint
{
    public function run($uri)
    {
        try {

            include __DIR__ . '/../includes/DatabaseConnection.php';
            include __DIR__ . '/../classes/DatabaseTable.php';
            include __DIR__ . '/../controllers/JokeController.php';
            include __DIR__ . '/../controllers/AuthorController.php';

            $jokeTable = new DatabaseTable($pdo, 'joke', 'id');
            $authorTable = new DatabaseTable($pdo, 'author', 'id');
            
            $this->checkUri($uri);
            if ($uri == '') {
                $uri = 'joke/home';
            }
            
            $route = explode('/', $uri);
            
            $controllerName = array_shift($route);
            
            $action = array_shift($route);
            
            if ($controllerName === 'joke') {
                $controller = new JokeController($jokeTable, $authorTable);
            } else if ($controllerName === 'author') {
                $controller = new AuthorController($authorTable);
            }
            
            $page = $controller->$action(...$route);
            $title = $page['title'];
            $variables = $page['variables'] ?? [];
            
            $output = $this->loadTemplate($page['template'], $variables);
        } catch (PDOException $e) {
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' .
                $e->getFile() . ':' . $e->getLine();
        }
        include __DIR__ . '/../templates/layout.html.php';
    }

    private function loadTemplate($templateFileName, $variables)
    {
        extract($variables);

        ob_start();
        include __DIR__ . '/../templates/' . $templateFileName;

        return ob_get_clean();
    }

    private function checkUri($uri)
    {
        if ($uri != strtolower($uri)) {
            http_response_code(301);
            header('location: /' . strtolower($uri));
        }
    }
}
