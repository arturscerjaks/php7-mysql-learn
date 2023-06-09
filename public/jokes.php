<?php
try {
  $pdo = new PDO(
    'mysql:host=mysql;dbname=ijdb;charset=utf8mb4',
    'ijdbuser',
    'mypassword'
  );

  $sql = 'SELECT `joketext`, `id` FROM joke';
  $jokes = $pdo->query($sql);
  $title = 'Joke list';

  ob_start();
  include __DIR__ . '/../templates/jokes.html.php';
  $output = ob_get_clean();

} catch (PDOException $e) {
  $title = 'An error has occurred';
  
  $output = 'Database error: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/../templates/layout.html.php';
