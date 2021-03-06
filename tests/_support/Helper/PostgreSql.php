<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use kradwhite\db\Connection;

class PostgreSql extends \Codeception\Module
{
    /** @var Connection */
    private ?Connection $conn = null;

    /**
     * @return Connection
     */
    public function conn(): Connection
    {
        if (!$this->conn) {
            $this->conn = new Connection(new \kradwhite\db\driver\PostgreSql('localhost', 'test-2', 'admin', 'admin', '5432'));
        }
        return $this->conn;
    }
}
