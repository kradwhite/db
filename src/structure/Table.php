<?php
/**
 * Date: 10.04.2020
 * Time: 8:36
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\structure;

use kradwhite\db\data\Insert;
use kradwhite\db\driver\Driver;
use kradwhite\db\QueryException;
use kradwhite\db\StmtTrait;
use kradwhite\db\syntax\Syntax;

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
    private array $options = [];

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
     * @throws QueryException
     */
    public function addColumn(string $name, string $type, array $options = []): Table
    {
        if (isset($this->columns[$name])) {
            throw new QueryException("Ошибка создания таблицы {$this->quote($this->table)}: повторное добавление колонки {$this->quote($name)}");
        }
        $this->columns[$name] = compact([$type, $options]);
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $options
     * @return Table
     * @throws QueryException
     */
    public function createColumn(string $name, string $type, array $options = []): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->createColumn($this->table, $name, $type, $options));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка создания колонки {$this->quote($name)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array $options
     * @return Table
     * @throws QueryException
     */
    public function alterColumn(string $name, string $type, array $options = []): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->alterColumn($this->table, $name, $type, $options));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка изменения колонки {$this->quote($name)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return Table
     * @throws QueryException
     */
    public function renameColumn(string $oldName, string $newName): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->renameColumn($this->table, $oldName, $newName));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка переименования колонки {$this->quote($oldName)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @return Table
     * @throws QueryException
     */
    public function dropColumn(string $name, array $options = []): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->dropColumn($this->table, $name, $options));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка удаления колонки {$this->quote($name)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param array $columns
     * @param array $options
     * @param string $name
     * @return Table
     */
    public function addIndex(array $columns, array $options = [], string $name = ''): Table
    {
        $this->indexes[$this->getSyntax()->buildIndexName($name, $this->table, $columns)] = compact([$columns, $options]);
        return $this;
    }

    /**
     * @param array $columns
     * @param array $options
     * @param string $name
     * @return Table
     * @throws QueryException
     */
    public function createIndex(array $columns, array $options = [], string $name = ''): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->createIndex($this->table, $columns, $options, $name));
        if (!$stmt->execute()) {
            $name = $this->getSyntax()->buildIndexName($name, $this->table, $columns);
            throw new QueryException("Ошибка создания индекса {$this->quote($name)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return Table
     * @throws QueryException
     */
    public function renameIndex(string $oldName, string $newName): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->renameIndex($this->table, $oldName, $newName));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка переименования индекса {$this->quote($oldName)}: ", $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $name
     * @return Table
     * @throws QueryException
     */
    public function dropIndex(string $name): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->dropIndex($this->table, $name));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка удаления индекса {$this->quote($name)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param array $columns
     * @param string $table
     * @param array $columns2
     * @param array $options
     * @param string $name
     * @return Table
     */
    public function addForeignKey(array $columns, string $table, array $columns2, array $options = [], string $name = ''): Table
    {
        $name = $this->getSyntax()->buildForeignKeyName($name, $this->table, $columns, $table, $columns2);
        $this->foreignKeys[$name] = compact([$columns, $table, $columns2, $options]);
        return $this;
    }

    /**
     * @param array $columns
     * @param string $table
     * @param array $columns2
     * @param array $options
     * @param string $name
     * @return Table
     * @throws QueryException
     */
    public function createForeignKey(array $columns, string $table, array $columns2, array $options = [], string $name = ''): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->createForeignKey($name, $this->table, $columns, $table, $columns2, $options));
        if (!$stmt->execute()) {
            $name = $this->getSyntax()->buildForeignKeyName($name, $this->table, $columns, $table, $columns2);
            throw new QueryException("Ошибка создания внешнего ключа {$this->quote($name)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return Table
     * @throws QueryException
     */
    public function renameForeignKey(string $oldName, string $newName): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->renameForeignKey($this->table, $oldName, $newName));
        if (!$stmt->execute()) {
            throw new QueryException("Ошика переименования внешнего ключа {$this->quote($oldName)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $name
     * @return Table
     * @throws QueryException
     */
    public function dropForeignKey(string $name): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->dropForeignKey($this->table, $name));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка удаления внешнего ключа {$this->quote($name)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @param string $column
     * @param array $options
     * @return Table
     * @throws QueryException
     */
    public function primaryKey(string $column, array $options = []): Table
    {
        if (in_array($column, $this->primaryKeys['columns'])) {
            throw new QueryException("Ошибка создания таблицы: дублирование первичного ключа {$this->quote($column)}");
        }
        $this->primaryKeys['columns'][] = $column;
        $this->primaryKeys['options'] += $options;
        return $this;
    }

    /**
     * @param array $columns
     * @param array $options
     * @return Table
     * @throws QueryException
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
     * @throws QueryException
     */
    public function create(): void
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->createTable($this->table, $this->columns, $this->indexes, $this->foreignKeys, $this->primaryKeys, $this->options));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка создания таблицы {$this->quote($this->table)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
    }

    /**
     * @param string $newName
     * @return Table
     * @throws QueryException
     */
    public function rename(string $newName): Table
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->renameTable($this->table, $newName));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка переименования таблицы {$this->quote($this->table)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
        return $this;
    }

    /**
     * @return void
     * @throws QueryException
     */
    public function drop(): void
    {
        $stmt = $this->_prepareExecute($this->getSyntax()->dropTable($this->table));
        if (!$stmt->execute()) {
            throw new QueryException("Ошибка удаления таблицы {$this->quote($this->table)}: " . $stmt->errorInfo(), $stmt->errorCode());
        }
    }

    /**
     * @param array $attributes
     * @param array $types
     * @return Table
     * @throws QueryException
     */
    public function insert(array $attributes, array $types = []): Table
    {
        (new Insert($this->table, $attributes, $types, $this->driver))->prepareExecute();
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
     * @return Syntax
     */
    private function getSyntax(): Syntax
    {
        return $this->driver->getSyntax();
    }
}