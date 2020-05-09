<?php
/**
 * Date: 08.04.2020
 * Time: 19:31
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\driver;

use kradwhite\db\syntax\MetaSyntax;
use kradwhite\db\syntax\mysql\MetaSyntax as MetaMySqlSyntax;
use kradwhite\db\syntax\mysql\TableSyntax as TableMySqlSyntax;
use kradwhite\db\syntax\TableSyntax;
use PDO;

/**
 * Class MySql
 * @package kradwhite\db\driver
 */
class MySql extends Sql
{
    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        if (!$this->pdo) {
            if (!$this->port) {
                $this->port = '3306';
            }
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbName};port={$this->port}", $this->user, $this->password, $this->options);
        }
        return $this->pdo;
    }

    /**
     * @return TableSyntax
     */
    public function getTableSyntax(): TableSyntax
    {
        if (!$this->tableSyntax) {
            $this->tableSyntax = new TableMySqlSyntax();
        }
        return $this->tableSyntax;
    }

    /**
     * @return MetaSyntax
     */
    public function getMetaSyntax(): MetaSyntax
    {
        if (!$this->metaSyntax) {
            $this->metaSyntax = new MetaMySqlSyntax();
        }
        return $this->metaSyntax;
    }

    /**
     * @param bool $value
     * @return string
     */
    public function getBoolValue(bool $value): string
    {
        return $value ? '1' : '0';
    }
}