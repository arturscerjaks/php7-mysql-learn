<?php
try {
    $pdo = new PDO('mysql:host=mysql;dbname=ijdb;charset=utf8mb4', 'ijdbuser', 'mypassword');

    $sql = 'SELECT `joketext` FROM `joke`';
    $result = $pdo->query($sql);

    while ($row = $result->fetch()) {
        $jokes[] = $row['joketext'];
        }
        $title = 'Joke list';
        // Start the buffer
        ob_start();
        // Include the template. The PHP code will be executed,
        // but the resulting HTML will be stored in the buffer
        // rather than sent to the browser.
        include __DIR__ . '/../templates/jokes.html.php';
        // Read the contents of the output buffer and store them
        // in the $output variable for use in layout.html.php
        $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';

    $output = 'Database error: ' . $e->getMessage() . ' in ' .
  $e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/../templates/layout.html.php';