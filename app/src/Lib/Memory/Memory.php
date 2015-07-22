<?php
/**
 * UTI, Memory usage information package
 */

namespace UTI\Lib\Memory;

/**
 * MemoryUsageInformation wrapper
 *
 * Class MemoryWrapper
 * @package UTI\Lib\Memory
 */
class Memory
{
    /**
     * @var MemoryUsageInformation
     */
    private static $memory;

    /**
     * Start measure the memory usage
     *
     * @param array $conditions Array of callback-conditions
     * @return bool
     */
    public static function start(array $conditions = [])
    {
        if (count($conditions) > 0) {
            foreach ($conditions as $condition) {
                if (! is_callable($condition) || ! $condition()) {
                    return false;
                }
            }
        }

        self::$memory = new MemoryUsageInformation();
        self::$memory->setStart();

        return true;
    }

    /**
     * Stop measure the memory usage
     */
    public static function finish()
    {
        if (self::$memory) {
            self::$memory->setEnd();
            echo '<pre>', self::$memory->printMemoryUsageInformation(), '</pre>';
        }
    }
}
