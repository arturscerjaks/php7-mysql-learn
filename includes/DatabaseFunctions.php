<?php

/**
 * Counts amount of rows in `$table` 
 * 
 * @param pdo $pdo
 * @param string $table
 */

function totalRows($pdo, $table): int
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM `' . $table . '`');
    $stmt->execute();
    $row = $stmt->fetch();
    return $row[0];
}

/**
 * Returns all rows of a `$table` where `$field`'s value is `$value` as multidimensional array
 * 
 * 
 * @param pdo $pdo
 * @param string $table 
 * @param string $field
 * @param string|int $value
 * @return array[]
 */

function find($pdo, $table, $field, $value)
{
    $query = 'SELECT * FROM `' . $table . '` WHERE `' . $field . '` = :value';

    $values = [
        'value' => $value
    ];

    $stmt = $pdo->prepare($query);
    $stmt->execute($values);
    return $stmt->fetchAll();
}

/**
 * Inserts `$values` into `$table` 
 * 
 * 
 * @param pdo $pdo
 * @param string $table
 * @param array $values
 */

function insert($pdo, $table, $values): void
{
    $query = 'INSERT INTO `'. $table .'` (';

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
        'SELECT `joke`.`id`, `joketext`, `jokedate`, `name`, `email`  
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
