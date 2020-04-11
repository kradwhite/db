<?php
/**
 * Date: 08.04.2020
 * Time: 19:28
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\driver;

use kradwhite\db\QueryException;
use kradwhite\db\syntax\Syntax;
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
     * @throws QueryException
     */
    public function begin();

    /**
     * @return void
     * @throws QueryException
     */
    public function commit();

    /**
     * @return void
     * @throws QueryException
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
     * @return Syntax
     */
    public function getSyntax(): Syntax;
}