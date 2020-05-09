<?php
/**
 * Author: Artem Aleksandrov
 * Date: 09.05.2020
 * Time: 14:40
 */

declare(strict_types=1);

namespace kradwhite\db\syntax\pgsql;

use kradwhite\db\syntax\MetaSyntax as MetaSyntaxInterface;

/**
 * Class MetaSyntax
 * @package kradwhite\db\syntax\pgsql
 */
class MetaSyntax implements MetaSyntaxInterface
{
    public function databases(): string
    {
        // TODO: Implement databases() method.
    }

    public function tables(): string
    {
        // TODO: Implement tables() method.
    }

    public function views(): string
    {
        // TODO: Implement views() method.
    }

    public function columns(): string
    {
        // TODO: Implement columns() method.
    }

    public function primaryKey(): string
    {
        // TODO: Implement primaryKey() method.
    }

    public function foreignKeys(): string
    {
        // TODO: Implement foreignKeys() method.
    }

    public function indexes(string $database, string $table): string
    {
        // TODO: Implement indexes() method.
    }
}