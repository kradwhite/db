<?php
/**
 * Date: 08.04.2020
 * Time: 19:32
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\driver;

use kradwhite\db\exception\PdoException;
use kradwhite\db\syntax\MetaSyntax;
use kradwhite\db\syntax\TableSyntax;
use PDO;

/**
 * Class Sql
 * @package kradwhite\db\driver
 */
abstract class Sql implements Driver
{
    /** @var string */
    protected string $host;

    /** @var string */
    protected string $dbName;

    /** @var string */
    protected string $port;

    /** @var string */
    protected string $user;

    /** @var string */
    protected string $password;

    /** @var array */
    protected array $options;

    /** @var PDO */
    protected ?PDO $pdo = null;

    /** @var TableSyntax */
    protected ?TableSyntax $tableSyntax = null;

    /** @var MetaSyntax */
    protected ?MetaSyntax $metaSyntax = null;

    /**
     * Connection constructor.
     * @param string $host
     * @param string $dbName
     * @param string $user
     * @param string $password
     * @param string $port
     * @param array $options
     */
    public function __construct(string $host, string $dbName, string $user, string $password, string $port = '', array $options = [])
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
        $this->options = $options;
    }

    /**
     * @param string $object
     * @return string
     */
    public function quote(string $object): string
    {
        return $this->getTableSyntax()->quote($object);
    }

    /**
     * @param array $objects
     * @return array
     */
    public function quotes(array &$objects): array
    {
        $result = [];
        foreach ($objects as &$object) {
            $result[] = $this->tableSyntax->quote($object);
        }
        return $result;
    }

    /**
     * @return void
     * @throws PdoException
     */
    public function begin()
    {
        if (!$this->getPdo()->beginTransaction()) {
            throw new PdoException('Ошибка открытия транзации: ', $this->getPdo());
        }
    }

    /**
     * @return void
     * @throws PdoException
     */
    public function commit()
    {
        if (!$this->getPdo()->commit()) {
            throw new PdoException('Ошибка фиксации транзации: ', $this->getPdo());
        }
    }

    /**
     * @return void
     * @throws PdoException
     */
    public function rollback()
    {
        if (!$this->getPdo()->rollBack()) {
            throw new PdoException('Ошибка отката транзации: ', $this->getPdo());
        }
    }

    /**
     * @return bool
     */
    public function inTransaction(): bool
    {
        return $this->getPdo()->inTransaction();
    }
}