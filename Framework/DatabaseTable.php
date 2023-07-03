<?php

/**Commonly used generic database operation methods*/

namespace Framework;

class DatabaseTable
{
    private $pdo;
    private $table;
    private $primaryKey;
    private $className;
    private $constructorArgs;

    /**
     * Creates instance of DatabaseTable with class variables
     * 
     * @param \PDO $pdo
     * @param string $table name of DB table
     * @param string $primaryKey primary key of said DB table
     * @param string $className object class for return type
     * @param array $constructorArgs necessary for chosen object class
     */


    public function __construct(\PDO $pdo, string $table, string $primaryKey, string $className = '\stdClass', array $constructorArgs = [])
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
        $this->constructorArgs = $constructorArgs;
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
     * Returns all rows of a `$this->table` where `$field`'s value is `$value` as object.
     * 
     * 
     * @param string $field
     * @param string|int $value
     * @return object specified in DatabaseTable instance constructor
     */

    function find($field, $value)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $field . '` = :value';

        $values = [
            'value' => $value
        ];

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
    }

    /**
     * Inserts `$values` into `$this->table` 
     * 
     * 
     * @param array $values
     * @return string id of last inserted row
     */

    private function insert($values): string
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

        return $this->pdo->lastInsertId();
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
     * Returns all rows of `$this->table` as an object
     * 
     * 
     * @return object specified in DatabaseTable instance constructor
     */

    function findAll()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM `' . $this->table . '`');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->className, $this->constructorArgs);
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
            if ($value instanceof \DateTime) {
                $values[$key] = $value->format('Y-m-d H:i:s');
            }
        }
        return $values;
    }

    /**
     * Tries to insert() `$record` into `$this->table` based on `$this->primaryKey`, 
     * will update() instead if `$this->primaryKey` is duplicate.
     * 
     * 
     * @param array $record
     * @return object specified in $this->className
     */


    function save($record): object
    {
        $entity = new $this->className(...$this->constructorArgs);
      
        try {
            if (empty($record[$this->primaryKey])) {
                unset($record[$this->primaryKey]);
            }
            $insertId = $this->insert($record);

            $entity->{$this->primaryKey} = $insertId;
        } catch (\PDOException $e) {
            $this->update($record);
        }

        foreach ($record as $key => $value) {
            if (!empty($value)) {
                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }
                $entity->$key = $value;
            }
        }

        return $entity;
    }
}
