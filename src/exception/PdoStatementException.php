<?php
/**
 * Date: 12.04.2020
 * Time: 9:05
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\exception;

use PDOStatement;
use Throwable;

/**
 * Class PdoStatementException
 * @package kradwhite\db\exception
 */
class PdoStatementException extends DbException
{
    /**
     * PdoException constructor.
     * @param string $message
     * @param PDOStatement $stmt
     * @param Throwable $previous
     */
    public function __construct(string $message, PDOStatement $stmt, ?Throwable $previous = null)
    {
        $info = $stmt->errorInfo();
        parent::__construct("$message\n" . implode(' ', $info) . "\nЗапрос:\n{$stmt->queryString}\n", $info[1], $previous);
    }
}