<?php
/**
 * UTI
 */

namespace UTI\Core;

use UTI\Lib\File\File;

/**
 * Class System
 * @package UTI\Core
 */
class System
{
    /**
     * @var array Stores config array
     */
    protected static $conf;

    /**
     * Write data to log file
     *
     * @param  string $file Path to the file
     * @param  string $data Message to log
     * @return int|bool Bytes that were written to the file, or false on failure
     * @throws AppException
     */
    public static function log($file, $data)
    {
        //todo Implement own package using PSR-3 or use Monolog
        $timeSeparator = ' | ';
        $lineSeparator = "\n";
        $data = date('Y-m-d H:i:s O') . " {$timeSeparator} " . (string)$data;

        return File::write($file, $data . $lineSeparator);
    }

    /**
     * Load file data
     *
     * @param string $fileName Path to the file
     * @throws AppException
     */
    public static function loadConf($fileName)
    {
        self::$conf = File::inc($fileName);
    }

    /**
     * Redirect to url
     *
     * @param string $uri URI where to redirect
     * @param string $server Super-global $_SERVER variable
     * @param string $schema Transfer schema, e.g. HTTP, HTTPS
     */
    public static function redirect2Url($uri, $server, $schema = 'http://')
    {
        header('Location: ' . $schema . $server['HTTP_HOST'] . $uri);
    }

    /**
     * Using array_reduce function (no user loops)
     *
     * This function is named fold in functional programming languages such as
     * lisp, ocaml, haskell, and erlang. Python just calls it reduce.
     *
     * @param  string $key Dictionary key
     * @param  mixed  $default Default value if key doesn't exists
     * @return mixed Returns key value
     */
    public static function getConfig($key, $default = null)
    {
        return array_reduce(
            explode('.', $key),
            function ($result, $item) use ($default) {
                return array_key_exists($item, $result) ? $result[$item] : $default;
            },
            self::$conf
        );
    }
}
