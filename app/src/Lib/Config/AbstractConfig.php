<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config;

use UTI\Lib\Config\Exceptions\ConfigException;

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
     * Template method patter is used.
     *
     * @param string $srcDir Root dir for sources
     * @param string $config Data source name to get configuration
     *
     * @return AbstractConfig
     *
     * @throws ConfigException
     */
    public static function init($srcDir, $config)
    {
        // For more config types use conditions.
        return (new PhpConfig($srcDir, $config))->generate();
    }

    /**
     * Generate ConfigData::options array when run application first time and
     * when config data source is changes.
     *
     * @returns array Assoc array of flattened options
     */
    abstract protected function generate();
}
