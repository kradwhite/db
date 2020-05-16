<?php
/**
 * Author: Artem Aleksandrov
 * Date: 2020.04.04
 * Time: 11:45:20
 */

namespace kradwhite\db;

use kradwhite\db\exception\BeforeQueryException;
use PDO;

/**
 * Trait FetchStyleTrait
 * @package kradwhite\db
 */
trait FetchStyleTrait
{
    /**
     * @param string $style
     * @return int
     */
    private function getStyleFetch(string $style): int
    {
        if ($style == 'assoc') {
            return PDO::FETCH_ASSOC;
        } else if ($style == 'num') {
            return PDO::FETCH_NUM;
        } else if ($style == 'column') {
            return PDO::FETCH_COLUMN;
        } else {
            throw new BeforeQueryException('Неверное значение style. Style может быть assoc|num|column');
        }
    }
}