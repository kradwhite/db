<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use kradwhite\tests\_mock\MockMysqlDriver;

class Unit extends \Codeception\Module
{
    /** @var MockMysqlDriver|null */
    private ?MockMysqlDriver $driver = null;

    /**
     * @return MockMysqlDriver
     */
    public function getDriver(): MockMysqlDriver
    {
        if (!$this->driver) {
            $this->driver = new MockMysqlDriver();
        }
        return $this->driver;
    }
}
