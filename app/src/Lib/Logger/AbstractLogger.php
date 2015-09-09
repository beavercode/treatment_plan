<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Logger;

/**
 * Common.
 *
 * //todo Implement using PSR-3 or use Monolog.
 *
 * @package UTI\Lib\Logger
 */
abstract class AbstractLogger
{
    /**
     * Creates an instance of AbstractLogger type.
     *
     * @param string      $env Development environment: dev or prod
     * @param null|string $dsn Name of data source, e.g. config.php or DB
     *
     * @return AbstractLogger
     */
    public static function init($env, $dsn = null)
    {
        if ('prod' === $env) {
            return new FileLogger($dsn);
        } else {
            return new BrowserLogger();
        }
    }

    /**
     * Log message.
     *
     * @param string $message Message to log
     */
    abstract public function log($message);
}
