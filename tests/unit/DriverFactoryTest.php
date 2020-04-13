<?php

namespace kradwhite\tests\unit;

use kradwhite\db\driver\DriverFactory;
use kradwhite\db\driver\MySql;
use kradwhite\db\driver\PostgreSql;
use kradwhite\db\exception\BeforeQueryException;

class DriverFactoryTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testBuildFail()
    {
        $this->tester->expectThrowable(new BeforeQueryException("Драйвер 'wrong' не поддерживается"), function () {
            DriverFactory::build('wrong', '', '', '', '');
        });
    }

    public function testBuildMySql()
    {
        $driver = DriverFactory::build('mysql', '', '', '', '', '', []);
        $this->assertInstanceOf(MySql::class, $driver);
    }

    public function testBuildPostgreSql()
    {
        $driver = DriverFactory::build('pgsql', '', '', '', '', '', []);
        $this->assertInstanceOf(PostgreSql::class, $driver);
    }

    public function testBuildFromArray()
    {
        $driver = DriverFactory::buildFromArray([], 'pgsql');
        $this->assertInstanceOf(PostgreSql::class, $driver);
    }
}