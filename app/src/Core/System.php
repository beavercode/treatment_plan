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
}
