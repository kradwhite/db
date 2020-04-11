<?php
/**
 * Date: 07.04.2020
 * Time: 19:26
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db;

use kradwhite\db\data\Delete;
use kradwhite\db\data\Insert;
use kradwhite\db\data\InsertMultiple;
use kradwhite\db\data\QueryBuilder;
use kradwhite\db\data\SelectMultiple;
use kradwhite\db\data\SelectOne;
use kradwhite\db\data\Update;
use kradwhite\db\driver\Driver;
use kradwhite\db\structure\Table;

/**
 * Class Connection
 */
class Connection
{
    use StmtTrait;

    /**
     * Connection constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param string $table
     * @param array $attributes
     * @param array $types
     * @return data\Insert
     */
    public function insert(string $table, array $attributes, array $types = []): Insert
    {
        return new Insert($table, $attributes, $types, $this->driver);
    }

    /**
     * @param string $table
     * @param array $fields
     * @param array $attributes
     * @param array $types
     * @return InsertMultiple
     */
    public function insertMultiple(string $table, array $fields, array $attributes, array $types = []): InsertMultiple
    {
        return new InsertMultiple($table, $attributes, $fields, $types, $this->driver);
    }

    /**
     * @param string $table
     * @param array $attributes
     * @param array $condition
     * @param array $types
     * @return Update
     */
    public function update(string $table, array $attributes, array $condition, array $types = []): Update
    {
        return new Update($table, $attributes, $condition, $types, $this->driver);
    }

    /**
     * @param string $table
     * @param array $condition
     * @param array $types
     * @return Delete
     */
    public function delete(string $table, array $condition, array $types = []): Delete
    {
        return new Delete($table, $condition, $types, $this->driver);
    }

    /**
     * @param string $table
     * @param array $fields
     * @param array $condition
     * @param array $types
     * @return SelectOne
     */
    public function selectOne(string $table, array $fields, array $condition, array $types = []): SelectOne
    {
        return new SelectOne($table, $fields, $condition, $types, $this->driver);
    }

    /**
     * @param string $table
     * @param array $fields
     * @param array $condition
     * @param array $types
     * @return SelectMultiple
     */
    public function selectMultiple(string $table, array $fields, array $condition, array $types = []): SelectMultiple
    {
        return new SelectMultiple($table, $fields, $condition, $types, $this->driver);
    }

    /**
     * @param string $query
     * @param array $params
     * @return int
     * @throws QueryException
     */
    public function execute(string $query, array $params = []): int
    {
        return $this->_prepareExecute($query, $params)->rowCount();
    }

    /**
     * @param string $query
     * @param array $params
     * @param string $style
     * @return array
     * @throws QueryException
     */
    public function query(string $query, array $params = [], string $style = 'assoc'): array
    {
        $stmt = $this->_prepareExecute($query, $params);
        $result = $stmt->fetch($style);
        $this->closeCursor($stmt);
        return $result;
    }

    /**
     * @param string $query
     * @param array $params
     * @param string $style
     * @return array
     * @throws QueryException
     */
    public function queryMultiple(string $query, array $params = [], string $style = 'assoc'): array
    {
        $stmt = $this->_prepareExecute($query, $params);
        $result = $stmt->fetchAll($style);
        $this->closeCursor($stmt);
        return $result;
    }

    /**
     * @param string $query
     * @param array $params
     * @param int $column
     * @return array
     * @throws QueryException
     */
    public function queryColumn(string $query, array $params = [], int $column = 0): array
    {
        $stmt = $this->_prepareExecute($query, $params);
        $result = $stmt->fetchColumn($column);
        $this->closeCursor($stmt);
        return $result;
    }

    /**
     * @return QueryBuilder
     */
    public function queryBuilder(): QueryBuilder
    {
        return new QueryBuilder($this->driver);
    }

    /**
     * @param string $table
     * @param array $options
     * @return Table
     */
    public function table(string $table, array $options = []): Table
    {
        return new Table($this->driver, $table, $options);
    }

    /**
     * @return void
     * @throws QueryException
     */
    public function begin()
    {
        $this->driver->begin();
    }

    /**
     * @return void
     * @throws QueryException
     */
    public function commit()
    {
        $this->driver->commit();
    }

    /**
     * @return void
     * @throws QueryException
     */
    public function rollback()
    {
        $this->driver->rollBack();
    }

    /**
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->driver->inTransaction();
    }
}