<?php

namespace Ijdb\Entity;

use Framework\DatabaseTable;

class Category
{

    /**@var int $id of joke category's id*/
    public $id;
    /**@var string $name of joke category's name*/
    public $name;
    
    private $jokeTable;
    private $jokeCategoryTable;

    /**
     * Creates instance of Category entity
     */

    public function __construct(?DatabaseTable $jokeTable, ?DatabaseTable $jokeCategoryTable)
    {
        $this->jokeTable = $jokeTable;
        $this->jokeCategoryTable = $jokeCategoryTable;
    }

    /**
     * Gets all jokes in category where `$this->id` is categoryId in DB's `joke_category`
     * 
     * 
     * @return array of objects specified in jokeTable's constructor
     */

    public function getJokes(): array
    {
        $jokeCategories = $this->jokeCategoryTable->find('categoryId', $this->id);

        $joke = [];

        foreach ($jokeCategories as $jokeCategory) {
            $joke = $this->jokeTable->find('id', $jokeCategory->jokeId)[0]  ?? null;
            if ($joke) {
                $jokes[] = $joke;
            }
        }

        return $jokes;
    }
}
