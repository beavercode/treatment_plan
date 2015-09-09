<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config;

use UTI\Core\AppException;

/**
 * Common.
 *
 * ::load() can be used as fabric if more than one realisation.
 *
 */
abstract class AbstractConfig
{
    protected $conf;

    /**
     * Creates an instance of AbstractConfig type.
     *
     * @param string $file Absolute path to file
     *
     * @return AbstractConfig
     *
     * @throws AppException
     */
    public static function init($file)
    {
        // May add more config classes and use as alternatives in if.
        return new PhpConfig($file);
    }

    /**
     * Get value from by key.
     *
     * @param string     $key Key in notation 'app.env'
     * @param null|mixed $default Default value is key not found
     *
     * @return mixed Returns key value
     */
    abstract public function get($key, $default = null);
}
