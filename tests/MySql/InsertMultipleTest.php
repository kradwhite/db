<?php

namespace kradwhite\tests\MySql;

class InsertMultipleTest extends \Codeception\Test\Unit
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
        $before = $this->tester->grabNumRecords('test_insert_multiple');
        $attributes = [['name 1', 22, true], ['name 2', 1000, false]];
        $fields = ['name', 'value', 'value2'];
        $this->tester->conn()->insertMultiple('test_insert_multiple', $fields, $attributes, ['value2' => 'bool'])->prepareExecute();
        $after = $this->tester->grabNumRecords('test_insert_multiple');
        $this->assertEquals($before + 2, $after);
    }
}