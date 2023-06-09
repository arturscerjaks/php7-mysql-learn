<?php

try {
    $pdo = new PDO(
        'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
        'ijdbuser',
        'mypassword'
    );
    $sql = 'SELECT `id`, `joketext` FROM `joke`';
    $result = $pdo->query($sql);

    while ($row = $result->fetch()) {
        $jokes[] = ['id' => $row['id'], 'joketext' => $row['joketext']];
    }
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage() . ' in ' .
        $e->getFile() . ':' . $e->getLine();
}
