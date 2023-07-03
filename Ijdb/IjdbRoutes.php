<?php

/**Holds Internet Joke Database-specific configurations: default route, controllers & necessary tables */

namespace Ijdb;

use Framework\{Authentication, Website, DatabaseTable};
use Ijdb\Controllers\{Joke, Author, Category, Login};
use \PDO;

class IjdbRoutes implements Website
{

    /**@var PDO $pdo Joke database connection*/

    private $pdo;
    private ?DatabaseTable $jokeTable;
    private ?DatabaseTable $authorTable;
    private ?DatabaseTable $categoryTable;
    private Authentication $authentication;

    public function __construct()
    {
        $this->pdo = new \PDO(
            'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
            'ijdbuser',
            'mypassword'
        );
        $this->jokeTable = new DatabaseTable($this->pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorTable]);
        $this->authorTable = new DatabaseTable($this->pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokeTable]);
        $this->categoryTable = new DatabaseTable($this->pdo, 'category', 'id');
        $this->authentication = new Authentication($this->authorTable, 'email', 'password');
    }

    public function getDefaultRoute(): string
    {
        return 'joke/home';
    }

    /**
     * Creates instance of necessary controller
     * 
     * 
     * Upon creating a new controller, it must be added in here
     * @param string $controllerName 
     * @return ?object if user uses correct first part of route, else null
     */

    public function getController(string $controllerName): ?object
    {

        $controllers = [
            'joke' => new Joke($this->jokeTable, $this->authorTable, $this->authentication),
            'author' => new Author($this->authorTable),
            'login' => new Login($this->authentication),
            'category' => new Category($this->categoryTable)
        ];

        return $controllers[$controllerName] ?? null;
    }

    /**
     * Checks whether a page ('controller/action') needs user to be logged in
     * 
     * 
     * @param string $uri Universal Resource Identifier
     * @var string[] $restrictedPages contains list of restricted routes
     * @return ?string
     */

    public function checkLogin(string $uri): ?string
    {
        $restrictedPages = ['joke/edit', 'joke/delete'];

        if (in_array($uri, $restrictedPages) && !$this->authentication->isLoggedIn()) {
            header('location: /login/login');
            exit();
        }

        return $uri;
    }

    /**
     * Gets 'loggedIn' status from authentication object
     */

    public function getLayoutVariables(): array
    {
        return [
            'loggedIn' => $this->authentication->isLoggedIn()
        ];
    }
}
