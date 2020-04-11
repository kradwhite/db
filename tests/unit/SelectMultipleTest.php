<?php

namespace kradwhite\tests\unit;

use kradwhite\db\data\SelectMultiple;

class SelectMultipleTest extends \Codeception\Test\Unit
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
        $mockPdo = $this->tester->getDriver()->getPdo();
        $condition = ['col1' => 'value11', 'col2' => 22, 'col3' => false];
        $selectOne = new SelectMultiple('test', ['col1', 'col2', 'col3'], $condition, $this->tester->getDriver());
        $selectOne->prepareExecute('assoc', ['col1', 'col2', 'ASC'], 345);
        $this->assertEquals('SELECT `col1`, `col2`, `col3` FROM `test` WHERE `col1`=:col1 AND `col2`=:col2 AND `col3`=:col3 ORDER BY (`col1`, `col2`) ASC LIMIT 345', $mockPdo->getQuery());
        $this->assertEquals($condition, $mockPdo->getParams());
    }
}