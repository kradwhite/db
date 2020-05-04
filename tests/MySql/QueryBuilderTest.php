<?php

namespace kradwhite\tests\MySql;

class QueryBuilderTest extends \Codeception\Test\Unit
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
        $result = $this->tester->conn()->queryBuilder()->select(['tqb.*'])
            ->from(['test_query_builder tqb'])
            ->where('tqb.name', 'name 1')
            ->orWhere('tqb.name', 'name 1')
            ->leftJoin('test_query_builder tqb2', ['tqb.id=tqb2.id'])
            ->limit(1)
            ->prepareExecute();
        $this->assertEquals($result, ['id' => '1', 'name' => 'name 1', 'value' => '22', 'value2' => '0']);
    }
}