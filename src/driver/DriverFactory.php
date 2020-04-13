<?php
/**
 * Date: 12.04.2020
 * Time: 18:43
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\driver;

use kradwhite\db\exception\BeforeQueryException;

/**
 * Class DriverFactory
 * @package kradwhite\db\driver
 */
class DriverFactory
{
    /**
     * @param string $driver
     * @param string $host
     * @param string $dbName
     * @param string $user
     * @param string $password
     * @param string $port
     * @param array $options
     * @return Driver
     * @throws BeforeQueryException
     */
    public static function build(string $driver, string $host, string $dbName, string $user, string $password, string $port = '', array $options = []): Driver
    {
        if ($driver == 'mysql') {
            return new MySql($host, $dbName, $user, $password, $port, $options);
        } else if ($driver == 'pgsql') {
            return new PostgreSql($host, $dbName, $user, $password, $port, $options);
        } else {
            throw new BeforeQueryException("Драйвер '$driver' не поддерживается");
        }
    }

    /**
     * @param string $driver
     * @param array $attributes
     * @return Driver
     * @throws BeforeQueryException
     */
    public static function buildFromArray(array $attributes, string $driver = ''): Driver
    {
        return self::build($attributes['driver'] ?? $driver,
            $attributes['host'] ?? '',
            $attributes['dbName'] ?? '',
            $attributes['user'] ?? '',
            $attributes['password'] ?? '',
            $attributes['port'] ?? '',
            $attributes['options'] ?? []);
    }
}