<?php
/**
 * Date: 08.04.2020
 * Time: 19:02
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;

/**
 * Class InsertMultiple
 * @package kradwhite\db
 */
class InsertMultiple extends DataQuery
{
    /**
     * InsertMultiple constructor.
     * @param string $table
     * @param array $attributes
     * @param array $fields
     * @param Driver $driver
     */
    public function __construct(string $table, array $attributes, array $fields, Driver $driver)
    {
        parent::__construct($table, $attributes, $fields, $driver);
    }

    /**
     * @return void
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function prepareExecute()
    {
        $query = "INSERT INTO {$this->table} (" . implode(', ', $this->driver->quotes($this->condition)) . ") VALUES ";
        $attributes = [];
        $rows = [];
        for ($i = 0; $i < count($this->attributes); ++$i) {
            $row = [];
            for ($j = 0; $j < count($this->condition); ++$j) {
                $name = "p_{$i}_{$j}";
                $row[] = ":$name";
                $attributes[$name] = $this->attributes[$i][$j];
            }
            $rows[] = "(" . implode(', ', $row) . ")";
        }
        $this->_prepareExecute($query . implode("\n,", $rows), $attributes);
    }
}