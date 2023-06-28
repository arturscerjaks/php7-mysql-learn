<?php

/**Holds Internet Joke Database-specific configurations: default route, controllers & necessary tables */

namespace Ijdb;

use Framework\{Authentication, Website, DatabaseTable};
use Ijdb\Controllers\{Joke, Author, Login};
use \PDO;

class IjdbRoutes implements Website
{

    /**@var PDO $pdo Joke database connection*/

    private $pdo;
    private $jokeTable;
    private $authorTable;
    private $authentication;

    public function __construct()
    {
        $this->pdo = new \PDO(
            'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
            'ijdbuser',
            'mypassword'
        );
        $this->jokeTable = new DatabaseTable($this->pdo, 'joke', 'id');
        $this->authorTable = new DatabaseTable($this->pdo, 'author', 'id');
        $this->authentication = new Authentication($this->authorTable, 'email', 'password');
    }

    public function getDefaultRoute(): string
    {
        return 'joke/home';
    }

    public function getController(string $controllerName): ?object
    {


        if ($controllerName === 'joke') {
            $controller = new Joke($this->jokeTable, $this->authorTable, $this->authentication);
        } else if ($controllerName === 'author') {
            $controller = new Author($this->authorTable);
        } else if ($controllerName === 'login') {
            $controller = new Login($this->authentication);
        } else {
            $controller = null;
        }

        return $controller;
    }

    public function checkLogin(string $uri): ?string
    {
        $restrictedPages = ['joke/edit', 'joke/delete'];

        if (in_array($uri, $restrictedPages) && !$this->authentication->isLoggedIn()) {
            header('location: /login/login');
            exit();
        }

        return $uri;
    }

    public function getLayoutVariables(): array
    {
        return [
            'loggedIn' => $this->authentication->isLoggedIn()
        ];
    }
}
