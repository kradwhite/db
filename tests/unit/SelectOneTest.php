<?php

namespace kradwhite\tests\unit;

use kradwhite\db\data\SelectOne;

class SelectOneTest extends \Codeception\Test\Unit
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
        $condition = ['col1' => 'value11', 'col2' => 22, 'col3' => false];
        $selectOne = new SelectOne('test', ['col1', 'col2', 'col3'], $condition, [], $this->tester->mysqlDriver());
        $selectOne->prepareExecute();
        $this->assertEquals('SELECT `col1`, `col2`, `col3` FROM `test` WHERE `col1`=:col1 AND `col2`=:col2 AND `col3`=:col3 LIMIT 1', $mockPdo->getQuery());
        $this->assertEquals($condition, $mockPdo->getParams());
    }
}