<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Config;

use UTI\Lib\Config\Exceptions\ConfigException;
use UTI\Lib\Config\Exceptions\FileException;
use UTI\Lib\Config\Helpers\FlattenArrayPreserveKeysHelper;
use UTI\Lib\File\File;

/**
 * Works with php configs.
 *
 * @package UTI\Lib\Config
 */
class PhpConfig extends AbstractConfig
{
    /**
     * @var string Root dir for application sources
     */
    private $dir;

    /**
     * @var array Configuration assoc array
     */
    private $configRaw;

    /**
     * Init.
     *
     * Includes configuration array from file.
     *
     * @param string $srcDir Root dir for sources
     * @param string $dsn Absolute path to file
     *
     * @throws ConfigException
     */
    public function __construct($srcDir, $dsn)
    {
        $this->dir = $srcDir;
        $file = $srcDir.$dsn;
        try {
            $this->configRaw = File::inc($file);
        } catch (FileException $e) {
            throw new ConfigException(sprintf('Cant include configuration file: "%s"', $file), null, $e);
        }
    }

    /**
     * @inheritdoc
     */
    protected function generate()
    {
        //todo Generate configuration class on first app run or when config file is changes.
        //todo Automatic config class generation and caching.
        $flattened = FlattenArrayPreserveKeysHelper::iterate($this->configRaw, $this->dir);

        return new ConfigData($flattened);
    }
}
