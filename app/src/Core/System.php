<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Lib\File\File;

/**
 * Common application functions.
 *
 * todo Decouple method tu specialized packages: config, log.
 *
 * @package UTI\Core
 */
class System
{
    /**
     * @var array Stores config array
     */
    protected static $conf;

    /**
     * Write data to log file.
     *
     * @param  string $file Path to the file
     * @param  string $data Message to log
     *
     * @return int|bool Bytes that were written to the file, or false on failure
     *
     * @throws AppException
     */
    public static function log($file, $data)
    {
        //todo Implement own package using PSR-3 or use Monolog
        $timeSeparator = ' | ';
        $lineSeparator = "\n";
        $data = date('Y-m-d H:i:s O')." {$timeSeparator} ".(string)$data;

        return File::write($file, $data.$lineSeparator);
    }

    /**
     * Load file data.
     *
     * @param string $fileName Path to the file
     *
     * @throws AppException
     */
    public static function loadConf($fileName)
    {
        //todo move to Config class
        self::$conf = File::inc($fileName);
    }

    /**
     * Using array_reduce function (no user loops).
     *
     * This function is named fold in functional programming languages such as
     * lisp, ocaml, haskell, and erlang. Python just calls it reduce.
     *
     * @param  string $key Dictionary key
     * @param  mixed  $default Default value if key doesn't exists
     *
     * @return mixed Returns key value
     */
    public static function getConfig($key, $default = null)
    {
        //todo move to Config class
        return array_reduce(
            explode('.', $key),
            function ($result, $item) use ($default) {
                return array_key_exists($item, $result) ? $result[$item] : $default;
            },
            self::$conf
        );
    }
}
