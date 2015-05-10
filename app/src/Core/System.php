<?php

namespace UTI\Core;

use UTI\Lib\File;

/**
 * System wide functions
 *
 * Class System
 * @package UTI\Lib
 */
class System
{
    protected static $conf;

    /**
     * Write data to log file
     *
     * @param        $fileName
     * @param        $data
     * @return false|int
     * @internal param string $time_sep
     * @internal param string $nl New line separator, unix style be default
     */
    public static function log($fileName, $data)
    {
        $timeSeparator = ' | ';
        $lineSeparator = "\n";
        $data = date('Y-m-d H:i:s O') . " {$timeSeparator} " . $data;

        return File::write($fileName, $data . $lineSeparator);
    }

    /**
     * Load file data
     *
     * @param $fileName
     * @return mixed
     * @throws AppException
     */
    public static function loadConf($fileName)
    {
        if (! $data = include "$fileName") {
            throw new AppException('Can\'t load file "' . $fileName . '""');
        }
        self::$conf = $data;
    }

    /**
     * Include template file
     *
     * @param $fileName
     * @return string
     */
    public static function loadData($fileName)
    {
        ob_start();
        if (! include "$fileName") {
            throw new AppException('Can\'t load file(ob) "' . $fileName . '"');
        }

        return ob_get_clean();
    }

    /**
     * Redirect to url
     *
     * @param        $uri
     * @param        $server
     * @param string $schema
     */
    public static function redirect2Url($uri, $server, $schema = 'http://')
    {
        header('Location: ' . $schema . $server['HTTP_HOST'] . $uri);
    }

    /**
     * Rid of base path in URI string, e.g.:
     * example.com/base/path/controller/action/id   =>  example.com/controller/action/id
     *
     * @param  string $uri where to find
     * @param  string $uriBase what to find
     * @param  string $baseReplace replacement string, default ''
     * @return string
     */
    public static function getRealUri($uri, $uriBase = '/', $baseReplace = '')
    {
        if ($uriBase === '/' || $uriBase === '') {
            return self::removeSlashes($uri);
        }
        $uri = self::removeSlashes($uri);
        $realUri = '/' . substr_replace($uri, $baseReplace, strpos($uri, $uriBase), strlen($uriBase));

        return self::removeSlashes($realUri);
    }

    /**
     * Remove slashes duplicates
     *
     * @param $str
     * @return mixed
     */
    public static function removeSlashes($str)
    {
        return preg_replace('~/+~', '/', $str);
    }

    /**
     * Using array_reduce function (no user loops)
     *
     * This function is named fold in functional programming languages such as
     * lisp, ocaml, haskell, and erlang. Python just calls it reduce.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
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
