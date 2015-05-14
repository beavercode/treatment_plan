<?php

namespace UTI\Lib;

use UTI\Core\AppException;

/**
 * Class File
 * @package UTI\Lib
 */
class File
{
    /**
     * Read file data
     *
     * @param      $fileName
     * @param int  $mode
     * @param null $length
     * @return string
     */
    public static function read($fileName, $mode = FILE_TEXT, $length = null)
    {
        if (! is_readable($fileName)) {
            throw new AppException('"' . $fileName . '" file not exists or not readable');
        }

        return file_get_contents($fileName, $mode, null, null, $length);
    }

    /**
     * Write data to file
     *
     * @param        $fileName
     * @param        $data
     * @param string $mode
     * 'append': in append mode
     * 'trunc': truncate and write
     *
     * @return int
     * @throws AppException
     */
    public static function write($fileName, $data, $mode = 'append')
    {
        if (file_exists($fileName) && ! is_writable($fileName)) {
            throw new AppException('"' . $fileName . '" file not exists or not writable');
        }
        if ($mode === 'trunc') {
            return file_put_contents($fileName, $data, LOCK_EX);
        }

        return file_put_contents($fileName, $data, FILE_APPEND | LOCK_EX);
    }
}