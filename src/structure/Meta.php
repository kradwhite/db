<?php
/**
 * Date: 08.05.2020
 * Time: 7:38
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\structure;

use kradwhite\db\driver\Driver;
use kradwhite\db\StmtTrait;

/**
 * Class Meta
 * @package kradwhite\db\structure
 */
class Meta
{
    use StmtTrait;

    /**
     * Meta constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function databases(): array
    {

    }
}