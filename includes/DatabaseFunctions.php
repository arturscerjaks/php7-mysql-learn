<?php

/**
 * Counts amount of rows in `joke` table
 * 
 * 
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
 * Gets a joke's row's values by id from `joke` table 
 * 
 * 
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
 * Inserts a joke into `joke` table. 
 * 
 * 
 * @param pdo $pdo
 * @param array $values
 * $values:
 *  [
 *      'joketext' => `string`,
 *      'jokedate' => `DateTime`,
 *      'authorId' => `int`
 *  ]
 */

function insertJoke($pdo, $values): void
{
    $query = 'INSERT INTO `joke` (';

    foreach ($values as $key => $value) {
        $query .= '`' . $key . '`,';
    }
    $query = rtrim($query, ',');

    $query .= ') VALUES (';

    foreach ($values as $key => $value) {
        $query .= ':' . $key . ',';
    }
    $query = rtrim($query, ',');
    $query .= ')';

    $values = processDates($values);

    $stmt = $pdo->prepare($query);
    $stmt->execute($values);
}

/**
 * Updates joke from `joke` table
 * 
 * 
 * @param pdo $pdo
 * @param mixed[] $values
 */

function updateJoke($pdo, $values): void
{
    $query = ' UPDATE joke SET ';
    foreach ($values as $key => $value) {
        $query .= '`' . $key . '` = :' . $key . ',';
    }
    $query = rtrim($query, ',');
    $query .= ' WHERE id = :primarykey';

    $values = processDates($values);

    // Set the :primaryKey variable
    $values['primaryKey'] = $values['id'];

    $stmt = $pdo->prepare($query);
    $stmt->execute($values);
}

/**
 * Deletes joke by id from `joke` table
 * 
 * 
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
 * Returns an array of arrays that consists of id, joketext, name and email fields from `joke` table 
 * 
 * 
 * @param pdo $pdo
 * @return array
 * Array's structure:
 *  [
 *      ['id' => `int`,
 *      'joketext' => `string`, 
 *      'name' => `string`,
 *      'email' => `string`]
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
/**
 * @param mixed[] $values
 */

function processDates($values): array
{
    foreach ($values as $key => $value) {
        if ($value instanceof DateTime) {
            $values[$key] = $value->format('Y-m-d H:i:s');
        }
    }
    return $values;
}
