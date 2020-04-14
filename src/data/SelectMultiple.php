<?php
/**
 * Date: 08.04.2020
 * Time: 18:53
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;

/**
 * Class SelectMultiple
 * @package kradwhite\db
 */
class SelectMultiple extends DataQuery
{
    /**
     * @param string $style
     * @param array $order
     * @param int $limit
     * @return array
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function prepareExecute(string $style = 'assoc', array $order = [], int $limit = 0): array
    {
        $condition = [];
        $fields = $this->attributes ? implode(', ', $this->driver->quotes($this->attributes)) : '*';
        foreach ($this->condition as $name => &$value) {
            $condition[] = "{$this->driver->quote($name)}=:$name";
        }
        $query = "SELECT $fields FROM {$this->table} WHERE " . implode(' AND ', $condition);
        if ($order) {
            $ascOrDesc = count($order) > 1 ? array_pop($order) : 'ASC';
            $query .= " ORDER BY (" . implode(', ', $this->driver->quotes($order)) . ") $ascOrDesc";
        }
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        $stmt = $this->_prepareExecute($query, $this->condition);
        $data = $stmt->fetchAll($style);
        $this->closeCursor($stmt);
        return $data;
    }
}