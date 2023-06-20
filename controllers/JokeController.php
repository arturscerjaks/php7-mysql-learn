<?php

namespace App\Classes;

use App\Classes\DatabaseTable;
use DateTime;

class JokeController
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

        return ['template' => 'jokes.html.php', 'title' => $title];
    }

    public function home()
    {

        $title = 'Internet Joke Database';

        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function delete()
    {
        $this->jokeTable->delete('id', $_POST['id']);

        header('location: index?action=list');
    }

    public function edit()
    {
        if (isset($_POST['joke'])) {

            $joke = $_POST['joke'];
            $joke['jokedate'] = new DateTime();
            $joke['authorid'] = 1;

            $this->jokeTable->save($joke);

            header('location: index?action=list');
        } else {

            if (isset($_GET['id'])) {
                $joke = $this->jokeTable->find('id', $_GET['id'])[0] ?? null;
            } else {
                $joke = null;
            }

            $title = 'Edit joke';

            return ['template' => 'editjoke.html.php', 'title' => $title];
        }
    }
}
