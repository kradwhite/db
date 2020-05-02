<?php

namespace kradwhite\tests\unit;

use kradwhite\db\data\Insert;

class InsertTest extends \Codeception\Test\Unit
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
    public function testPrepareExecute()
    {
        $attributes = ['col1' => 'value1', 'col2' => 33, 'col3' => true];
        $insert = new Insert('test', $attributes, [], [], $this->tester->mysqlDriver());
        $insert->prepareExecute();
        $this->assertEquals($this->tester->mysqlDriver()->getPdo()->getQuery(), 'INSERT INTO `test` (`col1`, `col2`, `col3`) VALUES (:col1, :col2, :col3)');
        $this->assertEquals($this->tester->mysqlDriver()->getPdo()->getParams(), $attributes, print_r($this->tester->mysqlDriver()->getPdo()->getParams(), true));
    }
}