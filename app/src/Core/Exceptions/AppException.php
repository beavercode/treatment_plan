<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core\Exceptions;

/**
 * Common application's exception.
 *
 * todo: Proper exception(s) extended from SPL exceptions
 *
 * @package UTI
 */
class AppException extends \Exception
{
    /**
     * Custom message.
     *
     * @return string
     */
    public function getError()
    {
        $file = $this->getFile().':'.$this->getLine().PHP_EOL;
        $message = 'Message: '.$this->getMessage().PHP_EOL.PHP_EOL;
        $exception = 'Current: exception: \''.$this->getClass().'\'; code: '.$this->getCode().PHP_EOL;
        $trace = $this->getTraceAsString().PHP_EOL.PHP_EOL;
        $previous = 'Previous: '.$this->getPrevious();
        $separator = PHP_EOL.PHP_EOL.str_repeat('--', 100).PHP_EOL;

        return $file.$message.$exception.$trace.$previous.$separator;
    }

    /**
     * Get class name of exception.
     *
     * @return string
     */
    protected function getClass()
    {
        return get_class($this);
    }
}
