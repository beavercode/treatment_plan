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
     * * Read file data
     *
     * @param          $fileName
     * @param null|int $length
     * @param int      $mode
     * @return string
     * @throws AppException
     */
    public static function read($fileName, $length = -1, $mode = FILE_BINARY)
    {
        if (! is_file($fileName) && ! is_readable($fileName)) {
            throw new AppException('"' . $fileName . '" file not exists or not readable!');
        }

        if (-1 === $length) {
            $length = filesize($fileName);
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
        if (! is_writable($fileName)) {
            throw new AppException('"' . $fileName . '" file not exists or not writable!');
        }
        if ($mode === 'trunc') {
            return file_put_contents($fileName, $data, LOCK_EX);
        }

        return file_put_contents($fileName, $data, FILE_APPEND | LOCK_EX);
    }

    //todo centralized file include
    public function inc($fileName)
    {

    }
}
