<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Logger;

use UTI\Lib\File\Exceptions\FileException;
use UTI\Lib\Logger\Exceptions\LoggerException;
use UTI\Lib\File\File;

/**
 * Writes message to a file.
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
     * @throws LoggerException
     */
    public function log($message)
    {
        $timeSeparator = ' | ';
        $lineSeparator = "\n";

        $data = date('Y-m-d H:i:s O')."$timeSeparator".(string)$message;
        try {
            File::write($this->path, $data.$lineSeparator);
        } catch (FileException $e) {
            throw new LoggerException($e->getMessage(), null, $e);
        }
    }
}
