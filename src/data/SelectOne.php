<?php
/**
 * Date: 08.04.2020
 * Time: 8:39
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;

/**
 * Class SelectOne
 * @package kradwhite\db
 */
class SelectOne extends DataQuery
{
    /**
     * @param string $style
     * @return array
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function prepareExecute(string $style = 'assoc'): array
    {
        $condition = [];
        $fields = $this->attributes ? implode(', ', $this->driver->quotes($this->attributes)) : '*';
        foreach ($this->condition as $name => &$value) {
            $condition[] = "{$this->driver->quote($name)}=:$name";
        }
        $stmt = $this->_prepareExecute("SELECT $fields FROM {$this->table} WHERE " . implode(' AND ', $condition) . " LIMIT 1", $this->condition);
        $data = $stmt->fetch($style);
        $this->closeCursor($stmt);
        return $data;
    }
}