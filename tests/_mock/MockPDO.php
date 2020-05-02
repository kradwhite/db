<?php
/**
 * Date: 11.04.2020
 * Time: 15:29
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\tests\_mock;

use PDO;

/**
 * Class MockPDO
 * @package kradwhite\tests\_mock
 */
class MockPDO extends PDO
{
    /** @var string */
    private string $query = '';

    /** @var array */
    private array $params = [];

    /**
     * MockPDO constructor.
     */
    public function __construct()
    {
        try {
            parent::__construct('mysql:host=localhost;dbname=postgre', 'username', 'passwd');
        } catch (\Exception $e) {

        }
    }

    /**
     * @param string $statement
     * @param array $driver_options
     * @return MockPDOStatement
     */
    public function prepare($statement, $driver_options = null)
    {
        $this->query = $statement;
        return new MockPDOStatement($this);
    }

    /**
     * @param null $name
     * @return string
     */
    public function lastInsertId($name = null): string
    {
        return '';
    }

    /**
     * @param array $params
     * @return void
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $statement
     * @return false|int
     */
    public function exec($statement)
    {
        $this->query = $statement;
        $this->params = [];
        return 1;
    }
}