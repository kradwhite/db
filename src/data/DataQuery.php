<?php
/**
 * Date: 08.04.2020
 * Time: 8:24
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\StmtTrait;

/**
 * Class DataQuery
 * @package kradwhite\db
 */
class DataQuery
{
    use StmtTrait;

    /** @var string */
    protected string $table;

    /** @var array */
    protected array $attributes;

    /** @var array */
    protected array $condition;

    /**
     * DataQuery constructor.
     * @param string $table
     * @param array $attributes
     * @param array $condition
     * @param Driver $driver
     */
    public function __construct(string $table, array $attributes, array $condition, Driver $driver)
    {
        $this->table = $driver->quote($table);
        $this->attributes = $attributes;
        $this->condition = $condition;
        $this->driver = $driver;
    }
}