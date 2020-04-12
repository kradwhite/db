<?php
/**
 * Date: 12.04.2020
 * Time: 11:44
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\tests\_mock;

use kradwhite\db\driver\PostgreSql;
use PDO;

class MockPostgreSqlDriver extends PostgreSql
{
    /**
     * MockMysqlDriver constructor.
     */
    public function __construct()
    {
        parent::__construct('host', 'dbName', 'user', 'password');
    }

    /**
     * @return MockPDO
     */
    public function getPdo(): PDO
    {
        if (!$this->pdo) {
            $this->pdo = new MockPDO();
        }
        return $this->pdo;
    }
}