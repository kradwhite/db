<?php
/**
 * Date: 11.04.2020
 * Time: 15:36
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\tests\_mock;

use PDO;
use PDOStatement;

/**
 * Class MockPDOStatement
 * @package kradwhite\tests\_mock
 */
class MockPDOStatement extends PDOStatement
{
    /** @var MockPDO */
    private ?MockPDO $pdo = null;

    /**
     * MockPDOStatement constructor.
     * @param MockPDO $mockPDO
     */
    public function __construct(MockPDO $mockPDO)
    {
        $this->pdo = $mockPDO;
    }

    /**
     * @param null $input_parameters
     * @return bool
     */
    public function execute($input_parameters = null)
    {
        $this->pdo->setParams((array)$input_parameters);
        return true;
    }

    /**
     * @return bool
     */
    public function closeCursor()
    {
        return true;
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return 111;
    }

    /**
     * @param null $fetch_style
     * @param int $cursor_orientation
     * @param int $cursor_offset
     * @return array|mixed
     */
    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        return [];
    }

    /**
     * @param null $fetch_style
     * @param null $fetch_argument
     * @param array $ctor_args
     * @return array
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = null)
    {
        return [];
    }
}