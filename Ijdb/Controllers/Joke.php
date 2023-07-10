<?php

/**IJDB-specific controller with /joke route*/

namespace Ijdb\Controllers;

use Framework\Authentication;
use \Framework\DatabaseTable;
use Ijdb\Entity\Author;

class Joke
{

    private $authorTable;
    private $jokeTable;
    private $authentication;
    private $categoryTable;

    /**
     * Constructs instance of JokeController with class variables
     * 
     * 
     * @param DatabaseTable $jokeTable
     * @param DatabaseTable $authorTable
     * @param DatabaseTable $categoryTable
     * @param Authentication $authentication
     */

    public function __construct(DatabaseTable $jokeTable, DatabaseTable $authorTable, DatabaseTable $categoryTable, Authentication $authentication)
    {
        $this->jokeTable = $jokeTable;
        $this->authorTable = $authorTable;
        $this->authentication = $authentication;
        $this->categoryTable = $categoryTable;
    }

    /**
     * Finds and returns all `joke` table entries.
     * 
     * 
     * @param int $categoryId if supplied returns specific category's jokes
     * @return mixed[]
     */

    public function list($categoryId = null)
    {
        if (isset($categoryId)) {
            $category = $this->categoryTable->find('id', $categoryId)[0];
            $jokes = $category->getJokes();
        } else {
            $jokes = $this->jokeTable->findAll();
        }

        $user = $this->authentication->getUser();
        $totalJokes = $this->jokeTable->totalRows();

        return [
            'template' => 'jokes.html.php',
            'title' => 'Joke list',
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes,
                'user' => $user,
                'categories' => $this->categoryTable->findAll()
            ]
        ];
    }

    /**
     * Gives template correct info to show home page
     * 
     * @return array
     */

    public function home()
    {

        $title = 'Internet Joke Database';

        return ['template' => 'home.html.php', 'title' => $title];
    }

    /**
     * Handles joke deletion
     * 
     * 
     * Returns prematurely, if user isn't author or doesn't have permission to delete others' jokes
    */

    public function deleteSubmit()
    {
        $author = $this->authentication->getUser();

        $joke = $this->jokeTable->find('id', $_POST['id']);

        if ($joke->authorid != $author->id && !$author->hasPermission(\Ijdb\Entity\Author::DELETE_JOKE)) {
            return;
        }

        $this->jokeTable->delete('id', $_POST['id']);

        header('location: /joke/list');
    }

    /**Handles form submission*/

    public function editSubmit(): void
    {
        $author = $this->authentication->getUser();

        if (!empty($id)) {
            $joke = $this->jokeTable->find('id', $id)[0];

            if ($joke->authorid != $author->id) {
                return;
            }
        }


        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();

        $jokeEntity = $author->addJoke($joke);

        $jokeEntity->clearCategories();

        foreach ($_POST['category'] as $categoryId) {
            $jokeEntity->addCategory($categoryId);
        }

        header('location: /joke/list');
    }

    /**
     * Displays the form for editting or adding a joke.
     * 
     * 
     * Finds and displays a joke if already there, else shows empty form.
     * Returns values necessary for template to show correct info.
     * 
     * @param int|null $id
     * @return mixed[] 
     */

    public function edit($id = null)
    {

        if (isset($id)) {
            $joke = $this->jokeTable->find('id', $id)[0] ?? null;
        }

        $author = $this->authentication->getUser();
        $categories = $this->categoryTable->findAll();

        return [
            'template' => 'editjoke.html.php',
            'title' => 'Edit joke',
            'variables' => [
                'joke' => $joke ?? null,
                'user' => $author,
                'categories' => $categories
            ]
        ];
    }
}
