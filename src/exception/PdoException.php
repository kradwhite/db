<?php
/**
 * Date: 07.04.2020
 * Time: 21:06
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\exception;

use PDO;
use Throwable;

/**
 * Class PdoException
 * @package kradwhite\db
 */
class PdoException extends DbException
{
    /**
     * PdoException constructor.
     * @param string $message
     * @param PDO $pdo
     * @param Throwable $previous
     */
    public function __construct(string $message, PDO $pdo, ?Throwable $previous = null)
    {
        $info = $pdo->errorInfo();
        parent::__construct($message . implode(' ', $info), $info[1], $previous);
    }
}