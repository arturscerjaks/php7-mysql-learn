<?php
function totalJokes($database)
{
    $stmt = $database->prepare('SELECT COUNT(*) FROM `joke`');
    $stmt->execute();
    $row = $stmt->fetch();
    return $row[0];
}

function getJoke($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM `joke` WHERE `id` = :id');
    $values = [
    'id' => $id
    ];
    $stmt->execute($values);
    return $stmt->fetch();
    }
