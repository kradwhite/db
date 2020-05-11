<?php
/**
 * Date: 08.05.2020
 * Time: 7:38
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\structure;

use kradwhite\db\driver\Driver;
use kradwhite\db\exception\DbException;
use kradwhite\db\FetchStyleTrait;
use kradwhite\db\StmtTrait;

/**
 * Class Meta
 * @package kradwhite\db\structure
 */
class Meta
{
    use StmtTrait;
    use FetchStyleTrait;

    /**
     * Meta constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return array
     * @throws DbException
     */
    public function databases(): array
    {
        return $this->query($this->driver->getMetaSyntax()->databases(), 'column');
    }

    /**
     * @param string $database
     * @return array
     * @throws DbException
     */
    public function tables(string $database): array
    {
        return $this->query($this->driver->getMetaSyntax()->tables(), 'column', [':db' => $database]);
    }

    /**
     * @param string $database
     * @return array
     * @throws DbException
     */
    public function views(string $database): array
    {
        return $this->query($this->driver->getMetaSyntax()->views(), 'column', [':db' => $database]);
    }

    /**
     * @param string $database
     * @return array
     * @throws DbException
     */
    public function columns(string $database): array
    {
        return $this->query($this->driver->getMetaSyntax()->columns(), 'assoc', [':db' => $database]);
    }

    /**
     * @param string $database
     * @return array
     * @throws DbException
     */
    public function primaryKeys(string $database): array
    {
        return $this->query($this->driver->getMetaSyntax()->primaryKeys(), 'assoc', [':db' => $database]);
    }

    /**
     * @param string $database
     * @return array
     * @throws DbException
     */
    public function foreignKeys(string $database): array
    {
        return $this->query($this->driver->getMetaSyntax()->foreignKeys(), 'assoc', [':db' => $database]);
    }

    /**
     * @param string $database
     * @return array
     * @throws DbException
     */
    public function indexes(string $database): array
    {
        $queryAndParams = $this->driver->getMetaSyntax()->indexes($database);
        return $this->query($queryAndParams['query'], 'assoc', $queryAndParams['params']);
    }

    /**
     * @param string $database
     * @return array
     * @throws DbException
     */
    public function sequences(string $database): array
    {
        return $this->query($this->driver->getMetaSyntax()->sequences(), 'column', [':db' => $database]);
    }

    /**
     * @param string $query
     * @param string $style
     * @param array $params
     * @return array
     * @throws DbException
     */
    private function query(string $query, string $style, array $params = []): array
    {
        $stmt = $this->_prepareExecute($query, $params);
        $result = $stmt->fetchAll($this->getStyleFetch($style));
        $this->closeCursor($stmt);
        return $result;
    }
}