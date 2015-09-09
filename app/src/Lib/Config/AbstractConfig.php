<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config;

use UTI\Core\AppException;

/**
 * Common.
 *
 * @package UTI\Lib\Config
 */
abstract class AbstractConfig
{
    /**
     * Creates an instance of AbstractConfig type.
     *
     * @param string $srcDir Root dir for sources
     * @param string $dsn Data source name to get configuration
     *
     * @return AbstractConfig
     *
     * @throws AppException
     */
    public static function init($srcDir, $dsn)
    {
        // May add more config classes and use as alternatives in if.
        return new PhpConfig($srcDir, $dsn);
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

    /**
     * Generate Config class.
     *
     * @returns Config
     */
    abstract protected function generate();
}
