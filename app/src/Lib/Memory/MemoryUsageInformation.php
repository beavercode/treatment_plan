<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Memory;

/**
 * Class MemoryUsageInformation
 *
 * todo Decouple data from it's presentation.
 *
 * @package Memory
 */
class MemoryUsageInformation
{
    private $realUsage;
    private $statistics = [];

    /**
     * Init.
     *
     * @param bool|false $realUsage
     */
    public function __construct($realUsage = false)
    {
        $this->realUsage = $realUsage;
    }

    /**
     * Returns current memory usage with or without styling.
     *
     * @param bool|true $styled
     *
     * @return int|string
     */
    public function getCurrentMemoryUsage($styled = true)
    {
        $mem = memory_get_usage($this->realUsage);

        return ($styled) ? $this->byteFormat($mem) : $mem;
    }

    /**
     * Returns peak of memory usage.
     *
     * @param bool|true $styled
     *
     * @return int|string
     */
    public function getPeakMemoryUsage($styled = true)
    {
        $mem = memory_get_peak_usage($this->realUsage);

        return ($styled) ? $this->byteFormat($mem) : $mem;
    }

    /**
     * Set memory usage with info
     *
     * @param string $info
     */
    public function setMemoryUsage($info = '')
    {
        $this->statistics[] = [
            'time'         => time(),
            'info'         => $info,
            'memory_usage' => $this->getCurrentMemoryUsage()
        ];
    }

    /**
     * Print all memory usage info, memory limit and execution time
     */
    public function printMemoryUsageInformation()
    {
        $elapsed = 0;
        foreach ($this->statistics as $statistic) {
            echo 'Time: '.$statistic['time'].
                ' | Memory Usage: '.$statistic['memory_usage'].
                ' | Info: '.$statistic['info'];
            echo "\n";
            $elapsed = $statistic['time'] - $elapsed;
        }
        echo "\n\n", 'Peak of memory usage: '.$this->getPeakMemoryUsage(), "\n\n";
        echo 'Execution time: '.$elapsed.' sec';
    }

    /**
     * Set start with default info or some custom info
     *
     * @param string $info
     */
    public function setStart($info = 'Initial Memory Usage')
    {
        $this->setMemoryUsage($info);
    }

    /**
     * Set end with default info or some custom info
     *
     * @param string $info
     */
    public function setEnd($info = 'Memory Usage at the End')
    {
        $this->setMemoryUsage($info);
    }

    /**
     * Byte formatting
     *
     * @param        $bytes
     * @param string $unit
     * @param int    $decimals
     * @return string
     */
    private function byteFormat($bytes, $unit = '', $decimals = 2)
    {
        $units = [
            'B'  => 0,
            'KB' => 1,
            'MB' => 2,
            'GB' => 3,
            'TB' => 4,
            'PB' => 5,
            'EB' => 6,
            'ZB' => 7,
            'YB' => 8
        ];

        $value = 0;
        if ($bytes > 0) {
            // Generate automatic prefix by bytes
            // If wrong prefix given
            if (! array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }

            // Calculate byte value by prefix
            $value = ($bytes / pow(1024, floor($units[$unit])));
        }

        // If decimals is not numeric or decimals is less than 0
        // then set default value
        if (! is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        // Format output
        return sprintf('%.'.$decimals.'f '.$unit, $value);
    }
}
