<?php
/**
 * Date: 08.04.2020
 * Time: 19:28
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\driver;

use kradwhite\db\exception\PdoException;
use kradwhite\db\syntax\MetaSyntax;
use kradwhite\db\syntax\TableSyntax;
use PDO;

/**
 * Interface Driver
 * @package kradwhite\db\driver
 */
interface Driver
{
    /**
     * @return PDO
     */
    public function getPdo(): PDO;

    /**
     * @return void
     * @throws PdoException
     */
    public function begin();

    /**
     * @return void
     * @throws PdoException
     */
    public function commit();

    /**
     * @return void
     * @throws PdoException
     */
    public function rollback();

    /**
     * @return bool
     */
    public function inTransaction(): bool;

    /**
     * @param string $object
     * @return string
     */
    public function quote(string $object): string;

    /**
     * @param array $objects
     * @return array
     */
    public function quotes(array &$objects): array;

    /**
     * @return TableSyntax
     */
    public function getTableSyntax(): TableSyntax;

    /**
     * @return MetaSyntax
     */
    public function getMetaSyntax(): MetaSyntax;

    /**
     * @param bool $value
     * @return mixed
     */
    public function getBoolValue(bool $value);
}