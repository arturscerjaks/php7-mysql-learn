<?php
// Include the file that creates the $pdo variable and connects to the database

use App\Classes\DatabaseTable;

include_once __DIR__ . '/../includes/DatabaseConnection.php';
// Include the file that provides the `totalJokes` function
include_once __DIR__ . '/../classes/DatabaseTable.php';
// Call the function
$jokeTable = new DatabaseTable($pdo, 'joke', 'id');

echo $jokeTable->totalRows($pdo, 'joke');
