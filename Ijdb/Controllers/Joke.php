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
     * Finds all rows in joke table, adds info from author table to each joke.
     * Returns info for template to show list
     * 
     * @return mixed[]
     */

    public function list()
    {
        $jokes = $this->jokeTable->findAll();

        $user = $this->authentication->getUser();
        $totalJokes = $this->jokeTable->totalRows();

        return [
            'template' => 'jokes.html.php',
            'title' => 'Joke list',
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes,
                'userId' => $user->id ?? null,
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

    /**Handles joke deletion*/

    public function deleteSubmit()
    {
        $author = $this->authentication->getUser();

        $joke = $this->jokeTable->find('id', $_POST['id'])[0];

        if ($joke->authorid != $author->id) {
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

        $title = 'Edit joke';

        $author = $this->authentication->getUser();
        $categories = $this->categoryTable->findAll();

        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
                'userId' => $author->id ?? null,
                'categories' => $categories
            ]
        ];
    }
}
