<?php

namespace kradwhite\db\tests;

use kradwhite\db\driver\Driver;
use kradwhite\db\QueryBuilder;
use PDO;
use PDOStatement;

class QueryBuilderTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $driver;

    protected string $lastQuery = '';

    protected function _before()
    {
        $this->driver = $this->make(Driver::class, [
            'getPdo' => $this->make(PDO::class, [
                'prepare' => function (string $query, array $params) {
                    $this->lastQuery = $query;
                    return $this->make(PDOStatement::class, [
                        'closeCursor' => true, 'execute' => true, 'fetch' => [], 'fetchAll' => [], 'fetchColumn' => []]);
                }])]);
    }

    protected function _after()
    {
    }

    // tests
    public function testWhereBuild1()
    {
        $queryBuilder = new QueryBuilder($this->driver);
        $queryBuilder->where('field1', 'value1');
    }
}