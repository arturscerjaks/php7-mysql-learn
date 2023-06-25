<?php

/**IJDB-specific controller with /joke route*/

namespace Ijdb\Controllers;

use \Framework\DatabaseTable;

class Joke
{

    private $authorTable;
    private $jokeTable;

    /**Creates instance of JokeController with class variables*/

    public function __construct(DatabaseTable $jokeTable, DatabaseTable $authorTable)
    {
        $this->jokeTable = $jokeTable;
        $this->authorTable = $authorTable;
    }

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
                'email' => $author['email']
            ];
        }

        $title = 'Joke list';

        $totalJokes = $this->jokeTable->totalRows();

        return [
            'template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes
            ]
        ];
    }

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
        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();
        $joke['authorid'] = 1;

        $this->jokeTable->save($joke);

        header('location: /joke/list');
    }

    /**Displays the form for editting or adding a joke
     * 
     * 
     * @param int|null $id
     * @return (string|(array|null)[])[]
    */

    public function edit($id = null)
    {

        if (isset($id)) {
            $joke = $this->jokeTable->find('id', $id)[0] ?? null;
        }

        $title = 'Edit joke';

        return [
            'template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null
            ]
        ];
    }
}
