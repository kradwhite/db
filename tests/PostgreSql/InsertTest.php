<?php

namespace kradwhite\tests\PostgreSql;

class InsertTest extends \Codeception\Test\Unit
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
    public function testInsert()
    {
        $before = $this->tester->grabNumRecords('test_insert');
        $attributes = ['test_bool' => true, 'test_integer' => 1000, 'test_string' => 'string', 'test_double' => 302.34];
        $result = $this->tester->conn()->insert('test_insert', $attributes, ['test_bool' => 'bool'])->prepareExecute();
        $this->assertEquals($result, '1');
        $after = $this->tester->grabNumRecords('test_insert');
        $this->assertEquals($before + 1, $after);
    }
}