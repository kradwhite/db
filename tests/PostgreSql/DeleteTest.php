<?php

namespace kradwhite\tests\PostgreSql;

class DeleteTest extends \Codeception\Test\Unit
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
        $before = $this->tester->grabNumRecords('test_delete');
        $condition = ['name' => 'name 1', 'value' => true];
        $result = $this->tester->conn()->delete('test_delete', $condition, ['value' => 'bool'])->prepareExecute();
        $this->assertEquals($result, '1');
        $after = $this->tester->grabNumRecords('test_delete');
        $this->assertEquals($before - 1, $after);
    }
}