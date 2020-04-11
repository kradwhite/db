<?php
/**
 * Date: 11.04.2020
 * Time: 15:25
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\tests\_mock;

use kradwhite\db\driver\MySql as Base;
use PDO;

/**
 * Class MockMysqlDriver
 * @package kradwhite\tests\_mock
 */
class MockMysqlDriver extends Base
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