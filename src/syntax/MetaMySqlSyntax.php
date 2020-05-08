<?php
/**
 * Date: 08.05.2020
 * Time: 7:53
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax;

/**
 * Class MetaMySqlSyntax
 * @package kradwhite\db\syntax
 */
class MetaMySqlSyntax implements MetaSyntax
{
    /**
     * @return array
     */
    public function databases(): array
    {
        
    }

    public function tables(string $database): array
    {
        // TODO: Implement tables() method.
    }

    public function views(string $database): array
    {
        // TODO: Implement views() method.
    }

    public function columns(string $database, string $table): array
    {
        // TODO: Implement columns() method.
    }

    public function primaryKey(string $database, string $table): array
    {
        // TODO: Implement primaryKey() method.
    }

    public function foreignKeys(string $database, string $table): array
    {
        // TODO: Implement foreignKeys() method.
    }

    public function indexes(string $database, string $table): array
    {
        // TODO: Implement indexes() method.
    }
}