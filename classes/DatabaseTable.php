<?php

namespace App\Classes;

use DateTime, PDO, PDOException;

class DatabaseTable
{
    private $pdo;
    private $table;
    private $primaryKey;

    /**Creates instance of DatabaseTable with class variables*/

    public function __construct(PDO $pdo, string $table, string $primaryKey)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /** Counts amount of rows in `$this->table`*/

    function totalRows(): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM `' . $this->table . '`');
        $stmt->execute();
        $row = $stmt->fetch();
        return $row[0];
    }

    /**
     * Returns all rows of a `$this->table` where `$field`'s value is `$value` as multidimensional array.
     * 
     *  
     * @param string $field
     * @param string|int $value
     * @return array[]
     */

    function find($field, $value)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $field . '` = :value';

        $values = [
            'value' => $value
        ];

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt->fetchAll();
    }

    /**
     * Inserts `$values` into `$this->table` 
     * 
     * 
     * @param array $values
     */

    private function insert($values): void
    {
        $query = 'INSERT INTO `' . $this->table . '` (';

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

        $values = $this->processDates($values);

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
    }

    /**
     * Updates `$this->table` to set `$values` where `$this->primaryKey` is `$values['id']`
     * 
     * 
     * @param array $values
     */

    private function update($values): void
    {
        $query = ' UPDATE `' . $this->table . '` SET ';

        foreach ($values as $key => $value) {
            $query .= '`' . $key . '` = :' . $key . ',';
        }

        $query = rtrim($query, ',');

        $query .= ' WHERE`' . $this->primaryKey . '` = :primaryKey';

        // Set the :primaryKey variable
        $values['primaryKey'] = $values['id'];

        $values = $this->processDates($values);

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
    }

    /**
     * Deletes row from `$this->table` where `$field`'s value is `$value`
     * 
     * 
     * @param string $field
     * @param string|int $value
     */

    function delete($field, $value): void
    {
        $values = [':value' => $value];

        $stmt = $this->pdo->prepare('DELETE FROM `' . $this->table . '` WHERE `' . $field . '` = :value');

        $stmt->execute($values);
    }

    /**
     * Returns all rows of `$this->table` as a multidimensional array
     * 
     * 
     * @return array[]
     */

    function findAll()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM `' . $this->table . '`');
        $stmt->execute();

        return $stmt->fetchAll();
    }
    /**
     * Returns modified array where DateTime objects are formatted the same way
     * 
     * 
     * @example e.g., 5th July 2021
     * @param array $values
     */

    private function processDates($values): array
    {
        foreach ($values as $key => $value) {
            if ($value instanceof DateTime) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            }
        }
        return $values;
    }

    /**
     * Tries to insert() `$record` into `$this->table` based on `$this->primaryKey`, 
     * will update() instead if `$this->primaryKey` is duplicate.
     * 
     * @param array $record
     */


    function save($record): void
    {
        try {
            if (empty($record[$this->primaryKey])) {
                unset($record[$this->primaryKey]);
            }
            $this->insert($record);
        } catch (PDOException $e) {
            $this->update($record);
        }
    }
}
