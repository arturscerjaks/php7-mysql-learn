<?php

/**IJDB-specific controller /w /author route*/

namespace Ijdb\Controllers;

use \Framework\DatabaseTable;

class Author
{

    private $authorTable;

    public function __construct(DatabaseTable $authorTable)
    {
        $this->authorTable = $authorTable;
    }

    /**Handles new user registration*/

    public function registrationForm(): array
    {
        return [
            'template' => 'register.html.php',
            'title' => 'Register an account'
        ];
    }

    /**Handles registration success*/

    public function success(): array
    {
        return [
            'template' => 'registersuccess.html.php',
            'title' => 'Registration succesful'
        ];
    }

    public function registrationFormSubmit() {
        $author = $_POST['author'];

        $this->authorTable->save($author);

        header('location: /author/success');
    }
}
