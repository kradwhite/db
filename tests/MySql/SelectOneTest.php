<?php

namespace kradwhite\tests\MySql;

class SelectOneTest extends \Codeception\Test\Unit
{
    /**
     * @var \MySqlTester
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
        $condition = ['name' => 'name 1', 'value2' => false];
        $result = $this->tester->conn()->selectOne('test_select_one', [], $condition, ['value2' => 'bool'])->prepareExecute();
        $this->assertEquals($result, ['id' => '1', 'name' => 'name 1', 'value' => '22', 'value2' => '0']);
    }
}