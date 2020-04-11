<?php
/**
 * Date: 08.04.2020
 * Time: 21:13
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\data;

use kradwhite\db\driver\Driver;
use kradwhite\db\QueryException;
use kradwhite\db\StmtTrait;
use PDOStatement;

/**
 * Class QueryBuilder
 * @package kradwhite\db
 */
class QueryBuilder
{
    use StmtTrait;

    /** @var array */
    private array $fields = [];

    /** @var array */
    private array $distinct = [];

    /** @var string */
    private string $count = '';

    /** @var string */
    private string $max = '';

    /** @var string */
    private string $min = '';

    /** @var string */
    private string $sum = '';

    /** @var array */
    private array $tables = [];

    /** @var array */
    private array $joins = [];

    /** @var array */
    private array $where = [];

    /** @var array */
    private array $groupBy = [];

    /** @var string */
    private string $having = '';

    /** @var array */
    private array $orderBy = [];

    /** @var int */
    private int $limit = 0;

    /** @var int */
    private int $offset = 0;

    /** @var QueryBuilder|null */
    private ?QueryBuilder $union = null;

    /** @var bool */
    private bool $unionAll = false;

    /** @var array */
    private array $params = [];

    /** @var int */
    private int $i = 0;

    /**
     * QueryBuilder constructor.
     * @param Driver $driver
     * @param bool $unionAll
     * @param QueryBuilder $unionParent
     */
    public function __construct(Driver $driver, bool $unionAll = false, QueryBuilder $unionParent = null)
    {
        $this->driver = $driver;
        $this->unionAll = $unionAll;
        $this->union = $unionParent;
    }

    /**
     * @param array $fields
     * @return QueryBuilder
     */
    public function select(array $fields = ['*']): QueryBuilder
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param array $fields
     * @return QueryBuilder
     */
    public function distinct(array $fields): QueryBuilder
    {
        $this->distinct = $fields;
        return $this;
    }

    /**
     * @param string $field
     * @return QueryBuilder
     */
    public function count(string $field): QueryBuilder
    {
        $this->count = $field;
        return $this;
    }

    /**
     * @param string $field
     * @return QueryBuilder
     */
    public function max(string $field): QueryBuilder
    {
        $this->max = $field;
        return $this;
    }

    /**
     * @param string $field
     * @return QueryBuilder
     */
    public function min(string $field): QueryBuilder
    {
        $this->min = $field;
        return $this;
    }

    /**
     * @param string $field
     * @return QueryBuilder
     */
    public function sum(string $field): QueryBuilder
    {
        $this->sum = $field;
        return $this;
    }

    /**
     * @param string $type
     * @param string $table
     * @param array $condition
     * @return QueryBuilder
     */
    public function join(string $type, string $table, array $condition): QueryBuilder
    {
        $this->joins[] = compact(['table', 'type', 'condition']);
        return $this;
    }

    /**
     * @param string $table
     * @param array $condition
     * @return QueryBuilder
     */
    public function innerJoin(string $table, array $condition): QueryBuilder
    {
        return $this->join('INNER', $table, $condition);
    }

    /**
     * @param string $table
     * @param array $condition
     * @return QueryBuilder
     */
    public function leftJoin(string $table, array $condition): QueryBuilder
    {
        return $this->join('LEFT', $table, $condition);
    }

    /**
     * @param string $table
     * @param array $condition
     * @return QueryBuilder
     */
    public function rightJoin(string $table, array $condition): QueryBuilder
    {
        return $this->join('RIGHT', $table, $condition);
    }

    /**
     * @param string $table
     * @param array $condition
     * @return QueryBuilder
     */
    public function outerJoin(string $table, array $condition): QueryBuilder
    {
        return $this->join('FULL OUTER', $table, $condition);
    }

    /**
     * @param string $table
     * @param array $condition
     * @return QueryBuilder
     */
    public function crossJoin(string $table, array $condition): QueryBuilder
    {
        return $this->join('CROSS', $table, $condition);
    }

    /**
     * @param array $tables
     * @return QueryBuilder
     */
    public function from(array $tables): QueryBuilder
    {
        $this->tables = $tables;
        return $this;
    }

    /**
     * @param string $field
     * @param string|int|float|bool $value
     * @param string $op
     * @return QueryBuilder
     */
    public function andWhere(string $field, $value, string $op = '='): QueryBuilder
    {
        $this->where[] = ['AND', [$field, $op, $this->nextKey($value)]];
        return $this;
    }

    /**
     * @param string $field
     * @param string|int|float|bool $value
     * @param string $op
     * @return QueryBuilder
     */
    public function orWhere(string $field, $value, string $op = '='): QueryBuilder
    {
        $this->where[] = ['OR', [$field, $op, $this->nextKey($value)]];
        return $this;
    }

