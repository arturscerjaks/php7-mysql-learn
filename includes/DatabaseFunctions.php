<?php

/**
 * @param pdo $pdo
 */

function totalJokes($pdo): int
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM `joke`');
    $stmt->execute();
    $row = $stmt->fetch();
    return $row[0];
}

/**
 * @param pdo $pdo
 * @param int $id `joke`.`id`
 * @return array
 * Array's structure:
 *  [
 *      `id` => int,
 *      `joketext` => string,
 *      `date` => DateTime,
 *      `authorid` => int
 *  ]
 */

function getJoke($pdo, $id): array
{
    $stmt = $pdo->prepare('SELECT * FROM `joke` WHERE `id` = :id');
    $values = [
        'id' => $id
    ];
    $stmt->execute($values);
    return $stmt->fetch();
}

/**
 * @param pdo $pdo
 * @param string $joketext
 * @param int $authorId
 */

function insertJoke($pdo, $joketext, $authorId): void
{
    $stmt = $pdo->prepare('INSERT INTO `joke` (`joketext`, `jokedate`, `authorid`)
    VALUES (:joketext, :jokedate, :authorId)');

    $values = [
        ':joketext' => $joketext,
        ':authorId' => $authorId,
        ':jokedate' => date('Y-m-d')
    ];

    $stmt->execute($values);
}

/**
 * @param pdo $pdo
 * @param mixed[] $values
 */

function updateJoke($pdo, $values): void
{
    $query = ' UPDATE `joke` SET ';
    $updateFields = [];
    foreach ($values as $key => $value) {
        $updateFields[] = '`' . $key . '` = :' . $key;
    }

    $query .= implode(', ', $updateFields);
    $query .= ' WHERE `id` = :primaryKey';
    // Set the :primaryKey variable
    $values['primaryKey'] = $values['id'];

    $stmt = $pdo->prepare($query);
    $stmt->execute($values);
}

/**
 * @param pdo $pdo
 * @param int $id
 */

function deleteJoke($pdo, $id): void
{
    $stmt = $pdo->prepare('DELETE FROM `joke` WHERE `id` = :id');

    $values = [':id' => $id];

    $stmt->execute($values);
}

/**
 * @param pdo $pdo
 * @return array
 * Array's structure:
 *  [
 *      `id` => int,
 *      `joketext` => string, 
 *      `name` => string,
 *      `email` => string
 *  ]
 */

function allJokes($pdo): array
{
    $stmt = $pdo->prepare(
        'SELECT `joke`.`id`, `joketext`, `name`, `email` 
        FROM `joke` 
        INNER JOIN `author` ON `authorid` = `author`.`id`'
    );

    $stmt->execute();

    return $stmt->fetchAll();
}
