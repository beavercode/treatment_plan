<?php
/**
 * UTI, File package
 */

namespace UTI\Lib\File;

use UTI\Lib\File\Exceptions\FileException;

/**
 * Class File
 *
 * @package UTI\Lib\File
 */
class File
{
    /**
     * * Read file data.
     *
     * @param string   $file Name of the file
     * @param null|int $length Number of bytes to read
     * @param int      $mode Mode for read
     *      FILE_BINARY - read file as is
     *      FILE_TEXT - read file using encoding
     *
     * @return string|bool Returns the read data or false on failure
     *
     * @throws FileException
     */
    public static function read($file, $length = -1, $mode = FILE_BINARY)
    {
        if (!is_file($file) && !is_readable($file)) {
            throw new FileException(sprintf('File "%s" not exists or not readable', $file));
        }

        if (-1 === $length) {
            $length = filesize($file);
        }

        return file_get_contents($file, $mode, null, null, $length);
    }

    /**
     * Write data to file.
     *
     * @param string $file Absolute name of file where to write
     * @param string $data Data to write into file
     * @param string $mode Mode, how to write
     *      'a': in append mode
     *      'w': truncate file and write
     *
     * @return int|bool The number of bytes that were written to the file, or false on failure.
     *
     * @throws FileException
     */
    public static function write($file, $data, $mode = 'a')
    {
        if (file_exists($file) && !is_writable($file)) {
            throw new FileException(sprintf('File "%s" not exists or not writable', $file));
        }
        if ($mode === 'w') {
            return file_put_contents($file, $data, LOCK_EX);
        }

        return file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
    }

    /**
     * Include file using output buffering or not and inject variable from data array.
     *
     * @param string $file Path to file need to include
     * @param array  $varArray Array of external key/value for variable injection into code included
     * @param bool   $oBuffer Use output buffering or not
     *
     * @return mixed Content of file included
     *
     * @throws FileException
     */
    public static function inc($file, array $varArray = [], $oBuffer = false)
    {
        if (!is_file($file) && !is_readable($file)) {
            throw new FileException(sprintf('Cant include file "%s"', $file));
        }
        //extract($options); //slower 20-80% than foreach
        if (count($varArray) > 0) {
            foreach ($varArray as $varName => $varValue) {
                $$varName = $varValue;
            }
        }

        if ($oBuffer) {
            ob_start();
            include($file);

            return ob_get_clean();
        } else {
            return include($file);
        }
    }

    /**
     * Removes a file.
     *
     * @param  string $file Name of the file
     *
     * @return bool True on success, false on failure
     *
     * @throws FileException
     */
    public static function remove($file)
    {
        if (!is_file($file)) {
            throw new FileException(sprintf('"%s" is directory, can not remove', $file));
        }

        if (!is_writable($file)) {
            throw new FileException(sprintf('"%s" is not writable!', $file));
        }

        return unlink($file);
    }
}
