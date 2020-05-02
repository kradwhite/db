<?php

namespace kradwhite\tests\unit;

use kradwhite\db\data\Delete;

class DeleteTest extends \Codeception\Test\Unit
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
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $condition = ['col1' => 33, 'col2' => 'value33'];
        $delete = new Delete('test', [], $condition, [], $this->tester->mysqlDriver());
        $delete->prepareExecute();
        $this->assertEquals('DELETE FROM `test` WHERE `col1`=:col1 AND `col2`=:col2', $mockPdo->getQuery());
        $this->assertEquals($condition, $mockPdo->getParams());
    }
}