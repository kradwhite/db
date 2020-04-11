<?php
/**
 * Date: 08.04.2020
 * Time: 8:20
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\QueryException;

/**
 * Class Delete
 * @package kradwhite\db
 */
class Delete extends DataQuery
{
    /**
     * Delete constructor.
     * @param string $table
     * @param array $condition
     * @param Driver $driver
     */
    public function __construct(string $table, array $condition, Driver $driver)
    {
        parent::__construct($table, [], $condition, $driver);
    }

    /**
     * @return int
     * @throws QueryException
     */
    public function prepareExecute(): int
    {
        $condition = [];
        foreach ($this->condition as $name => &$value) {
            $condition[] = "{$this->driver->quote($name)}=:$name";
        }
        return $this->_prepareExecute("DELETE FROM {$this->table} WHERE " . implode(' AND ', $condition), $this->condition)->rowCount();
    }
}