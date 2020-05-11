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
    /**
     * @return string
     */
    public function databases(): string
    {
        return 'SELECT "datname" FROM "pg_catalog"."pg_database"';
    }

    /**
     * @return string
     */
    public function tables(): string
    {
        return 'SELECT "table_name" 
            FROM "information_schema"."tables" 
            WHERE "table_type"=\'BASE TABLE\' 
                AND "table_catalog"=:db';
    }

    /**
     * @return string
     */
    public function views(): string
    {
        return 'SELECT "table_name" FROM "information_schema"."views" WHERE "table_catalog"=:db';
    }

    /**
     * @return string
     */
    public function columns(): string
    {
        return 'SELECT "table_name" "table",
            "column_name" "column",
            "ordinal_position" "position"
            FROM "information_schema"."columns"
            WHERE "table_catalog"=:db';
    }

    /**
     * @return string
     */
    public function primaryKeys(): string
    {
        return 'SELECT "tc"."table_name" "table", "kc"."column_name" "column", "kc"."ordinal_position" "position"
            FROM "information_schema"."table_constraints" "tc"
            INNER JOIN "information_schema"."key_column_usage" "kc"
            ON "kc"."table_name"="tc"."table_name"
                AND "kc"."table_schema"="tc"."table_schema"
                AND "kc"."constraint_name"="tc"."constraint_name"
            WHERE "tc"."table_catalog" =:db
                AND "tc"."constraint_type"=\'PRIMARY KEY\'
                AND "kc"."ordinal_position" IS NOT NULL
            ORDER BY "tc"."table_schema", "tc"."table_name", "kc"."position_in_unique_constraint"';
    }

    public function foreignKeys(): string
    {
        return 'SELECT "tc"."constraint_name" "name",
            "tc"."table_catalog" "db",
            "tc"."table_name" "table", 
            "kcu"."column_name" "column", 
            "ccu"."table_catalog" "db2",
            "ccu"."table_name" "table2",
            "ccu"."column_name" "column2",
            "kcu"."ordinal_position" "position"
        FROM "information_schema"."table_constraints" "tc" 
        JOIN "information_schema"."key_column_usage" "kcu"
        ON "tc"."constraint_name"="kcu"."constraint_name"
            AND "tc"."table_schema"="kcu"."table_schema"
        JOIN "information_schema"."constraint_column_usage" AS "ccu"
        ON "ccu"."constraint_name"="tc"."constraint_name"
            AND "ccu"."table_schema"="tc"."table_schema"
        WHERE "tc"."constraint_type"=\'FOREIGN KEY\'
            AND "tc"."table_catalog"=:db';
    }

    /**
     * @param string $database
     * @return string
     */
    public function indexes(string $database): array
    {
        return ['query' => 'SELECT "t"."relname" "table", "i"."relname" "index", "a"."attname" "column"
            FROM "pg_class" "t"
            INNER JOIN "pg_index" "ix" ON "ix"."indrelid"="t"."oid"
            INNER JOIN "pg_class" "i" ON "i"."oid"="ix"."indexrelid"
            INNER JOIN "pg_attribute" "a" ON "a"."attrelid"="t"."oid" AND "a"."attnum"=ANY("ix"."indkey")
            WHERE "t"."relkind"=\'r\'
            ORDER BY "t"."relname", "i"."relname"', 'params' => []];
    }

    /**
     * @return string
     */
    public function sequences(): string
    {
        return 'SELECT "sequence_name" "sequence" FROM "information_schema"."sequences" WHERE "sequence_catalog"=:db';
    }
}