<?php

/**IJDB-specific controller with /joke route*/

namespace Ijdb\Controllers;

use Framework\Authentication;
use \Framework\DatabaseTable;

class Joke
{

    private $authorTable;
    private $jokeTable;
    private $authentication;

    /**Constructs instance of JokeController with class variables
     * 
     * 
     * @param DatabaseTable $jokeTable
     * @param DatabaseTable $authorTable
     * @param Authentication $authentication
    */

    public function __construct(DatabaseTable $jokeTable, DatabaseTable $authorTable, Authentication $authentication)
    {
        $this->jokeTable = $jokeTable;
        $this->authorTable = $authorTable;
        $this->authentication = $authentication;
    }

    /**Finds all rows in joke table, adds info from author table to each joke.
     * Returns info for template to show list
     * 
     * @return mixed[]
     */

    public function list()
    {
        $result = $this->jokeTable->findAll();

        $jokes = [];
        foreach ($result as $joke) {
            $author = $this->authorTable->find('id', $joke['authorid'])[0];

            $jokes[] = [
                'id' => $joke['id'],
                'joketext' => $joke['joketext'],
                'jokedate' => $joke['jokedate'],
                'name' => $author['name'],
                'email' => $author['email'],
                'authorid' => $author['id']
            ];
        }

        $title = 'Joke list';

        $totalJokes = $this->jokeTable->totalRows();

        $user = $this->authentication->getUser();

        return [
            'template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes,
                'userId' => $user['id'] ?? null
            ]
        ];
    }

    /**Gives template correct info to show home page
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
        $this->jokeTable->delete('id', $_POST['id']);

        header('location: /joke/list');
    }

    /**Handles form submission*/

    public function editSubmit(): void
    {
        $author = $this->authentication->getUser();

        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();
        $joke['authorid'] = $author['id'];

        $this->jokeTable->save($joke);

        header('location: /joke/list');
    }

    /**Displays the form for editting or adding a joke.
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

        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
                'userId' => $author['id'] ?? null
            ]
        ];
    }
}
