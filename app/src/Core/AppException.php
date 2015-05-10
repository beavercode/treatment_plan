<?php
namespace UTI\Core;

class AppException extends \RuntimeException
{
    public function getError()
    {
        return  parent::getTraceAsString() . "\n" . parent::getMessage() . "\n";
    }
}