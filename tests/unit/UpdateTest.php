<?php

namespace kradwhite\tests\unit;

use kradwhite\db\data\Update;

class UpdateTest extends \Codeception\Test\Unit
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
        $attributes = ['col1' => 'value1', 'col2' => 22, 'col3' => true];
        $update = new Update('test', $attributes, ['id' => 54, 'ext_id' => 435], $this->tester->getDriver());
        $update->prepareExecute();
        $this->assertEquals('UPDATE `test` SET `col1`=:col1, `col2`=:col2, `col3`=:col3 WHERE `id`=:c_id AND `ext_id`=:c_ext_id', $mockPdo->getQuery());
        $this->assertEquals($attributes + ['c_id' => 54, 'c_ext_id' => 435], $mockPdo->getParams());
    }
}