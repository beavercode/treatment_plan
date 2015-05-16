<?php

namespace UTI\Lib;

/**
 * Class Ajax
 * @package UTI\Lib
 */
class Ajax
{
    public function __construct()
    {
    }

    public function send($data)
    {
        echo json_encode($data);
    }
}
