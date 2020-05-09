<?php

namespace kradwhite\tests\MySql;

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
        $this->assertCount(2, $dbs);
        $this->assertContains('test-1', $dbs);
        $this->assertContains('information_schema', $dbs);
    }

    public function testTables()
    {
        $tables = $this->tester->conn()->meta()->tables();
        $this->assertNotEmpty($tables);
        $this->assertEquals('information_schema', $tables[0]['db']);
        $this->assertEquals('CHARACTER_SETS', $tables[0]['table']);
    }

    public function testViews()
    {
        $views = $this->tester->conn()->meta()->views();
        $this->assertNotEmpty($views);
        $this->assertCount(1, $views);
        $this->assertEquals('test-1', $views[0]['db']);
        $this->assertEquals('test_view_select_view', $views[0]['table']);
    }

    public function testColumns()
    {
        $columns = $this->tester->conn()->meta()->columns();
        $this->assertNotEmpty($columns);
        $this->assertArrayHasKey('db', $columns[0]);
        $this->assertArrayHasKey('table', $columns[0]);
        $this->assertArrayHasKey('column', $columns[0]);
    }

    public function testPrimaryKeys()
    {
        $columns = $this->tester->conn()->meta()->primaryKeys();
        $this->assertNotEmpty($columns);
        $this->assertArrayHasKey('position', $columns[0]);
        $this->assertArrayHasKey('db', $columns[0]);
        $this->assertArrayHasKey('table', $columns[0]);
        $this->assertArrayHasKey('column', $columns[0]);
    }

    public function testForeignKeys()
    {
        $columns = $this->tester->conn()->meta()->foreignKeys();
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
        $columns = $this->tester->conn()->meta()->indexes();
        $this->assertNotEmpty($columns);
        $this->assertArrayHasKey('db', $columns[0]);
        $this->assertArrayHasKey('table', $columns[0]);
        $this->assertArrayHasKey('index', $columns[0]);
        $this->assertArrayHasKey('column', $columns[0]);
    }
}