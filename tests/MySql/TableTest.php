<?php 
class TableTest extends \Codeception\Test\Unit
{
    /**
     * @var \MySqlTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testCreateTable()
    {
        $this->tester->conn()->table('test_create_table-2')
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
        $this->tester->conn()->table('test_create_column')
            ->createColumn('col1', 'string', ['limit' => 1024, 'null' => true]);
    }

    public function testAlterColumn()
    {
        $this->tester->conn()->table('test_alter_column')
            ->alterColumn('col1', 'string', ['limit' => 1002, 'null' => false, 'default' => 'string default']);
    }

    public function testRenameColumn()
    {
        $this->tester->conn()->table('test_rename_column')
            ->renameColumn('col1', 'new_col_name');
    }

    public function testDropColumn()
    {
        $this->tester->conn()->table('test_drop_column')
            ->dropColumn('col1');
    }

    public function testCreateIndex()
    {
        $this->tester->conn()->table('test_create_index')
            ->createIndex(['col1'], ['unique' => true])
            ->createIndex(['col1']);
    }

    public function testRenameIndex()
    {
        $this->tester->conn()->table('test_rename_index')
            ->renameIndex('test_rename_index_col1_idx', 'new_name');
    }

    public function testDropIndex()
    {
        $this->tester->conn()->table('test_drop_index')
            ->dropIndex('test_drop_index_col1_idx');
    }

    public function testCreateForeignKey()
    {
        $this->tester->conn()->table('test_create_foreign_key_target')
            ->createForeignKey(['source_id'], 'test_create_foreign_key_source', ['id'], ['update' => 'CASCADE', 'remove' => 'CASCADE']);
    }

    public function testDropForeignKey()
    {
        $this->tester->conn()->table('test_drop_foreign_key_target')
            ->dropForeignKey('fk_test_drop_foreign_key_target_source_id');
    }

    public function testRenameTable()
    {
        $this->tester->conn()->table('test_rename_table')
            ->rename('new_table_name');
    }

    public function testDropTable()
    {
        $this->tester->conn()->table('test_drop_table')->drop();
    }
}