<?php

/**Creates PHP Database Object for use in database operations*/

namespace App\Includes;

use PDO;

/**@var PDO $pdo Joke database connection*/

$pdo = new PDO(
    'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
    'ijdbuser',
    'mypassword'
);
