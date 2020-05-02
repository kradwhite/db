<?php
/**
 * Date: 08.04.2020
 * Time: 20:53
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db;

use kradwhite\db\driver\Driver;
use kradwhite\db\exception\PdoException;
use kradwhite\db\exception\PdoStatementException;
use PDOStatement;

/**
 * Trait StmtTrait
 * @package kradwhite\db
 */
trait StmtTrait
{
    /** @var Driver */
    protected ?Driver $driver = null;

    /**
     * @param string $query
     * @param array $params
     * @param array $types
     * @return PDOStatement
     * @throws PdoException
     * @throws PdoStatementException
     */
    protected function _prepareExecute(string $query, array $params = [], array $types = []): PDOStatement
    {
        if (!$stmt = $this->driver->getPdo()->prepare($query)) {
            throw new PdoException("Ошибка подготовки запроса: ", $this->driver->getPdo());
        }
        foreach ($types as $name => $type) {
            if ($type == 'bool') {
                $params[$name] = $params[$name] ? 'true' : 'false';
            } else if ($type == 'int') {
                $params[$name] = (int)$params[$name];
            } else if ($type == 'string') {
                $params[$name] = (string)$params[$name];
            }
        }
        if (!$stmt->execute($params)) {
            throw new PdoStatementException("Ошика выполнения запроса: ", $stmt);
        } else {
            return $stmt;
        }
    }

    /**
     * @param string $query
     * @return int
     * @throws PdoException
     */
    protected function _execute(string $query): int
    {
        if (($result = $this->driver->getPdo()->exec($query)) === false) {
            throw new PdoException('Ошибка выполнения запроса: ', $this->driver->getPdo());
        }
        return $result;
    }

    /**
     * @param PDOStatement $stmt
     * @return void
     * @throws PdoStatementException
     */
    protected function closeCursor(PDOStatement $stmt)
    {
        if (!$stmt->closeCursor()) {
            throw new PdoStatementException("Ошибка закрытия курсора: ", $stmt);
        }
    }
}