    /**
     * @param string $field
     * @param $value
     * @param string $op
     * @param string $union
     * @return QueryBuilder
     */
    public function where(string $field, $value, string $op = '=', string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, $op, $this->nextKey($value)]];
        return $this;
    }

    /**
     * @param string $field
     * @param array $values
     * @param string $union
     * @return QueryBuilder
     */
    public function whereIn(string $field, array $values, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'IN', '(' . implode(', ', $this->nextKeys($values)) . ')']];
        return $this;
    }

    /**
     * @param string $field
     * @param array $values
     * @param string $union
     * @return QueryBuilder
     */
    public function whereNotIn(string $field, array $values, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'NOT IN', '(' . implode(', ', $this->nextKeys($values)) . ')']];
        return $this;
    }

    /**
     * @param string $field
     * @param array $values
     * @param string $union
     * @return QueryBuilder
     */
    public function whereBetween(string $field, array $values, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'BETWEEN', $this->nextKey($values[0]), 'AND', $this->nextKey($values[1])]];
        return $this;
    }

    public function whereNotBetween(string $field, array $values, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'NOT BETWEEN', $this->nextKey($values[0]), 'AND', $this->nextKey($values[1])]];
        return $this;
    }

    /**
     * @param string $field
     * @param string $union
     * @return QueryBuilder
     */
    public function whereIsNull(string $field, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'IS NULL']];
        return $this;
    }

    /**
     * @param string $field
     * @param string $union
     * @return QueryBuilder
     */
    public function whereIsNotNull(string $field, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'IS NOT NULL']];
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $union
     * @return QueryBuilder
     */
    public function whereLike(string $field, string $value, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'LIKE', $this->nextKey($value)]];
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @param string $union
     * @return QueryBuilder
     */
    public function whereNotLike(string $field, string $value, string $union = 'AND'): QueryBuilder
    {
        $this->where[] = [strtoupper($union), [$field, 'NOT LIKE', $this->nextKey($value)]];
        return $this;
    }

    /**
     * @param string $condition
     * @param array $params
     * @param string $union
     * @return QueryBuilder
     */
    public function whereString(string $condition, array $params = [], string $union = 'AND'): QueryBuilder
    {
        $this->params += $params;
        $this->where[] = [strtoupper($union), [$condition]];
        return $this;
    }

    /**
     * @param array $fields
     * @return QueryBuilder
     */
    public function groupBy(array $fields): QueryBuilder
    {
        $this->groupBy = $fields;
        return $this;
    }

    /**
     * @param string $having
     * @return QueryBuilder
     */
    public function having(string $having): QueryBuilder
    {
        $this->having = $having;
        return $this;
    }

    /**
     * @param array $fields
     * @param string $order
     * @return QueryBuilder
     */
    public function orderBy(array $fields, string $order = 'ASC'): QueryBuilder
    {
        $this->orderBy = [$order, $fields];
        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return QueryBuilder
     */
    public function limit(int $limit = 1, int $offset = 0): QueryBuilder
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param int $offset
     * @return QueryBuilder
     */
    public function offset(int $offset): QueryBuilder
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return QueryBuilder
     */
    public function union(): QueryBuilder
    {
        return new QueryBuilder($this->driver, false, $this);
    }

    /**
     * @return QueryBuilder
     */
    public function unionAll(): QueryBuilder
    {
        return new QueryBuilder($this->driver, true, $this);
    }

    /**
     * @param string $fetch
     * @param string $style
     * @param int $column
     * @return array
     * @throws QueryException
     */
    public function prepareExecute(string $fetch = 'fetch', string $style = 'assoc', int $column = 0): array
    {
        $stmt = $this->_prepareExecute($this->buildQuery(), $this->params);
        if ($fetch == 'fetch') {
            $result = $stmt->fetch($style);
        } else if ($fetch == 'all') {
            $result = $stmt->fetchAll($style);
        } else if ($fetch == 'column') {
            $result = $stmt->fetchColumn($column);
        } else {
            throw new QueryException("Допустимые значения 1 аргумента: fetch | all | column");
        }
        $this->closeCursor($stmt);
        return $result;
    }

    /**
     * @return string
     */
    private function buildQuery(): string
    {
        $query = '';
        if ($this->union) {
            $query = $this->union->buildQuery() . ' UNION ' . $this->unionAll ? 'ALL ' : '';
        }
        $query .= "SELECT ";
        $select = [];
        if ($this->fields) {
            $select[] = implode(', ', $this->fields);
        }
        if ($this->distinct) {
            $select[] = "DISTINCT(" . implode(', ', $this->distinct) . ")";
        }
        if ($this->count) {
            $select[] = "COUNT({$this->count})";
        }
        if ($this->max) {
            $select[] = "MAX({$this->max})";
        }
        if ($this->min) {
            $select[] = "MIN({$this->min})";
        }
        if ($this->sum) {
            $select[] = "SUM({$this->sum})";
        }
        $query .= implode(', ', $select) . " FROM " . implode(', ', $this->tables);
        foreach ($this->joins as &$join) {
            $query .= " {$join['type']} JOIN {$join['table']} ON " . implode(' AND ', $join['condition']);
        }
        if ($this->where) {
            $query .= " WHERE " . implode(' ', $this->where[0][1]);
            for ($i = 1; $i < count($this->where); $i++) {
                $query .= " {$this->where[$i][0]} " . implode(' ', $this->where[$i][1]);
            }
        }
        if ($this->groupBy) {
            $query .= " GROUP BY (" . implode(', ', $this->groupBy) . ")";
        }
        if ($this->having) {
            $query .= " HAVING ({$this->having})";
        }
        if ($this->orderBy) {
            $query .= " ORDER BY (" . implode(', ', $this->orderBy[1]) . ") {$this->orderBy[0]}";
        }
        if ($this->limit) {

            $query .= " LIMIT {$this->nextKey($this->limit)}";
        }
        if ($this->offset) {
            $query .= " OFFSET {$this->nextKey($this->offset)}";
        }
        return $query;
    }

    /**
     * @param string|int|float|bool
     * @return string
     */
    private function nextKey($value): string
    {
        $key = "c_" . $this->i++;
        $this->params[$key] = $value;
        return ":$key";
    }

    /**
     * @param array $values
     * @return array
     */
    private function nextKeys(array $values): array
    {
        $keys = [];
        foreach ($values as &$value) {
            $keys[] = $this->nextKey($value);
        }
        return $keys;
    }
}