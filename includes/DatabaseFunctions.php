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
 * Returns all rows of a `$table` where `$field`'s value is `$value` as multidimensional array.
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
    $query = 'INSERT INTO `' . $table . '` (';

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
 * Updates `$table` to set `$values` where `$primaryKey` is `$values['id']`
 * 
 * 
 * @param pdo $pdo
 * @param string $table
 * @param string $primaryKey
 * @param array $values
 */

function update($pdo, $table, $primaryKey, $values): void
{
    $query = ' UPDATE `' . $table . '` SET ';

    foreach ($values as $key => $value) {
        $query .= '`' . $key . '` = :' . $key . ',';
    }

    $query = rtrim($query, ',');

    $query .= ' WHERE`' . $primaryKey . '` = :primarykey';

    // Set the :primaryKey variable
    $values['primaryKey'] = $values['id'];

    $values = processDates($values);

    $stmt = $pdo->prepare($query);
    $stmt->execute($values);
}

/**
 * Deletes row from `$table` where `$field`'s value is `$value`
 * 
 * 
 * @param pdo $pdo
 * @param string $table
 * @param string $field
 * @param string|int $value
 */

function delete($pdo, $table, $field, $value): void
{
    $values = [':value' => $value];

    $stmt = $pdo->prepare('DELETE FROM `' . $table . '` WHERE `' . $field . '` = :value');

    $stmt->execute($values);
}

/**
 * Returns all rows of `$table` as a multidimensional array
 * 
 * 
 * @param pdo $pdo
 * @param string @table
 * @return array[]
 */

function findAll($pdo, $table)
{
    $stmt = $pdo->prepare('SELECT * FROM `' . $table . '`');
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
