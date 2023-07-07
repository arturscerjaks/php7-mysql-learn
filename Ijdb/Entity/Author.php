<?php

namespace Ijdb\Entity;

use Framework\DatabaseTable;

class Author
{

    const EDIT_JOKE = 1;
    const DELETE_JOKE = 2;
    const LIST_CATEGORIES = 3;
    const EDIT_CATEGORY = 4;
    const DELETE_CATEGORY = 5;
    const EDIT_USER_ACCESS = 6;

    public ?int $id;
    public ?string $name;
    public ?string $email;
    public ?string $password;
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

    public function getJokes(): object
    {
        return $this->jokeTable->find('authorid', $this->id);
    }

    /**
     * Adds a joke to the `joke` DB table
     * 
     * 
     * Changes instance's `$this->id` to `$joke['authorid']`
     */

    public function addJoke(array $joke): object
    {
        $joke['authorid'] = $this->id;

        return $this->jokeTable->save($joke);
    }

    /**
     * Checks whether an user has permission
     */

    public function hasPermission(int $permission) {

    }
}
