<?php
namespace UTI\Core;

/**
 * Class AppException
 * @package UTI\Core
 */
class AppException extends \RuntimeException
{
    /**
     * Returns exceptions message prepended with stack trace string
     *
     * @return string
     */
    public function getError()
    {
        return parent::getMessage() . PHP_EOL . parent::getTraceAsString() . PHP_EOL;
    }
}
