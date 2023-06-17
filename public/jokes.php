<?php
try {
  include __DIR__ . '/../includes/DatabaseConnection.php';
  include __DIR__ . '/../includes/DatabaseFunctions.php';

  $jokes = findAll($pdo, 'joke');
  
  $title = 'Joke list';
  
  $totalJokes = totalRows($pdo, 'joke');

  ob_start();
  include __DIR__ . '/../templates/jokes.html.php';
  $output = ob_get_clean();

} catch (PDOException $e) {
  $title = 'An error has occurred';

  $output = 'Database error: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/../templates/layout.html.php';
