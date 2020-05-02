<?php
/**
 * Date: 10.04.2020
 * Time: 18:54
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax;

use kradwhite\db\exception\BeforeQueryException;

/**
 * Class MySqlSyntax
 * @package kradwhite\db\syntax
 */
class MySqlSyntax extends SqlSyntax
{
    /** @var array */
    private const MySqlTypes = [
        'float' => 'FLOAT',
        'double' => 'DOUBLE',
        'text' => 'LONGTEXT',
        'auto' => 'INT',
        'smallauto' => 'SMALLINT',
        'bigauto' => 'BIGINT',
        'datetime' => 'DATETIME',
    ];

    /**
     * @param string $table
     * @param array $columns
     * @param array $indexes
     * @param array $foreignKeys
     * @param array $primaryKeys
     * @param array $options
     * @return string
     */
    public function createTable(string $table, array $columns, array $indexes, array $foreignKeys, array $primaryKeys, array $options): string
    {
        $query = "CREATE TABLE{$this->notExistOption($options)} {$this->quote($table)} (\n{$this->columnsToString($columns)}";
        if ($primaryKeys['columns']) {
            $pkColumns = implode(', ', $this->quotes($primaryKeys['columns']));
            $query .= ",\n\tCONSTRAINT PRIMARY KEY{$this->indexType($primaryKeys['options'])} ($pkColumns)";
        }
        foreach ($foreignKeys as $name => &$fk) {
            $query .= ",\n\t" . $this->foreignKeyToString($name, $fk['columns'], $fk['table'], $fk['columns2'], $fk['options']);
        }
        foreach ($indexes as $name => &$index) {
            $indexColumns = implode(', ', $this->quotes($index['columns']));
            $query .= ",\n\t" . (isset($index['options']['unique']) && $index['options']['unique']
                    ? "CONSTRAINT UNIQUE INDEX {$this->quote($name)}{$this->indexType($index['options'])}"
                    : "INDEX {$this->quote($name)}{$this->indexType($index['options'])}")
                . " ($indexColumns)";
        }
        return $query . ")\n";
    }

    /**
     * @param string $table
     * @param string $newName
     * @return string
     */
    public function renameTable(string $table, string $newName): string
    {
        return "ALTER TABLE {$this->quote($table)} RENAME {$this->quote($newName)}";
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
        return "ALTER TABLE {$this->quote($table)} ALTER COLUMN {$this->quote($name)} {$this->columnType($type, $options)}{$this->columnOptions($options)}";
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
     * @param string $oldName
     * @param string $newName
     * @return string
     */
    public function renameIndex(string $table, string $oldName, string $newName): string
    {
        return "ALTER TABLE {$this->quote($table)} RENAME INDEX {$this->quote($oldName)} TO {$this->quote($newName)}";
    }

    /**
     * @param string $table
     * @param string $name
     * @return string
     */
    public function dropIndex(string $table, string $name): string
    {
        return "DROP INDEX {$this->quote($name)} ON {$this->quote($table)}";
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
     * @throws BeforeQueryException
     */
    public function renameForeignKey(string $table, string $oldName, string $newName): string
    {
        throw new BeforeQueryException("В MySql нельзя переименовывать внешние ключи");
    }

    /**
     * @param string $table
     * @param string $name
     * @return string
     */
    public function dropForeignKey(string $table, string $name): string
    {
        return "ALTER TABLE {$this->quote($table)} DROP FOREIGN KEY {$this->quote($name)}";
    }

    /**
     * @param string $object
     * @return string
     */
    public function quote(string $object): string
    {
        return "`$object`";
    }

    /**
     * @param array $options
     * @return string
     */
    protected function columnOptions(array &$options): string
    {
        return parent::columnOptions($options) . $this->afterOption($options);
    }

    /**
     * @param array $options
     * @return string
     */
    protected function afterOption(array &$options): string
    {
        return isset($options['after']) && $options['after'] ? " AFTER {$this->quote($options['after'])}" : '';
    }

    /**
     * @param string $type
     * @param array $options
     * @return string
     */
    protected function columnType(string $type, array &$options): string
    {
        $result = isset(self::MySqlTypes[$type])
            ? self::MySqlTypes[$type] . $this->limitColumnOption($options)
            : parent::columnType($type, $options);
        if (in_array($type, ['auto', 'smallauto', 'bigauto'])) {
            $result .= ' AUTO INCREMENT';
        }
        return $result;
    }

    /**
     * @param array $options
     * @return string
     */
    private function indexType(array &$options): string
    {
        return ' USING ' . ($options['type'] ?? 'BTREE');
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
        return "CONSTRAINT FOREIGN KEY {$this->quote($name)}"
            . " (" . implode(', ', $this->quotes($columns)) . ") REFERENCES {$this->quote($table2)}"
            . " (" . implode(', ', $this->quotes($columns2)) . ")"
            . $this->foreignKeyOptions($options);
    }
}