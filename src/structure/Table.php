<?php
/**
 * Date: 10.04.2020
 * Time: 8:36
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\structure;

use kradwhite\db\data\Insert;
use kradwhite\db\data\InsertMultiple;
use kradwhite\db\driver\Driver;
use kradwhite\db\exception\BeforeQueryException;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;
use kradwhite\db\StmtTrait;
use kradwhite\db\syntax\TableSyntax;

/**
 * Class Table
 * @package kradwhite\db\structure
 */
class Table
{
    use StmtTrait;

    /** @var string */
    private string $table;

    /** @var array */
    private array $columns = [];

    /** @var array */
    private array $indexes = [];

    /** @var array */
    private array $foreignKeys = [];

    /** @var array */
    private array $primaryKeys = ['columns' => [], 'options' => []];

    /** @var array */
    private array $options;

    /**
     * Table constructor.
     * @param Driver $driver
     * @param string $table
     * @param array $options
     */
    public function __construct(Driver $driver, string $table, array $options)
    {
        $this->driver = $driver;
        $this->table = $table;
        $this->options = $options;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $options
     * @return Table
     * @throws BeforeQueryException
     */
    public function addColumn(string $name, string $type, array $options = []): Table
    {
        if (isset($this->columns[$name])) {
            throw new BeforeQueryException('re-adding-column', [$this->quote($name)]);
        }
        $this->columns[$name] = compact(['type', 'options']);
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $options
     * @return Table
     * @throws PdoException
     */
    public function createColumn(string $name, string $type, array $options = []): Table
    {
        $this->_execute($this->getTableSyntax()->createColumn($this->table, $name, $type, $options));
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $options
     * @return Table
     * @throws PdoException
     */
    public function alterColumn(string $name, string $type, array $options = []): Table
    {
        $this->_execute($this->getTableSyntax()->alterColumn($this->table, $name, $type, $options));
        return $this;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return Table
     * @throws PdoException
     */
    public function renameColumn(string $oldName, string $newName): Table
    {
        $this->_execute($this->getTableSyntax()->renameColumn($this->table, $oldName, $newName));
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return Table
     * @throws PdoException
     */
    public function dropColumn(string $name, array $options = []): Table
    {
        $this->_execute($this->getTableSyntax()->dropColumn($this->table, $name, $options));
        return $this;
    }

    /**
     * @param array $columns
     * @param array $options
     * @param string $name
     * @return Table
     * @throws BeforeQueryException
     */
    public function addIndex(array $columns, array $options = [], string $name = ''): Table
    {
        $name = $this->getTableSyntax()->buildIndexName($name, $this->table, $columns, $options);
        if (isset($this->indexes[$name])) {
            throw new BeforeQueryException('re-adding-index', [$this->quote($name)]);
        }
        $this->indexes[$name] = compact(['columns', 'options']);
        return $this;
    }

    /**
     * @param array $columns
     * @param array $options
     * @param string $name
     * @return Table
     * @throws PdoException
     */
    public function createIndex(array $columns, array $options = [], string $name = ''): Table
    {
        $this->_execute($this->getTableSyntax()->createIndex($this->table, $columns, $options, $name));
        return $this;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return Table
     * @throws PdoException
     */
    public function renameIndex(string $oldName, string $newName): Table
    {
        $this->_execute($this->getTableSyntax()->renameIndex($this->table, $oldName, $newName));
        return $this;
    }

    /**
     * @param string $name
     * @return Table
     * @throws PdoException
     */
    public function dropIndex(string $name): Table
    {
        $this->_execute($this->getTableSyntax()->dropIndex($this->table, $name));
        return $this;
    }

    /**
     * @param array $columns
     * @param string $table
     * @param array $columns2
     * @param array $options
     * @param string $name
     * @return Table
     * @throws BeforeQueryException
     */
    public function addForeignKey(array $columns, string $table, array $columns2, array $options = [], string $name = ''): Table
    {
        $name = $this->getTableSyntax()->buildForeignKeyName($name, $this->table, $table);
        if (isset($this->foreignKeys[$name])) {
            throw new BeforeQueryException('re-adding-foreign-key', [$this->quote($name)]);
        }
        $this->foreignKeys[$name] = compact(['columns', 'table', 'columns2', 'options']);
        return $this;
    }

    /**
     * @param array $columns
     * @param string $table
     * @param array $columns2
     * @param array $options
     * @param string $name
     * @return Table
     * @throws PdoException
     */
    public function createForeignKey(array $columns, string $table, array $columns2, array $options = [], string $name = ''): Table
    {
        $name = $this->getTableSyntax()->buildForeignKeyName($name, $this->table, $table);
        $this->_execute($this->getTableSyntax()->createForeignKey($name, $this->table, $columns, $table, $columns2, $options));
        return $this;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return Table
     * @throws PdoException
     */
    public function renameForeignKey(string $oldName, string $newName): Table
    {
        $this->_execute($this->getTableSyntax()->renameForeignKey($this->table, $oldName, $newName));
        return $this;
    }

    /**
     * @param string $name
     * @return Table
     * @throws PdoException
     */
    public function dropForeignKey(string $name): Table
    {
        $this->_execute($this->getTableSyntax()->dropForeignKey($this->table, $name));
        return $this;
    }

    /**
     * @param string $column
     * @param array $options
     * @return Table
     * @throws BeforeQueryException
     */
    public function primaryKey(string $column, array $options = []): Table
    {
        if (in_array($column, $this->primaryKeys['columns'])) {
            throw new BeforeQueryException('re-adding-primary-key', [$this->quote($column)]);
        }
        $this->primaryKeys['columns'][] = $column;
        $this->primaryKeys['options'] += $options;
        return $this;
    }

    /**
     * @param array $columns
     * @param array $options
     * @return Table
     * @throws BeforeQueryException
     */
    public function compositePrimaryKey(array $columns, array $options): Table
    {
        foreach ($columns as $column) {
            $this->primaryKey($column);
        }
        $this->primaryKeys['options'] += $options;
        return $this;
    }

    /**
     * @return void
     * @throws PdoException
     */
    public function create(): void
    {
        $this->_execute($this->getTableSyntax()->createTable($this->table, $this->columns, $this->indexes, $this->foreignKeys, $this->primaryKeys, $this->options));
    }

    /**
     * @param string $newName
     * @return Table
     * @throws PdoException
     */
    public function rename(string $newName): Table
    {
        $this->_execute($this->getTableSyntax()->renameTable($this->table, $newName));
        return $this;
    }

    /**
     * @return void
     * @throws PdoException
     */
    public function drop(): void
    {
        $this->_execute($this->getTableSyntax()->dropTable($this->table));
    }

    /**
     * @param array $attributes
     * @param array $types
     * @return Table
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function insert(array $attributes, array $types = []): Table
    {
        (new Insert($this->table, $attributes, [], $types, $this->driver))->prepareExecute();
        return $this;
    }

    /**
     * @param array $attributes
     * @param array $fields
     * @param array $types
     * @return Table
     * @throws PdoException
     * @throws PdoStatementException
     */
    public function insertMultiple(array $attributes, array $fields, array $types = []): Table
    {
        (new InsertMultiple($this->table, $attributes, $fields, $types, $this->driver))->prepareExecute();
        return $this;
    }

    /**
     * @param string $object
     * @return string
     */
    private function quote(string $object): string
    {
        return $this->driver->quote($object);
    }

    /**
     * @return TableSyntax
     */
    private function getTableSyntax(): TableSyntax
    {
        return $this->driver->getTableSyntax();
    }
}