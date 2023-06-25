<?php

/**IJDB-specific controller /w /author route*/

namespace Ijdb\Controllers;

use \Framework\DatabaseTable;

class Author {

    private $authorTable;

    public function __construct(DatabaseTable $authorTable) 
    {
        $this->authorTable = $authorTable;
    }
}