<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

/**
 * Common application exception.
 *
 * todo: Proper exception(s) extended from SPL exceptions
 *
 * @package UTI
 */
class AppException extends \Exception
{
    /**
     * Returns exceptions message prepended with stack trace string.
     *
     * @return string
     */
    public function getError()
    {
        return parent::getMessage() . PHP_EOL . parent::getTraceAsString() . PHP_EOL;
    }
}
