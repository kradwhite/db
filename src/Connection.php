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
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;
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
     * @return data\Insert
     */
    public function insert(string $table, array $attributes): Insert
    {
        return new Insert($table, $attributes, $this->driver);
    }

    /**
     * @param string $table
     * @param array $fields
     * @param array $attributes
     * @return InsertMultiple
     */
    public function insertMultiple(string $table, array $fields, array $attributes): InsertMultiple
    {
        return new InsertMultiple($table, $attributes, $fields, $this->driver);
    }

    /**
     * @param string $table
     * @param array $attributes
     * @param array $condition
     * @return Update
     */
    public function update(string $table, array $attributes, array $condition): Update
    {
        return new Update($table, $attributes, $condition, $this->driver);
    }

    /**
     * @param string $table
     * @param array $condition
     * @return Delete
     */
    public function delete(string $table, array $condition): Delete
    {
        return new Delete($table, $condition, $this->driver);
    }

    /**
     * @param string $table
     * @param array $fields
     * @param array $condition
     * @return SelectOne
     */
    public function selectOne(string $table, array $fields, array $condition): SelectOne
    {
        return new SelectOne($table, $fields, $condition, $this->driver);
    }

    /**
     * @param string $table
     * @param array $fields
     * @param array $condition
     * @return SelectMultiple
     */
    public function selectMultiple(string $table, array $fields, array $condition): SelectMultiple
    {
        return new SelectMultiple($table, $fields, $condition, $this->driver);
    }

    /**
     * @param string $query
     * @param array $params
     * @return int
     * @throws PdoException
     * @throws PdoStatementException
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
     * @throws PdoException
     * @throws PdoStatementException
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
     * @throws PdoException
     * @throws PdoStatementException
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
     * @throws PdoException
     * @throws PdoStatementException
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
     * @throws PdoException
     */
    public function begin()
    {
        $this->driver->begin();
    }

    /**
     * @return void
     * @throws PdoException
     */
    public function commit()
    {
        $this->driver->commit();
    }

    /**
     * @return void
     * @throws PdoException
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