<?php

namespace kradwhite\tests\unit;

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\db\structure\Table;

class MySqlTableTest extends \Codeception\Test\Unit
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
        return new Table($this->tester->mysqlDriver(), 'test', []);
    }

    // tests
    public function testAddColumn()
    {
        $this->tester->expectThrowable(new BeforeQueryException("Повторное добавление колонки `col`"), function () {
            $table = $this->getTable();
            $table->addColumn('col', 'string');
            $table->addColumn('col', 'string');
        });
    }

    public function testCreateColumn()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->createColumn('col', 'string', ['null' => false, 'limit' => 256, 'default' => '', 'after' => 'col3']);
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` ADD COLUMN `col` VARCHAR(256) DEFAULT '' NOT NULL AFTER `col3`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testAlterColumn()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->alterColumn('col', 'bigint', ['null' => true, 'limit' => 15, 'default' => 1000, 'after' => 'col44']);
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` ALTER COLUMN `col` BIGINT(15) DEFAULT 1000 NULL AFTER `col44`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testRenameColumn()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->renameColumn('col', 'new_col');
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` RENAME COLUMN `col` TO `new_col`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testDropColumn()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->dropColumn('col');
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` DROP COLUMN `col`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testAddCompositeIndex()
    {
        $this->tester->expectThrowable(new BeforeQueryException("Повторное добавление индекса `test_col_col2_idx`"), function () {
            $table = $this->getTable();
            $table->addIndex(['col', 'col2'], ['unique' => true]);
            $table->addIndex(['col', 'col2'], ['unique' => true]);
        });
    }

    public function testCreateIndex()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->createIndex(['col1', 'col2'], ['unique' => true, 'not_exist' => true]);
        $this->assertEquals($mockPdo->getQuery(), "CREATE UNIQUE INDEX IF NOT EXIST `test_col1_col2_idx` ON `test` (`col1`, `col2`)");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testRenameIndex()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->renameIndex('col1', 'new_col2');
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` RENAME INDEX `col1` TO `new_col2`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testDropIndex()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->dropIndex('col1');
        $this->assertEquals($mockPdo->getQuery(), "DROP INDEX `col1` ON `test`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testAddForeignKey()
    {
        $this->tester->expectThrowable(new BeforeQueryException("Повторное добавление внешнего ключа `fk_test_ext_id_ext_id2_ext_id_id2`"), function () {
            $table = $this->getTable();
            $table->addForeignKey(['ext_id', 'ext_id2'], 'ext', ['id', 'id2'], ['delete' => 'CASCADE', 'update' => 'CASCADE']);
            $table->addForeignKey(['ext_id', 'ext_id2'], 'ext', ['id', 'id2']);
        });
    }

    public function testCreateForeignKey()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->createForeignKey(['ext_id', 'ext_id2'], 'ext', ['id', 'id2'], ['delete' => 'CASCADE', 'update' => 'CASCADE']);
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` ADD CONSTRAINT FOREIGN KEY `fk_test_ext_id_ext_id2_ext_id_id2` (`ext_id`, `ext_id2`) "
            . "REFERENCES `ext` (`id`, `id2`) ON DELETE CASCADE ON UPDATE CASCADE");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testRenameForeignKey()
    {
        $this->tester->expectThrowable(new BeforeQueryException("В MySql нельзя переименовывать внешние ключи"), function () {
            $this->getTable()->renameForeignKey('old_name', 'new_name');
        });
    }

    public function testDropForeignKey()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->dropForeignKey('name');
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` DROP FOREIGN KEY `name`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testPrimaryKey()
    {
        $this->tester->expectThrowable(new BeforeQueryException("Повторное добавление первичного ключа `id`"), function () {
            $table = $this->getTable();
            $table->primaryKey('id', []);
            $table->primaryKey('id', []);
        });
    }

    public function testCompositePrimaryKey()
    {
        $this->tester->expectThrowable(new BeforeQueryException("Повторное добавление первичного ключа `id`"), function () {
            $table = $this->getTable();
            $table->compositePrimaryKey(['id', 'id'], []);
        });
    }

    public function testRename()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->rename('new_name');
        $this->assertEquals($mockPdo->getQuery(), "ALTER TABLE `test` RENAME `new_name`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testDrop()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->drop();
        $this->assertEquals($mockPdo->getQuery(), "DROP TABLE `test`");
        $this->assertEquals($mockPdo->getParams(), []);
    }

    public function testCreate()
    {
        $mockPdo = $this->tester->mysqlDriver()->getPdo();
        $table = $this->getTable();
        $table->addColumn('col1', 'integer', ['null' => false])
            ->addColumn('col2', 'string', ['default' => 'none'])
            ->addColumn('ext_id', 'integer', ['null' => false])
            ->addColumn('id', 'auto', ['null' => false])
            ->addForeignKey(['ext_id'], 'ext_test', ['id'], ['update' => 'NO ACTION', 'delete' => 'CASCADE'])
            ->addIndex(['ext_id'])
            ->addIndex(['col1', 'col2'], ['unique' => true])
            ->primaryKey('id')
            ->create();
        $this->assertEquals($mockPdo->getQuery(), "CREATE TABLE `test` (\n"
            . "\t`col1` INTEGER NOT NULL,\n"
            . "\t`col2` VARCHAR DEFAULT 'none' NOT NULL,\n"
            . "\t`ext_id` INTEGER NOT NULL,\n"
            . "\t`id` INT AUTO INCREMENT NOT NULL,\n"
            . "\tCONSTRAINT PRIMARY KEY USING BTREE (`id`),\n"
            . "\tCONSTRAINT FOREIGN KEY `fk_test_ext_id_ext_test_id` (`ext_id`) REFERENCES `ext_test` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,\n"
            . "\tINDEX `test_ext_id_idx` USING BTREE (`ext_id`),\n"
            . "\tCONSTRAINT UNIQUE INDEX `test_col1_col2_idx` USING BTREE (`col1`, `col2`))\n");
        $this->assertEquals($mockPdo->getParams(), []);
    }
}