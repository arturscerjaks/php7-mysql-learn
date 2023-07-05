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
    private DatabaseTable $jokeCategoryTable;

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

    public function __construct($authorTable, $jokeCategoryTable)
    {
        $this->authorTable = $authorTable;
        $this->jokeCategoryTable = $jokeCategoryTable;
    }

    /**
     * Returns Author object where author's id is `$this->authorid`*/

    public function getAuthor(): object {
        if (empty($this->author)) {
            $this->author = $this->authorTable->find('id', $this->authorid)[0];
        }

        return $this->authorTable->find('id', $this->authorid)[0];
    }

    /**
     * Adds category to a joke
     */

    public function addCategory($categoryId) {
        $jokeCat = ['jokeId' => $this->id, 'categoryId' => $categoryId];

        $this->jokeCategoryTable->save($jokeCat);
    }

    /**
     * Loops through all associated categories for a joke
     */

    public function hasCategory($categoryId) {
        $jokeCategories = $this->jokeCategoryTable->find('jokeId', $this->id);
        
        foreach ($jokeCategories as $jokeCategory) {
            if ($jokeCategory->categoryId == $categoryId) {
                return true;
            }
        }
    }
}