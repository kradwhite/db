<?php
/**
 * Date: 12.04.2020
 * Time: 8:43
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\db\exception;

use kradwhite\language\Lang;
use kradwhite\language\LangException;
use Throwable;

/**
 * Class BeforeQueryException
 * @package kradwhite\db\exception
 */
class BeforeQueryException extends DbException
{
    /** @var Lang */
    private static ?Lang $lang = null;

    /**
     * BeforeQueryException constructor.
     * @param $id
     * @param array $params
     * @param Throwable|null $previous
     */
    public function __construct($id, array $params = [], Throwable $previous = null)
    {
        parent::__construct($this->getLang()->phrase('exceptions', $id, $params), 0, $previous);
    }

    /**
     * @return Lang
     * @throws LangException
     */
    private function getLang(): Lang
    {
        if(!self::$lang){
            $configFilename = __DIR__ . '/../../language.php';
            $rawLocale = (string)getenv('LANG');
            $locale = substr($rawLocale, 0, 2);
            self::$lang = Lang::init($configFilename, $locale);
        }
        return self::$lang;
    }
}