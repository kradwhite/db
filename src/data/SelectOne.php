<?php
/**
 * Date: 08.04.2020
 * Time: 8:39
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;
use kradwhite\db\FetchStyleTrait;

/**
 * Class SelectOne
 * @package kradwhite\db
 */
class SelectOne extends DataQuery
{
    use FetchStyleTrait;

    /**
     * @param string $style
     * @return array
     * @throws PdoException
     * @throws PdoStatementException
     * @throws BeforeQueryException
     */
    public function prepareExecute(string $style = 'assoc'): array
    {
        $condition = [];
        $fields = $this->attributes ? implode(', ', $this->driver->quotes($this->attributes)) : '*';
        foreach ($this->condition as $name => &$value) {
            $condition[] = "{$this->driver->quote($name)}=:$name";
        }
        $query = "SELECT $fields FROM {$this->table}";
        if ($condition) {
            $query .= ' WHERE ' . implode(' AND ', $condition) . ' LIMIT 1';
        }
        $stmt = $this->_prepareExecute($query, $this->condition, $this->types);
        if (!$data = $stmt->fetch($this->getStyleFetch($style))) {
            $data = [];
        }
        $this->closeCursor($stmt);
        return $data;
    }
}