<?php
/**
 * Date: 07.04.2020
 * Time: 21:30
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;

/**
 * Class Update
 * @package kradwhite\db
 */
class Update extends DataQuery
{
    /**
     * @return int
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function prepareExecute(): int
    {
        $query = "UPDATE {$this->table} SET ";
        $fields = [];
        foreach ($this->attributes as $name => &$value) {
            $fields[] = "{$this->driver->quote($name)}=:$name";
        }
        $where = [];
        $params = $this->attributes;
        foreach ($this->condition as $name => &$value) {
            $where[] = "{$this->driver->quote($name)}=:c_$name";
            $params[":c_$name"] = $value;
        }
        $query .= implode(', ', $fields);
        if ($where) {
            $query .= ' WHERE ' . implode(' AND ', $where);
        }
        return $this->_prepareExecute($query, $params, $this->types)->rowCount();
    }
}