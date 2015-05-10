<?php
/**
 * UTI, path to magic
 *
 * User: bbr
 * Date: 25/03/15
 * Time: 20:21
 */

namespace UTI\Lib;

class File
{
    public static function readFile($fileName, $mode = FILE_TEXT, $length = null)
    {
        if (! is_readable($fileName)) {
            throw new AppException('"' . $fileName . '" file not exists or not readable');
        }

        return file_get_contents($fileName, $mode, null, null, $length);
    }

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