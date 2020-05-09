<?php
/**
 * Date: 08.05.2020
 * Time: 7:46
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\syntax;

/**
 * Interface MetaSyntax
 * @package kradwhite\db\syntax
 */
interface MetaSyntax
{
    /**
     * @return string
     */
    public function databases(): string;

    /**
     * @return string
     */
    public function tables(): string;

    /**
     * @return string
     */
    public function views(): string;

    /**
     * @return string
     */
    public function columns(): string;

    /**
     * @return string
     */
    public function primaryKeys(): string;

    /**
     * @return string
     */
    public function foreignKeys(): string;

    /**
     * @return string
     */
    public function indexes(): string;
}