<?php
/**
 * Date: 07.04.2020
 * Time: 21:19
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;

/**
 * Class Insert
 * @package kradwhite\db
 */
class Insert extends DataQuery
{
    /**
     * @param string|null $sequence
     * @return string
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function prepareExecute(string $sequence = null): string
    {
        $fieldNames = array_keys($this->attributes);
        $query = "INSERT INTO {$this->table} (" . implode(', ', $this->driver->quotes($fieldNames)) . ") VALUES (";
        for ($i = 0; $i < count($fieldNames); ++$i) {
            $fieldNames[$i] = ":{$fieldNames[$i]}";
        }
        $this->_prepareExecute($query . implode(', ', $fieldNames) . ")", $this->attributes, $this->types);
        return $this->driver->getPdo()->lastInsertId($sequence);
    }
}