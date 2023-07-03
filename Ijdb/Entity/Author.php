<?php

namespace Ijdb\Entity;

use Framework\DatabaseTable;

class Author {
    public $id;
    public $name;
    public $email;
    public $password;
    private DatabaseTable $jokeTable;

    /**
     * Creates instance of Author class.
     * 
     * 
     * Besides jokeTable param, there are 4 public initially unset variables (with var tag)
     * @var int|null $this->id
     * @var string|null $this->name
     * @var string|null $this->email
     * @var string|null $this->password
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