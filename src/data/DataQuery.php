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

    /** @var array */
    protected array $types;

    /**
     * DataQuery constructor.
     * @param string $table
     * @param array $attributes
     * @param array $condition
     * @param array $types
     * @param Driver $driver
     */
    public function __construct(string $table, array $attributes, array $condition, array $types, Driver $driver)
    {
        $this->table = $driver->quote($table);
        $this->attributes = $attributes;
        $this->condition = $condition;
        $this->types = $types;
        $this->driver = $driver;
    }
}