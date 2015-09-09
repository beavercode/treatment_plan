<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Logger;

use UTI\Core\AppException;
use UTI\Lib\File\File;

/**
 * Writes message to a file.
 *
 * todo Decouple method tu specialized packages: config, log.
 *
 * @package UTI\Lib\Logger
 */
class FileLogger extends AbstractLogger
{
    /**
     * @var string Absolute path to a file
     */
    private $path;

    /**
     * Init.
     *
     * @param string $dsn Data source name.
     */
    public function __construct($dsn)
    {
        $this->path = $dsn;
    }

    /**
     * @inheritdoc
     *
     * @throws AppException
     */
    public function log($message)
    {
        $timeSeparator = ' | ';
        $lineSeparator = "\n";

        $data = date('Y-m-d H:i:s O')." {$timeSeparator} ".(string)$message;
        File::write($this->path, $data.$lineSeparator);
    }
}
