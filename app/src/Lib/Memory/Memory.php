<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Memory;

/**
 * MemoryUsageInformation wrapper
 *
 * @package Memory
 */
class Memory
{
    /**
     * @var MemoryUsageInformation
     */
    private static $memory;

    /**
s     * Start measure the memory usage.
     *
     * @param array $conditions Array of callback-conditions.
     *  If callback returns true then start collecting memory usage.
     */
    public static function start(array $conditions = [])
    {
        if (count($conditions) > 0) {
            foreach ($conditions as $condition) {
                if (!is_callable($condition) || !$condition()) {
                    return;
                }
            }
        }
        self::$memory = new MemoryUsageInformation();
        self::$memory->setStart();
    }

    /**
     * Stop the memory usage measuring and show results.
     */
    public static function finish()
    {
        if (self::$memory) {
            self::$memory->setEnd();
            echo '<pre>', self::$memory->printMemoryUsageInformation(), '</pre>';
        }
    }
}
