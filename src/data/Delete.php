<?php
/**
 * Date: 08.04.2020
 * Time: 8:20
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;

/**
 * Class Delete
 * @package kradwhite\db
 */
class Delete extends DataQuery
{
    /**
     * @return int
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function prepareExecute(): int
    {
        $condition = [];
        foreach ($this->condition as $name => &$value) {
            $condition[] = "{$this->driver->quote($name)}=:$name";
        }
        $query = "DELETE FROM {$this->table} WHERE " . implode(' AND ', $condition);
        return $this->_prepareExecute($query, $this->condition, $this->types)->rowCount();
    }
}