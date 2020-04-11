<?php
/**
 * Date: 07.04.2020
 * Time: 21:19
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\QueryException;

/**
 * Class Insert
 * @package kradwhite\db
 */
class Insert extends DataQuery
{
    /**
     * Insert constructor.
     * @param string $table
     * @param array $attributes
     * @param Driver $driver
     */
    public function __construct(string $table, array $attributes, Driver $driver)
    {
        parent::__construct($table, $attributes, [], $driver);
    }

    /**
     * @param string|null $sequence
     * @return string
     * @throws QueryException
     */
    public function prepareExecute(string $sequence = null): string
    {
        $fieldNames = array_keys($this->attributes);
        $query = "INSERT INTO {$this->table} (" . implode(', ', $this->driver->quotes($fieldNames)) . ") VALUES (";
        for ($i = 0; $i < count($fieldNames); ++$i) {
            $fieldNames[$i] = ':' . $fieldNames[$i];
        }
        $this->_prepareExecute($query . implode(', ', $fieldNames) . ")", $this->attributes);
        return $this->driver->getPdo()->lastInsertId($sequence);
    }
}