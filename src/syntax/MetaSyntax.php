<?php
/**
 * Date: 08.05.2020
 * Time: 7:46
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax;

/**
 * Interface MetaSyntax
 * @package kradwhite\db\syntax
 */
interface MetaSyntax
{
    /**
     * @return array
     */
    public function databases(): array;

    /**
     * @param string $database
     * @return array
     */
    public function tables(string $database): array;

    /**
     * @param string $database
     * @return array
     */
    public function views(string $database): array;

    /**
     * @param string $database
     * @param string $table
     * @return array
     */
    public function columns(string $database, string $table): array;

    /**
     * @param string $database
     * @param string $table
     * @return array
     */
    public function primaryKey(string $database, string $table): array;

    /**
     * @param string $database
     * @param string $table
     * @return array
     */
    public function foreignKeys(string $database, string $table): array;

    /**
     * @param string $database
     * @param string $table
     * @return array
     */
    public function indexes(string $database, string $table): array;
}