<?php

/**Holds Internet Joke Database-specific configurations: default route, controllers & necessary tables */

namespace Ijdb;

use Framework\{Authentication, Website, DatabaseTable};
use Ijdb\Controllers\{Joke, Author, Category, Login};

class IjdbRoutes implements Website
{

    private ?DatabaseTable $jokeTable;
    private ?DatabaseTable $authorTable;
    private ?DatabaseTable $categoryTable;
    private ?DatabaseTable $jokeCategoryTable;
    private Authentication $authentication;

    /**
     * Creates IjdbRoutes instance with dependencies for controllers and site-specific routes
     * 
     * 
     * DBTable constructors may specify what object type to return in methods
     */

    public function __construct()
    {
        $pdo = new \PDO(
            'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
            'ijdbuser',
            'mypassword'
        );
        $this->jokeTable = new DatabaseTable($pdo, 'joke', 'id', '\Ijdb\Entity\Joke', [&$this->authorTable, &$this->jokeCategoryTable]);

        $this->authorTable = new DatabaseTable($pdo, 'author', 'id', '\Ijdb\Entity\Author', [&$this->jokeTable]);

        $this->categoryTable = new DatabaseTable($pdo, 'category', 'id', '\Ijdb\Entity\Category', [&$this->jokeTable, &$this->jokeCategoryTable]);

        $this->jokeCategoryTable = new DatabaseTable($pdo, 'joke_category', 'categoryId');

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
            'joke' => new Joke($this->jokeTable, $this->authorTable, $this->categoryTable, $this->authentication),
            'author' => new Author($this->authorTable),
            'login' => new Login($this->authentication),
            'category' => new Category($this->categoryTable)
        ];

        return $controllers[$controllerName] ?? null;
    }

    /**
     * Checks whether an user has sufficient permissions to access page
     * 
     * 
     * If a page is restricted by any necessary permission,
     * then an user needs to be logged in to view it
     * @param string $uri Universal Resource Identifier
     * @var string[] $restrictedPages contains list of restricted routes
     * @return ?string
     */

    public function checkLogin(string $uri): ?string
    {
        $restrictedPages = ['category/list' => \Ijdb\Entity\Author::LIST_CATEGORIES];

        if (isset($restrictedPages[$uri])) {
            if (
                !$this->authentication->isLoggedIn()
                || !$this->authentication->getUser()->hasPermission($restrictedPages[$uri])
            ) {
                header('location: /login/login');
                exit();
            }
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
