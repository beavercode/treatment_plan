<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config;

use UTI\Core\AppException;
use UTI\Lib\File\File;

/**
 * Works with php configs.
 */
class PhpConfig extends AbstractConfig
{
    /**
     * Init.
     *
     * Includes configuration array from file.
     *
     * @param string $file Absolute path to file
     *
     * @throws AppException
     */
    public function __construct($file)
    {
        $this->conf = File::inc($file);
    }

    /**
     * Using array_reduce function (no user loops).
     *
     * This function is named fold in functional programming languages such as
     * lisp, ocaml, haskell, and erlang. Python just calls it reduce.
     *
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        return array_reduce(
            explode('.', $key),
            function ($result, $item) use ($default) {
                return isset($result[$item]) ? $result[$item] : $default;
            },
            $this->conf
        );
    }
}
