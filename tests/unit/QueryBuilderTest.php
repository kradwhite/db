<?php

namespace kradwhite\db\tests;

use kradwhite\db\data\QueryBuilder;

class QueryBuilderTest extends \Codeception\Test\Unit
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
        $queryBuilder = new QueryBuilder($this->tester->mysqlDriver());
        $queryBuilder->select(['t.col1', 't.col2'])
            ->distinct(['t.col3'])
            ->count('t.col4')
            ->max('t.col5')
            ->min('t.col6')
            ->sum('t.col7')
            ->from(['test t'])
            ->join('INNER', 'test_inner_join tij', ['tij.test_id=t.id'])
            ->innerJoin('test_inner_join2 tij2', ['tij2.test_id=t.id', 'tij.it=tij2.id'])
            ->leftJoin('test_left_join tlj', ['tlj.test_id=t.id'])
            ->rightJoin('test_right_join trj', ['trj.test_id=t.id'])
            ->outerJoin('test_outer_join toj', ['toj.test_id=t.id'])
            ->crossJoin('test_cross_join tcj', ['tcj.test_id=t.id'])
            ->andWhere('t.col8', 'value1')
            ->orWhere('t.col9', 33, '!=')
            ->where('t.col10', false, '=')
            ->whereIn('t.col11', [1, 2, 3])
            ->whereNotIn('t.col12', [4, 5, 6])
            ->whereBetween('t.col13', ['value2', 'value3'])
            ->whereNotBetween('t.col14', ['value4', 'value5'])
            ->whereIsNull('t.col15')
            ->whereIsNotNull('t.col16')
            ->whereLike('t.col17', '%like')
            ->whereNotLike('t.col18', '%not like')
            ->whereString('t.col19!=:col19', ['col18' => 434])
            ->groupBy(['t.col20', 't.col21'])
            ->having('t.col22>1')
            ->orderBy(['t.col23', 't.col24'])
            ->limit(11)
            ->offset(22)
            ->prepareExecute();
        $this->assertEquals('SELECT t.col1, t.col2, DISTINCT(t.col3), COUNT(t.col4), MAX(t.col5), MIN(t.col6), SUM(t.col7)'
            . ' FROM test t'
            . ' INNER JOIN test_inner_join tij ON tij.test_id=t.id'
            . ' INNER JOIN test_inner_join2 tij2 ON tij2.test_id=t.id AND tij.it=tij2.id'
            . ' LEFT JOIN test_left_join tlj ON tlj.test_id=t.id'
            . ' RIGHT JOIN test_right_join trj ON trj.test_id=t.id'
            . ' FULL OUTER JOIN test_outer_join toj ON toj.test_id=t.id'
            . ' CROSS JOIN test_cross_join tcj ON tcj.test_id=t.id'
            . ' WHERE t.col8 = :c_0 OR t.col9 != :c_1 AND t.col10 = :c_2 AND t.col11 IN (:c_3, :c_4, :c_5) AND t.col12 NOT IN (:c_6, :c_7, :c_8)'
            . ' AND t.col13 BETWEEN :c_9 AND :c_10 AND t.col14 NOT BETWEEN :c_11 AND :c_12 AND t.col15 IS NULL AND t.col16 IS NOT NULL'
            . ' AND t.col17 LIKE :c_13 AND t.col18 NOT LIKE :c_14 AND t.col19!=:col19'
            . ' GROUP BY (t.col20, t.col21)'
            . ' HAVING (t.col22>1)'
            . ' ORDER BY (t.col23, t.col24) ASC'
            . ' LIMIT 11 OFFSET 22', $mockPdo->getQuery());
        $this->assertEquals([
            'c_0' => 'value1',
            'c_1' => 33,
            'c_2' => false,
            'c_3' => 1,
            'c_4' => 2,
            'c_5' => 3,
            'c_6' => 4,
            'c_7' => 5,
            'c_8' => 6,
            'c_9' => 'value2',
            'c_10' => 'value3',
            'c_11' => 'value4',
            'c_12' => 'value5',
            'c_13' => '%like',
            'c_14' => '%not like',
            'col18' => 434], $mockPdo->getParams());
    }
}