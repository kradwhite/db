<?php
/**
 * Date: 07.04.2020
 * Time: 21:30
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\QueryException;

/**
 * Class Update
 * @package kradwhite\db
 */
class Update extends DataQuery
{
    /**
     * Update constructor.
     * @param string $table
     * @param array $attributes
     * @param array $condition
     * @param Driver $driver
     */
    public function __construct(string $table, array $attributes, array $condition, Driver $driver)
    {
        parent::__construct($table, $attributes, $condition, $driver);
    }

    /**
     * @throws QueryException
     */
    public function prepareExecute(): int
    {
        $query = "UPDATE {$this->table} SET ";
        $fields = [];
        $params = [];
        foreach ($this->attributes as $name => &$value) {
            $fields[] = "{$this->driver->quote($name)}=:$name";
            $params[$name] = $value;
        }
        $where = [];
        foreach ($this->condition as $name => &$value) {
            $where[] = "{$this->driver->quote($name)}=:c_$name";
            $params["c_$name"] = $value;
        }
        return $this->_prepareExecute($query . implode(', ', $fields) . " WHERE " . implode(' AND ', $where), $params)->rowCount();
    }
}