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

    /**
     * Redirects to loginform template
     */

    public function login()
    {
        return [
            'template' => 'loginform.html.php',
            'title' => 'Log in'
        ];
    }

    /**
     * Redirects to loginSuccess template in case of correct login, else to loginForm template
     */

    public function loginSubmit()
    {
        $success = $this->authentication->login($_POST['email'], $_POST['password']);

        if ($success) {
            return [
                'template' => 'loginsuccess.html.php',
                'title' => 'Log In Succesful'
            ];
        } else {
            return [
                'template' => 'loginform.html.php',
                'title' => 'Log in',
                'variables' => [
                    'errorMessage' => true
                ]
            ];
        }
    }

    /**
     * Logs user out and redirects to website's root
     */

    public function logout() {
        $this->authentication->logout();
        header('location: /');
    }
}
