<?php
/**
 * Date: 08.04.2020
 * Time: 19:46
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\driver;

use kradwhite\db\syntax\MetaSyntax;
use kradwhite\db\syntax\pgsql\MetaSyntax as MetaPostgreSqlSyntax;
use kradwhite\db\syntax\pgsql\TableSyntax as TablePostgreSqlSyntax;
use kradwhite\db\syntax\TableSyntax;
use PDO;

/**
 * Class PostgreSql
 * @package kradwhite\db\driver
 */
class PostgreSql extends Sql
{
    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        if (!$this->pdo) {
            if (!$this->port) {
                $this->port = '5432';
            }
            $this->pdo = new PDO("pgsql:host={$this->host};port={$this->port};dbname={$this->dbName}", $this->user, $this->password, $this->options);
        }
        return $this->pdo;
    }

    /**
     * @return TableSyntax
     */
    public function getTableSyntax(): TableSyntax
    {
        if (!$this->tableSyntax) {
            $this->tableSyntax = new TablePostgreSqlSyntax();
        }
        return $this->tableSyntax;
    }

    /**
     * @return MetaSyntax
     */
    public function getMetaSyntax(): MetaSyntax
    {
        if (!$this->metaSyntax) {
            $this->metaSyntax = new MetaPostgreSqlSyntax();
        }
        return $this->metaSyntax;
    }

    /**
     * @param bool $value
     * @return mixed|string
     */
    public function getBoolValue(bool $value): string
    {
        return $value ? 't' : 'f';
    }
}