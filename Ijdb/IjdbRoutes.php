<?php

/**Holds Internet Joke Database-specific configurations: default route, controllers & necessary tables */

namespace Ijdb;

use Framework\DatabaseTable;
use Ijdb\Controllers\{Joke, Author};
use PDO;

class IjdbRoutes
{

    /**@var PDO $pdo Joke database connection*/

    private $pdo;

    public function __construct() {
        $this->pdo = new PDO(
            'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
            'ijdbuser',
            'mypassword'
        );
    }

    public function getDefaultRoute()
    {
        return 'joke/home';
    }

    public function getController(string $controllerName)
    {
        $jokeTable = new DatabaseTable($this->pdo, 'joke', 'id');
        $authorTable = new DatabaseTable($this->pdo, 'author', 'id');

        if ($controllerName === 'joke') {
            $controller = new Joke($jokeTable, $authorTable);
        } else if ($controllerName === 'author') {
            $controller = new Author($authorTable);
        }

        return $controller;
    }
}
