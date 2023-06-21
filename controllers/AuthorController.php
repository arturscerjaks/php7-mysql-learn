<?php

namespace App\Controllers;

use App\Classes\DatabaseTable;

class AuthorController {

    private $authorTable;

    public function __construct(DatabaseTable $authorTable) 
    {
        $this->authorTable = $authorTable;
    }
}