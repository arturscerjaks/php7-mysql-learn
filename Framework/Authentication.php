<?php

namespace Framework;

use Framework\DatabaseTable;

class Authentication
{

    private $users;
    private $usernameColumn;
    private $passwordColumn;

    /**Creates instance of Authentication class and starts session.
     * 
     * 
     * @param DatabaseTable $users is table where user details are found
     * @param string $usernameColumn is name of username column
     * @param string $passwordColumn is name of pass column
     */

    public function __construct(DatabaseTable $users, string $usernameColumn, string $passwordColumn)
    {
        $this->users = $users;
        $this->usernameColumn = $usernameColumn;
        $this->passwordColumn = $passwordColumn;

        session_start();
    }

    /**Tries to log in user with credentials
     * 
     * 
     * @param string $username is checked if in `$this->usernameColumn`
     * @param string $password is then checked if matches `$this->passwordColumn`
     * @return bool true if success or false if failure
    */

    public function login(string $username, string $password): bool
    {
        $user = $this->users->find($this->usernameColumn, strtolower($username));

        if (!empty($user) && password_verify($password, $user[0][$this->passwordColumn])) {
            session_regenerate_id();

            $_SESSION['username'] = $username;
            $_SESSION['password'] = $user[0][$this->passwordColumn];

            return true;
        } else {
            return false;
        }
    }

    /**Checks whether user is logged in.
     * 
     * 
     * Returns true if an user is found in $_SESSION with username in DB's `$this->usernameColumn`
     * and if password in $_SESSION matches with hashed password in DB's `$this->passwordColumn`
     */

    public function isLoggedIn(): bool
    {
        if (empty($_SESSION['username'])) {
            return false;
        }

        $user = $this->users->find($this->usernameColumn, strtolower($_SESSION['username']));

        if (!empty($user) && $user[0][$this->passwordColumn] === $_SESSION['password']) {
            return true;
        } else {
            return false;
        }
    }

    /**Logs out current user, regens session id*/

    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        session_regenerate_id();
    }

    /**Finds current user's row in DB's user table.
     * 
     * 
     * Returns all rows of `$this->users` where `$this->usernameColumn`
     * is equal to `$_SESSION['username']`
     * */

    public function getUser(): ?object
    {
        if ($this->isLoggedIn()) {
            return $this->users->find($this->usernameColumn, strtolower($_SESSION['username']))[0];
        } else {
            return false;
        }
    }
}
