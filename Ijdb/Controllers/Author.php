<?php

/**IJDB-specific controller /w /author route*/

namespace Ijdb\Controllers;

use Framework\DatabaseTable;

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

        if (count($this->authorTable->find('email', $author['email'])) > 0) {
            $errors[] = 'That email address is already registered';
        }

        if (empty($author['password'])) {
            $errors[] = 'Password cannot be blank';
        }

        // If $valid is still true, no fields were blank and the data can be added
        if (empty($errors)) {
            // Hash the password before saving
            $author['password'] = password_hash($author['password'], PASSWORD_DEFAULT);

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

    /**
     * Lists all authors in DB's `author` table
     */

    public function list(): array
    {
        $authors = $this->authorTable->findAll();

        return [
            'template' => 'authorlist.html.php',
            'title' => 'Author List',
            'variables' => [
                'authors' => $authors
            ]
        ];
    }

    /**
     * Lets edit permissions of author specified by id
     * 
     * 
     * Gets permission bitwise values through Author entity
     * @param ?string $id author's id
     */

    public function permissions(?string $id = null): array
    {
        $author = $this->authorTable->find('id', $id)[0];

        $reflected = new \ReflectionClass('\Ijdb\Entity\Author');
        $constants = $reflected->getConstants();

        return [
            'template' => 'permissions.html.php',
            'title' => 'Edit Permissions',
            'variables' => [
                'author' => $author,
                'permissions' => $constants
            ]
        ];
    }

    public function permissionsSubmit($id = null) {
        $author = [
            'id' => $_POST['id'],
            'permissions' => array_sum($_POST['permissions'] ?? [])
        ];

        $this->authorTable->save($author);

        header('location: /author/list');
    }

}
