<?php

namespace Ijdb\Entity;

use Framework\DatabaseTable;

class Author {
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    private DatabaseTable $jokeTable;

    /**
     * Creates instance of Author class.
     * 
     * 
     * Besides jokeTable param, there are 4 public initially unset variables (with var tag)
     * @var int $this->id
     * @var string $this->name
     * @var string $this->email
     * @var string $this->password
    */

    public function __construct(DatabaseTable $jokeTable)
    {
        $this->jokeTable = $jokeTable;
    }

    /**
     * Returns all jokes from DB table where authorid is `$this->id`*/

    public function getJokes(): object {
        return $this->jokeTable->find('authorid', $this->id);
    }

    /**
     * Adds a joke to the `joke` DB table
     * 
     * 
     * Changes instance's `$this->id` to `$joke['authorid']`
    */

    public function addJoke(array $joke): void {
        $joke['authorid'] = $this->id;

        $this->jokeTable->save($joke);
    }
}