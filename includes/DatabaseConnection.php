<?php
try {
    $pdo = new PDO('mysql:host=mysql;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'mypassword');

    $sql = 'SELECT `joketext` FROM `joke`';
    $result = $pdo->query($sql);

    while ($row = $result->fetch()) {
        $jokes[] = $row['joketext'];
    }

} catch (PDOException $e) {
    $output = 'Database error: ' . $e->getMessage() . '. Error: ' . $e->getFile() . ': line ' . $e->getLine();
}

include __DIR__ . '/../templates/jokes.html.php';
