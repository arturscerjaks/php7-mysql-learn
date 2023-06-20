<?php

use App\Classes\DatabaseTable;
use PDOException;

try {
  include __DIR__ . '/../includes/DatabaseConnection.php';
  include __DIR__ . '/../classses/DatabaseTable.php';

  $jokeTable = new DatabaseTable($pdo, 'joke', 'id');
  $authorTable = new DatabaseTable($pdo, 'author', 'id');

  $result = $jokeTable->findAll();

  $jokes = [];
  foreach ($result as $joke) {
          $author = $authorTable->find('id', $joke['authorid'])[0];

          $jokes[] = [
                  'id' => $joke['id'],
                  'joketext' => $joke['joketext'],
                  'jokedate' => $joke['jokedate'],
                  'name' => $author['name'],
                  'email' => $author['email']
          ];
  }

  $title = 'Joke list';

  $totalJokes = $jokeTable->totalRows();

  ob_start();

  include  __DIR__ . '/../templates/jokes.html.php';

  $output = ob_get_clean();

} catch (PDOException $e) {
  $title = 'An error has occurred';

  $output = 'Database error: ' . $e->getMessage() . ' in ' .
    $e->getFile() . ':' . $e->getLine();
}

include  __DIR__ . '/../templates/layout.html.php';
