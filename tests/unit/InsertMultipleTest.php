<?php

namespace kradwhite\tests\unit;

use kradwhite\db\data\InsertMultiple;

class InsertMultipleTest extends \Codeception\Test\Unit
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
        $attributes = [['value11', 22, true], ['value22', 33, false]];
        $insertMultiple = new InsertMultiple('test', $attributes, ['col1', 'col2', 'col3'], $this->tester->getDriver());
        $insertMultiple->prepareExecute();
        $this->assertEquals("INSERT INTO `test` (`col1`, `col2`, `col3`) VALUES (:p_0_0, :p_0_1, :p_0_2)\n,(:p_1_0, :p_1_1, :p_1_2)", $mockPdo->getQuery());
        $this->assertEquals(['p_0_0' => 'value11', 'p_0_1' => 22, 'p_0_2' => true, 'p_1_0' => 'value22', 'p_1_1' => 33, 'p_1_2' => false],
            $mockPdo->getParams(), print_r($mockPdo->getParams(), true));
    }
}