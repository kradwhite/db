<?php
/**
 * Date: 10.04.2020
 * Time: 18:59
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax\pgsql;

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\db\syntax\TableSqlSyntax;

/**
 * Class TableSyntax
 * @package kradwhite\db\syntax\pgsql
 */
class TableSyntax extends TableSqlSyntax
{
    /** @var array */
    private const PostgreSqlTypes = [
        'float' => 'REAL',
        'double' => 'DOUBLE PRECISION',
        'text' => 'TEXT',
        'auto' => 'SERIAL',
        'smallauto' => 'SMALLSERIAL',
        'bigauto' => 'BIGSERIAL',
        'json' => 'JSON',
        'jsonb' => 'JSONB'
    ];

    /**
     * @param string $table
     * @param array $columns
     * @param array $indexes
     * @param array $foreignKeys
     * @param array $primaryKeys
     * @param array $options
     * @return string
     * @throws BeforeQueryException
     */
    public function createTable(string $table, array $columns, array $indexes, array $foreignKeys, array $primaryKeys, array $options): string
    {
        if (!$columns) {
            throw new BeforeQueryException('table-column-min-1');
        }
        $query = "CREATE TABLE{$this->notExistOption($options)} {$this->quote($table)} (\n{$this->columnsToString($columns)}";
        if ($primaryKeys['columns']) {
            $query .= ",\n\tPRIMARY KEY (" . implode(', ', $this->quotes($primaryKeys['columns'])) . ')';
        }
        foreach ($foreignKeys as $name => &$fk) {
            $query .= ",\n\t" . $this->foreignKeyToString($name, $fk['columns'], $fk['table'], $fk['columns2'], $fk['options']);
        }
        $query .= ");\n";
        foreach ($indexes as $name => &$index) {
            $query .= $this->createIndex($table, $index['columns'], $index['options'], $name) . ";\n";
        }
        return $query;
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
        $prefix = "ALTER TABLE {$this->quote($table)} ALTER COLUMN {$this->quote($name)}";
        return "$prefix TYPE {$this->columnType($type, $options)};\n"
            . "$prefix " . (isset($options['null']) && $options['null'] ? 'DROP' : 'SET') . " NOT NULL;\n"
            . "$prefix " . (isset($options['default']) ? "SET DEFAULT '{$options['default']}'" : 'DROP DEFAULT') . ";\n";
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
     * @param string $newName
     * @return string
     */
    public function renameTable(string $table, string $newName): string
    {
        return "ALTER TABLE {$this->quote($table)} RENAME TO {$this->quote($newName)}";
    }

    /**
     * @param string $table
     * @param string $oldName
     * @param string $newName
     * @return string
     */
    public function renameIndex(string $table, string $oldName, string $newName): string
    {
        return "ALTER INDEX {$this->quote($oldName)} RENAME TO {$this->quote($newName)}";
    }

    /**
     * @param string $table
     * @param string $name
     * @return string
     */
    public function dropIndex(string $table, string $name): string
    {
        return "DROP INDEX {$this->quote($name)}";
    }

    /**
     * @param string $name
     * @param string $table
     * @param array $columns
     * @param string $table2
     * @param array $columns2
     * @param array $options
     * @return string
     */
    public function createForeignKey(string $name, string $table, array $columns, string $table2, array $columns2, array $options = []): string
    {
        return "ALTER TABLE {$this->quote($table)} ADD {$this->foreignKeyToString($name, $columns, $table2, $columns2, $options)}";
    }

    /**
     * @param string $table
     * @param string $oldName
     * @param string $newName
     * @return string
     */
    public function renameForeignKey(string $table, string $oldName, string $newName): string
    {
        return "ALTER TABLE {$this->quote($table)} RENAME CONSTRAINT {$this->quote($oldName)} TO {$this->quote($newName)}";
    }

    /**
     * @param string $table
     * @param string $name
     * @return string
     */
    public function dropForeignKey(string $table, string $name): string
    {
        return "ALTER TABLE {$this->quote($table)} DROP CONSTRAINT {$this->quote($name)}";
    }

    /**
     * @param string $object
     * @return string
     */
    public function quote(string $object): string
    {
        return "\"$object\"";
    }

    /**
     * @param string $type
     * @param array $options
     * @return string
     */
    protected function columnType(string $type, array &$options): string
    {
        return self::PostgreSqlTypes[$type] ?? parent::columnType($type, $options);
    }

    /**
     * @param string $name
     * @param array $columns
     * @param string $table2
     * @param array $columns2
     * @param array $options
     * @return string
     */
    private function foreignKeyToString(string $name, array $columns, string $table2, array $columns2, array $options): string
    {
        return "CONSTRAINT {$this->quote($name)} FOREIGN KEY"
            . ' (' . implode(', ', $this->quotes($columns)) . ") REFERENCES {$this->quote($table2)}"
            . ' (' . implode(', ', $this->quotes($columns2)) . ')'
            . $this->foreignKeyOptions($options);
    }
}