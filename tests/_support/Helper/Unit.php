<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use kradwhite\tests\_mock\MockMysqlDriver;
use kradwhite\tests\_mock\MockPostgreSqlDriver;

class Unit extends \Codeception\Module
{
    /** @var MockMysqlDriver|null */
    private ?MockMysqlDriver $mysqlDriver = null;

    /** @var MockPostgreSqlDriver|null */
    private ?MockPostgreSqlDriver $postgresqlDriver = null;

    /**
     * @return MockMysqlDriver
     */
    public function mysqlDriver(): MockMysqlDriver
    {
        if (!$this->mysqlDriver) {
            $this->mysqlDriver = new MockMysqlDriver();
        }
        return $this->mysqlDriver;
    }

    /**
     * @return MockPostgreSqlDriver
     */
    public function pgsqlDriver(): MockPostgreSqlDriver
    {
        if (!$this->postgresqlDriver) {
            $this->postgresqlDriver = new MockPostgreSqlDriver();
        }
        return $this->postgresqlDriver;
    }
}
