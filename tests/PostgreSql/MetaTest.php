<?php

namespace kradwhite\tests\PostgreSql;

class MetaTest extends \Codeception\Test\Unit
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
    public function testDatabases()
    {
        $dbs = $this->tester->conn()->meta()->databases();
        $this->assertCount(4, $dbs);
        $this->assertContains('test-2', $dbs);
        $this->assertContains('postgres', $dbs);
    }

    public function testTables()
    {
        $tables = $this->tester->conn()->meta()->tables('test-2');
        $this->assertNotEmpty($tables);
        $this->assertContains('test_alter_column', $tables);
    }

    public function testViews()
    {
        $views = $this->tester->conn()->meta()->views('test-2');
        $this->assertNotEmpty($views);
        $this->assertContains('test_view_select_view', $views);
    }

    public function testColumns()
    {
        $columns = $this->tester->conn()->meta()->columns('test-2');
        $this->assertNotEmpty($columns);
        $this->assertArrayHasKey('position', $columns[0]);
        $this->assertArrayHasKey('table', $columns[0]);
        $this->assertArrayHasKey('column', $columns[0]);
    }

    public function testPrimaryKeys()
    {
        $columns = $this->tester->conn()->meta()->primaryKeys('test-2');
        $this->assertNotEmpty($columns);
        $this->assertArrayHasKey('position', $columns[0]);
        $this->assertArrayHasKey('table', $columns[0]);
        $this->assertArrayHasKey('column', $columns[0]);
    }

    public function testForeignKeys()
    {
        $columns = $this->tester->conn()->meta()->foreignKeys('test-2');
        $this->assertNotEmpty($columns);
        $this->assertArrayHasKey('position', $columns[0]);
        $this->assertArrayHasKey('db', $columns[0]);
        $this->assertArrayHasKey('table', $columns[0]);
        $this->assertArrayHasKey('column', $columns[0]);
        $this->assertArrayHasKey('db2', $columns[0]);
        $this->assertArrayHasKey('table2', $columns[0]);
        $this->assertArrayHasKey('column2', $columns[0]);
    }

    public function testIndexes()
    {
        $columns = $this->tester->conn()->meta()->indexes('test-2');
        $this->assertNotEmpty($columns);
        $this->assertArrayHasKey('table', $columns[0]);
        $this->assertArrayHasKey('index', $columns[0]);
        $this->assertArrayHasKey('column', $columns[0]);
    }

    public function testSequences()
    {
        $sequences = $this->tester->conn()->meta()->sequences('test-2');
        $this->assertNotEmpty($sequences);
        $this->assertContains('test_update_id_seq', $sequences);
    }
}