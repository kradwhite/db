<?php

namespace kradwhite\tests\PostgreSql;

use kradwhite\db\Connection;
use kradwhite\db\driver\PostgreSql;

class TableTest extends \Codeception\Test\Unit
{
    /**
     * @var \PostgreSqlTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateEmpty()
    {
        $connection = new Connection(new PostgreSql('localhost', 'test', 'admin', 'admin', '5434'));
        $connection->table('test_create')->create();
    }
}