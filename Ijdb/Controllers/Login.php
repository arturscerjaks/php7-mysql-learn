<?php

namespace Ijdb\Controllers;

use Framework\Authentication;

class Login
{
    private $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function login()
    {
        return [
            'template' => 'loginform.html.php',
            'title' => 'Log in'
        ];
    }

    public function loginSubmit()
    {
        $success = $this->authentication->login($_POST['email'], $_POST['password']);

        if ($success) {
            return [
                'template' => 'loginSuccess.html.php',
                'title' => 'Log In Succesful'
            ];
        } else {
            return [
                'template' => 'loginForm.html.php',
                'title' => 'Log in',
                'variables' => [
                    'errorMessage' => true
                ]
            ];
        }
    }

    public function logout() {
        $this->authentication->logout();
        header('location: /');
    }
}
