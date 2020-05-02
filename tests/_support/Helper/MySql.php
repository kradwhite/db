<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use kradwhite\db\Connection;

class MySql extends \Codeception\Module
{
    /** @var Connection */
    private ?Connection $conn = null;

    /**
     * @return Connection
     */
    public function conn(): Connection
    {
        if (!$this->conn) {
            $this->conn = new Connection(new \kradwhite\db\driver\MySql('0.0.0.0', 'test-1', 'admin', 'admin', '3306'));
        }
        return $this->conn;
    }
}
