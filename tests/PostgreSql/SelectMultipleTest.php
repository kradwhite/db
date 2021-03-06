<?php

namespace kradwhite\tests\PostgreSql;

class SelectMultipleTest extends \Codeception\Test\Unit
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
    public function testPrepareExecute()
    {
        $condition = ['value2' => true];
        $result = $this->tester->conn()->selectMultiple('test_select_multiple', [], $condition, ['value2' => 'bool'])->prepareExecute();
        $this->assertEquals($result, [
            ['id' => 2, 'name' => 'name 2', 'value' => 33, 'value2' => true],
            ['id' => 3, 'name' => 'name 3', 'value' => 44, 'value2' => true]
        ]);
    }
}