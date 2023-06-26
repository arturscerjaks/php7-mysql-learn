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

    /**Handles user registration and form validation*/

    public function registrationFormSubmit()
    {
        $author = $_POST['author'];


        $errors = [];

        // But if any of the fields have been left blank, set $valid to false
        if (empty($author['name'])) {
            $errors[] = 'Name cannot be blank';
        }

        if (empty($author['email'])) {
            $errors[] = 'Email cannot be blank';
        } else if (filter_var($author['email']) == false) {
            $errors[] = 'Invalid email address';
        }

        if (empty($author['password'])) {
            $errors[] = 'Password cannot be blank';
        }

        // If $valid is still true, no fields were blank and the data can be added
        if (empty($errors)) {
            $this->authorTable->save($author);
            header('Location: /author/success');
        } else {

            // If the data is not valid, show the form again
            return [
                'template' => 'register.html.php',
                'title' => 'Register an account',
                'variables' => [
                    'errors' => $errors,
                    'author' => $author
                ]
            ];
        }
    }
}
