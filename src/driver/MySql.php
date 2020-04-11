<?php
/**
 * Date: 08.04.2020
 * Time: 19:31
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\driver;

use kradwhite\db\syntax\MySqlSyntax;
use kradwhite\db\syntax\Syntax;
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
            $dbName = $this->quote($this->dbName);
            if (!$this->port) {
                $this->port = '3306';
            }
            $this->pdo = new PDO("mysql:host={$this->host};port={$this->port};dbname={$dbName}", $this->user, $this->password, $this->options);
        }
        return $this->pdo;
    }

    /**
     * @return Syntax
     */
    public function getSyntax(): Syntax
    {
        if (!$this->syntax) {
            $this->syntax = new MySqlSyntax();
        }
        return $this->syntax;
    }
}