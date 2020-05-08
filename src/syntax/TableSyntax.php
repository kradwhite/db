<?php
/**
 * Date: 10.04.2020
 * Time: 18:35
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax;

/**
 * Interface TableSyntax
 * @package kradwhite\db\syntax
 */
interface TableSyntax
{
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
     * @param string $value
     * @return string
     */
    public function stringQuote(string $value): string;

    /**
     * @param string $table
     * @param array $columns
     * @param array $indexes
     * @param array $foreignKeys
     * @param array $primaryKeys
     * @param array $options
     * @return string
     */
    public function createTable(string $table, array $columns, array $indexes, array $foreignKeys, array $primaryKeys, array $options): string;

    /**
     * @param string $table
     * @param string $newName
     * @return string
     */
    public function renameTable(string $table, string $newName): string;

    /**
     * @param string $table
     * @return string
     */
    public function dropTable(string $table): string;

    /**
     * @param string $table
     * @param string $name
     * @param string $type
     * @param array $options
     * @return string
     */
    public function createColumn(string $table, string $name, string $type, array $options = []): string;

    /**
     * @param string $table
     * @param string $name
     * @param string $type
     * @param array $options
     * @return string
     */
    public function alterColumn(string $table, string $name, string $type, array $options = []): string;

    /**
     * @param string $table
     * @param string $oldName
     * @param string $newName
     * @return string
     */
    public function renameColumn(string $table, string $oldName, string $newName): string;

    /**
     * @param string $table
     * @param string $name
     * @param array $options
     * @return string
     */
    public function dropColumn(string $table, string $name, array $options = []): string;

    /**
     * @param string $table
     * @param array $columns
     * @param array $options
     * @param string $name
     * @return string
     */
    public function createIndex(string $table, array $columns, array $options = [], string $name = ''): string;

    /**
     * @param string $table
     * @param string $oldName
     * @param string $newName
     * @return string
     */
    public function renameIndex(string $table, string $oldName, string $newName): string;

    /**
     * @param string $table
     * @param string $name
     * @return string
     */
    public function dropIndex(string $table, string $name): string;

    /**
     * @param string $name
     * @param string $table
     * @param array $columns
     * @param string $table2
     * @param array $columns2
     * @param array $options
     * @return string
     */
    public function createForeignKey(string $name, string $table, array $columns, string $table2, array $columns2, array $options = []): string;

    /**
     * @param string $table
     * @param string $oldName
     * @param string $newName
     * @return string
     */
    public function renameForeignKey(string $table, string $oldName, string $newName): string;

    /**
     * @param string $table
     * @param string $name
     * @return string
     */
    public function dropForeignKey(string $table, string $name): string;

    /**
     * @param string $name
     * @param string $table
     * @param array $columns
     * @param array $options
     * @return string
     */
    public function buildIndexName(string $name, string $table, array $columns, array $options): string;

    /**
     * @param string $name
     * @param string $table
     * @param string $table2
     * @return string
     */
    public function buildForeignKeyName(string $name, string $table, string $table2): string;
}