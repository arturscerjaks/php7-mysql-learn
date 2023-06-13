<?php

try {
    include '../includes/DatabaseConnection.php';
    include '../includesDatabaseFunctions.php';

    if (isset($_POST['joketext'])) {
        updateJoke($pdo, $_POST['jokeid'], $_POST['joketext'], 1);
        header('location: jokes.php');
    } else {
        $joke = getJoke($pdo, $_GET['id']);
        $title = 'Edit joke';

        ob_start();
        include '../templates/editjoke.html.php';
        $output = ob_get_clean();
    }
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage() . ' in ' .
        $e->getFile() . ':' . $e->getLine();
};
