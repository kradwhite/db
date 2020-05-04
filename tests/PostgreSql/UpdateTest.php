<?php

namespace kradwhite\tests\PostgreSql;

class UpdateTest extends \Codeception\Test\Unit
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
        $before = $this->tester->grabNumRecords('test_update');
        $attributes = ['name' => 'new name', 'value' => 44, 'value2' => false];
        $condition = ['name' => 'name 1', 'value' => 22, 'value2' => true];
        $result = $this->tester->conn()->update('test_update', $attributes, $condition, ['value2' => 'bool'])->prepareExecute();
        $this->assertEquals($result, '1');
        $after = $this->tester->grabNumRecords('test_update');
        $this->assertEquals($before, $after);
        $this->assertNotEmpty($this->tester->grabNumRecords('test_update', $attributes));
    }
}