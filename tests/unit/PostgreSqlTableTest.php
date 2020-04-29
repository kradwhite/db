<?php

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\db\structure\Table;

class PostgreSqlTableTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @return Table
     */
    private function getTable(): Table
    {
        return new Table($this->tester->pgsqlDriver(), 'test', []);
    }

    // tests
    public function testAddColumn()
    {
        $this->tester->expectThrowable(new BeforeQueryException('Повторное добавление колонки "col"'), function () {
            $table = $this->getTable();
            $table->addColumn('col', 'string');
            $table->addColumn('col', 'string');
        });
    }

    public function testCreateColumn()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->createColumn('col', 'VARCHAR', ['null' => false, 'limit' => 256, 'default' => '']);
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" ADD COLUMN "col" VARCHAR(256) DEFAULT \'\' NOT NULL');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testAlterColumn()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->alterColumn('col', 'BIGINT', ['null' => true, 'limit' => 15, 'default' => 1000]);
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" ALTER COLUMN "col" BIGINT(15) DEFAULT 1000 NULL');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testRenameColumn()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->renameColumn('col', 'new_col');
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" RENAME COLUMN "col" TO "new_col"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testDropColumn()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->dropColumn('col');
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" DROP COLUMN "col"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testAddCompositeIndex()
    {
        $this->tester->expectThrowable(new BeforeQueryException('Повторное добавление индекса "test_col_col2_idx"'), function () {
            $table = $this->getTable();
            $table->addIndex(['col', 'col2'], ['unique' => true]);
            $table->addIndex(['col', 'col2'], ['unique' => true]);
        });
    }

    public function testCreateIndex()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->createIndex(['col1', 'col2'], ['unique' => true, 'not_exist' => true]);
        $this->assertEquals($mockPdo->getQuery(), 'CREATE UNIQUE INDEX IF NOT EXIST "test_col1_col2_idx" ON "test" ("col1", "col2")');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testRenameIndex()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->renameIndex('col1', 'new_col2');
        $this->assertEquals($mockPdo->getQuery(), 'ALTER INDEX "col1" RENAME TO "new_col2"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testDropIndex()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->dropIndex('col1');
        $this->assertEquals($mockPdo->getQuery(), 'DROP INDEX "col1"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testAddForeignKey()
    {
        $this->tester->expectThrowable(new BeforeQueryException('Повторное добавление внешнего ключа "fk_test_ext_id_ext_id2_ext_id_id2"'), function () {
            $table = $this->getTable();
            $table->addForeignKey(['ext_id', 'ext_id2'], 'ext', ['id', 'id2'], ['delete' => 'CASCADE', 'update' => 'CASCADE']);
            $table->addForeignKey(['ext_id', 'ext_id2'], 'ext', ['id', 'id2']);
        });
    }

    public function testCreateForeignKey()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->createForeignKey(['ext_id', 'ext_id2'], 'ext', ['id', 'id2'], ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" ADD CONSTRAINT "fk_test_ext_id_ext_id2_ext_id_id2" FOREIGN KEY ("ext_id", "ext_id2") '
            . 'REFERENCES "ext" ("id", "id2") ON DELETE CASCADE ON UPDATE CASCADE');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testRenameForeignKey()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->renameForeignKey('old_name', 'new_name');
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" RENAME CONSTRAINT "old_name" TO "new_name"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testDropForeignKey()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->dropForeignKey('name');
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" DROP CONSTRAINT "name"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testPrimaryKey()
    {
        $this->tester->expectThrowable(new BeforeQueryException('Повторное добавление первичного ключа "id"'), function () {
            $table = $this->getTable();
            $table->primaryKey('id', []);
            $table->primaryKey('id', []);
        });
    }

    public function testCompositePrimaryKey()
    {
        $this->tester->expectThrowable(new BeforeQueryException('Повторное добавление первичного ключа "id"'), function () {
            $table = $this->getTable();
            $table->compositePrimaryKey(['id', 'id'], []);
        });
    }

    public function testRename()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->rename('new_name');
        $this->assertEquals($mockPdo->getQuery(), 'ALTER TABLE "test" RENAME TO "new_name"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testDrop()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->drop();
        $this->assertEquals($mockPdo->getQuery(), 'DROP TABLE "test"');
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testCreateFail()
    {
        $this->tester->expectThrowable(new BeforeQueryException('В таблице должна быть минимум 1 колонка'), function () {
            $this->getTable()->create();
        });
    }

    public function testCreateSuccess()
    {
        $mockPdo = $this->tester->pgsqlDriver()->getPdo();
        $table = $this->getTable();
        $table->addColumn('col1', 'INTEGER', ['null' => false])
            ->addColumn('col2', 'VARCHAR', ['default' => 'none'])
            ->addColumn('ext_id', 'INTEGER', ['null' => false])
            ->addColumn('id', 'INTEGER', ['null' => false])
            ->addForeignKey(['ext_id'], 'ext_test', ['id'], ['update' => 'NO ACTION', 'delete' => 'CASCADE'])
            ->addIndex(['ext_id'])
            ->addIndex(['col1', 'col2'], ['unique' => true])
            ->primaryKey('id')
            ->create();
        $this->assertEquals($mockPdo->getQuery(), "CREATE TABLE \"test\" (\n"
            . "\t\"col1\" INTEGER NOT NULL,\n"
            . "\t\"col2\" VARCHAR DEFAULT 'none' NOT NULL,\n"
            . "\t\"ext_id\" INTEGER NOT NULL,\n"
            . "\t\"id\" INTEGER NOT NULL,\n"
            . "\tPRIMARY KEY (\"id\"),\n"
            . "\tCONSTRAINT \"fk_test_ext_id_ext_test_id\" FOREIGN KEY (\"ext_id\") REFERENCES \"ext_test\" (\"id\") ON DELETE CASCADE ON UPDATE NO ACTION);\n"
            . "CREATE INDEX \"test_ext_id_idx\" ON \"test\" (\"ext_id\");\n"
            . "CREATE UNIQUE INDEX \"test_col1_col2_idx\" ON \"test\" (\"col1\", \"col2\");\n");
        $this->assertEquals($mockPdo->getParams(), []);
    }
}