<?php
/**
 * Date: 10.04.2020
 * Time: 18:54
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax;

/**
 * Class SqlSyntax
 * @package kradwhite\db\syntax
 */
abstract class SqlSyntax implements Syntax
{
    /**
     * @param string $table
     * @return string
     */
    public function dropTable(string $table): string
    {
        return "DROP TABLE {$this->quote($table)}";
    }

    /**
     * @param string $table
     * @param string $name
     * @param string $type
     * @param array $options
     * @return string
     */
    public function createColumn(string $table, string $name, string $type, array $options = []): string
    {
        return "ALTER TABLE {$this->quote($table)} ADD COLUMN {$this->quote($name)} $type{$this->columnOptions($options)}";
    }

    /**
     * @param string $table
     * @param string $name
     * @param string $type
     * @param array $options
     * @return string
     */
    public function alterColumn(string $table, string $name, string $type, array $options = []): string
    {
        return "ALTER TABLE {$this->quote($table)} ALTER COLUMN {$this->quote($name)} $type{$this->columnOptions($options)}";
    }

    /**
     * @param string $table
     * @param string $oldName
     * @param string $newName
     * @return string
     */
    public function renameColumn(string $table, string $oldName, string $newName): string
    {
        return "ALTER TABLE {$this->quote($table)} RENAME COLUMN {$this->quote($oldName)} TO {$this->quote($newName)}";
    }

    /**
     * @param string $table
     * @param string $name
     * @param array $options
     * @return string
     */
    public function dropColumn(string $table, string $name, array $options = []): string
    {
        return "ALTER TABLE {$this->quote($table)} DROP COLUMN {$this->quote($name)}{$this->existOption($options)}";
    }

    /**
     * @param string $table
     * @param array $columns
     * @param array $options
     * @param string $name
     * @return string
     */
    public function createIndex(string $table, array $columns, array $options = [], string $name = ''): string
    {
        $query = "CREATE{$this->uniqueOption($options)} INDEX{$this->notExistOption($options)}";
        $name = $this->buildIndexName($name, $table, $columns);
        $query .= " {$this->quote($name)} ON {$this->quote($table)} (" . implode(', ', $this->quotes($columns)) . ')';
        return $query;
    }

    /**
     * @param array $objects
     * @return array
     */
    public function quotes(array &$objects): array
    {
        $result = [];
        foreach ($objects as &$object) {
            $result[] = $this->quote($object);
        }
        return $result;
    }

    /**
     * @param string $name
     * @param string $table
     * @param array $columns
     * @return string
     */
    public function buildIndexName(string $name, string $table, array $columns): string
    {
        return $name ? $name : $table . '_' . implode('_', $columns) . '_idx';
    }

    /**
     * @param string $name
     * @param string $table
     * @param array $columns
     * @param string $table2
     * @param array $columns2
     * @return string
     */
    public function buildForeignKeyName(string $name, string $table, array $columns, string $table2, array $columns2): string
    {
        return $name ? $name : 'fk_' . $table . '_' . implode('_', $columns) . '_' . $table2 . '_' . implode('_', $columns2);
    }

    /**
     * @param array $options
     * @return string
     */
    protected function columnOptions(array &$options): string
    {
        return $this->defaultOption($options) . $this->nullOption($options);
    }

    /**
     * @param array $options
     * @return string
     */
    protected function defaultOption(array &$options): string
    {
        return isset($options['default']) ? " DEFAULT {$options['default']}" : '';
    }

    /**
     * @param array $options
     * @return string
     */
    protected function nullOption(array &$options): string
    {
        return array_key_exists('null', $options) && $options['null'] ? ' NULL' : ' NOT NULL';
    }

    /**
     * @param array $options
     * @return string
     */
    protected function existOption(array &$options): string
    {
        return isset($options['exist']) && $options['exist'] ? ' IF EXIST' : '';
    }

    /**
     * @param array $options
     * @return string
     */
    protected function notExistOption(array &$options): string
    {
        return isset($options['not_exist']) && $options['not_exist'] ? ' IF NOT EXIST' : '';
    }

    /**
     * @param array $options
     * @return string
     */
    protected function uniqueOption(array &$options): string
    {
        return isset($options['unique']) && $options['unique'] ? ' UNIQUE' : '';
    }

    /**
     * @param array $options
     * @return string
     */
    protected function foreignKeyOptions(array $options): string
    {
        $query = '';
        if (isset($options['delete'])) {
            $query .= " ON DELETE {$options['delete']}";
        }
        if (isset($options['update'])) {
            $query .= " ON UPDATE {$options['update']}";
        }
        return $query;
    }

    /**
     * @param array $columns
     * @return string
     */
    protected function columnsToString(array $columns): string
    {
        $strColumns = [];
        foreach ($columns as $name => &$column) {
            $strColumn = "\t{$this->quote($name)} {$column['type']}";
            if (isset($column['options'])) {
                $strColumn = $this->columnOptions($column['options']);
            }
            $strColumns[] = $strColumn;
        }
        return implode(",\n", $strColumns);
    }
}