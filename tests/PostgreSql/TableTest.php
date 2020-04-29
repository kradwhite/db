<?php

namespace kradwhite\tests\PostgreSql;

use kradwhite\db\Connection;
use kradwhite\db\driver\PostgreSql;

class TableTest extends \Codeception\Test\Unit
{
    /**
     * @var \PostgreSqlTester
     */
    protected $tester;

    /** @var Connection */
    protected $connection = null;

    protected function _before()
    {
        if (!$this->connection) {
            $this->connection = new Connection(new PostgreSql('localhost', 'test-1', 'admin', 'admin', '5434'));
        }
    }

    protected function _after()
    {
    }

    // tests
    public function testCreate()
    {
        $this->connection->table('test_create_table-2')
            ->addColumn('col1', 'INTEGER', ['null' => false])
            ->addColumn('col2', 'VARCHAR', ['default' => 'none'])
            ->addColumn('ext_id', 'INTEGER', ['null' => false])
            ->addColumn('id', 'INTEGER', ['null' => false])
            ->addForeignKey(['ext_id'], 'test_create_table-1', ['id'], ['update' => 'NO ACTION', 'delete' => 'CASCADE'])
            ->addIndex(['ext_id'])
            ->addIndex(['col1', 'col2'], ['unique' => true])
            ->primaryKey('id')
            ->create();
    }

    public function testCreateColumn()
    {
        $this->connection->table('test_create_column')
            ->createColumn('col1', 'string', ['limit' => 1024, 'null' => true]);
    }

    public function testAlterColumn()
    {
        $this->connection->table('test_alter_column')
            ->alterColumn('col1', 'string', ['limit' => 1002, 'null' => false, 'default' => 'string default']);
    }

    public function testRenameColumn()
    {
        $this->connection->table('test_rename_column')
            ->renameColumn('col1', 'col3434');
    }
}