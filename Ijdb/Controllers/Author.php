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

    public function registrationFormSubmit()
    {
        $author = $_POST['author'];

        // Assume the data is valid to begin with
        $valid = true;

        // But if any of the fields have been left blank, set $valid to false
        if (empty($author['name'])) {
            $valid = false;
        }

        if (empty($author['email'])) {
            $valid = false;
        }

        if (empty($author['password'])) {
            $valid = false;
        }

        // If $valid is still true, no fields were blank and the data can be added
        if ($valid == true) {
            $this->authorTable->save($author);
            header('Location: /author/success');
        } else {

            // If the data is not valid, show the form again
            return [
                'template' => 'register.html.php',
                'title' => 'Register an account'
            ];
        }
    }
}
