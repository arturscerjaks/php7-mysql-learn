<?php

namespace Ijdb\Entity;

use Framework\DatabaseTable;

class Author {
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    private DatabaseTable $jokeTable;

    public function __construct(DatabaseTable $jokeTable)
    {
        $this->jokeTable = $jokeTable;
    }

    /**Returns all jokes from DB table where authorid is `$this->id`*/

    public function getJokes(): array {
        return $this->jokeTable->find('authorid', $this->id);
    }
}