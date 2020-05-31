<?php
/**
 * Date: 08.05.2020
 * Time: 7:53
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax\mysql;

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\db\syntax\MetaSyntax as MetaSyntaxInterface;

/**
 * Class MetaSyntax
 * @package kradwhite\db\syntax\mysql
 */
class MetaSyntax implements MetaSyntaxInterface
{
    /**
     * @return string
     */
    public function databases(): string
    {
        return 'SELECT `SCHEMA_NAME` FROM `information_schema`.`SCHEMATA`';
    }

    /**
     * @return string
     */
    public function tables(): string
    {
        return 'SELECT `TABLE_NAME` `table` FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA`=:db';
    }

    /**
     * @return string
     */
    public function views(): string
    {
        return 'SELECT `TABLE_NAME` `table` FROM `information_schema`.`VIEWS` WHERE `TABLE_SCHEMA`=:db';
    }

    /**
     * @return string
     */
    public function columns(): string
    {
        return 'SELECT `TABLE_NAME` `table`,
            `COLUMN_NAME` `column`,
            `ORDINAL_POSITION` `position`
            FROM `information_schema`.`COLUMNS`
            WHERE `TABLE_SCHEMA`=:db';
    }

    /**
     * @return string
     */
    public function primaryKeys(): string
    {
        return $this->columns() . " AND `COLUMN_KEY`='PRI'";
    }

    /**
     * @return string
     */
    public function foreignKeys(): string
    {
        return 'SELECT `TABLE_NAME` `table`,
            `ORDINAL_POSITION` `position`,
            `TABLE_SCHEMA` `db`,
            `COLUMN_NAME` `column`,
            `REFERENCED_TABLE_NAME` `table2`,
            `REFERENCED_TABLE_SCHEMA` `db2`,
            `REFERENCED_COLUMN_NAME` `column2`
            FROM `information_schema`.`KEY_COLUMN_USAGE`
            WHERE `REFERENCED_TABLE_SCHEMA` IS NOT NULL AND `TABLE_SCHEMA`=:db';
    }

    /**
     * @param string $database
     * @return array
     */
    public function indexes(string $database): array
    {
        return ['query' => 'SELECT DISTINCT `TABLE_NAME` `table`,
            `INDEX_NAME` `index`,
            `COLUMN_NAME` `column`
            FROM `information_schema`.`STATISTICS`
            WHERE `TABLE_SCHEMA`=:db', 'params' => [':db' => $database]];
    }

    /**
     * @return string
     * @throws BeforeQueryException
     */
    public function sequences(): string
    {
        throw new BeforeQueryException('mysql-sequences-not-exist');
    }
}