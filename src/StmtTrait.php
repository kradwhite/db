<?php
/**
 * Date: 08.04.2020
 * Time: 20:53
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db;

use kradwhite\db\driver\Driver;
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
     * @return PDOStatement
     * @throws QueryException
     */
    protected function _prepareExecute(string $query, array $params = []): PDOStatement
    {
        if (!$stmt = $this->driver->getPdo()->prepare($query)) {
            throw new QueryException("Ошибка подготовки запроса: " . $this->driver->getPdo()->errorInfo(), $this->driver->getPdo()->errorCode());
        } else if (!$stmt->execute($params)) {
            throw new QueryException("Ошика выполнения запроса: " . $stmt->errorInfo(), $stmt->errorCode());
        } else {
            return $stmt;
        }
    }

    /**
     * @param PDOStatement $stmt
     * @return void
     * @throws QueryException
     */
    protected function closeCursor(PDOStatement $stmt)
    {
        if (!$stmt->closeCursor()) {
            throw new QueryException("Ошибка закрытия курсора: " . $stmt->errorInfo(), $stmt->errorInfo());
        }
    }
}