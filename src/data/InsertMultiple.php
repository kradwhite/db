<?php
/**
 * Date: 08.04.2020
 * Time: 19:02
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\QueryException;

/**
 * Class InsertMultiple
 * @package kradwhite\db
 */
class InsertMultiple extends DataQuery
{
    /**
     * @return void
     * @throws QueryException
     */
    public function prepareExecute()
    {
        $query = "INSERT INTO {$this->table} (" . implode(', ', $this->driver->quotes($this->condition)) . ") VALUES ";
        $attributes = [];
        $rows = [];
        for ($i = 0; $i < count($this->attributes); ++$i) {
            $row = [];
            for ($j = 0; $j < count($this->condition); ++$j) {
                $name = ":{$i}_{$this->condition[$i]}";
                $row[] = $name;
                $attributes[$name] = $this->attributes[$i][$j];
            }
            $rows[] = "(" . implode(',', $rows) . ")";
        }
        $this->_prepareExecute($query . implode(', ', $rows), $attributes);
    }
}