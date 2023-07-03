<?php

namespace Ijdb\Entity;

use Framework\DatabaseTable;

class Joke {
    public ?int $id;
    public ?int $authorid;
    public ?string $jokedate;
    public ?string $joketext;
    private DatabaseTable $authorTable;
    private ?object $author;

    /**
     * Creates instance of Joke class.
     * 
     * 
     * Besides authorTable param, there are 4 public initially unset variables (with var tag)
     * @var int|null $this->id
     * @var string|null $this->authorid
     * @var string|null $this->jokedate
     * @var string|null $this->joketext
    */

    public function __construct($authorTable)
    {
        $this->authorTable = $authorTable;
    }

    /**
     * Returns Author object where author's id is `$this->authorid`*/

    public function getAuthor(): object {
        if (empty($this->author)) {
            $this->author = $this->authorTable->find('id', $this->authorid)[0];
        }

        return $this->authorTable->find('id', $this->authorid)[0];
    }
}