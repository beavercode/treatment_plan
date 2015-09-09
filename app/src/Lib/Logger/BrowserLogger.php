<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Lib\Logger;

/**
 * Show message to browser.
 *
 * @package UTI\Lib\Logger
 */
class BrowserLogger extends AbstractLogger
{
    /**
     * Log message.
     *
     * @param string $message Message to log
     */
    public function log($message)
    {
        printf('<pre>%s</pre>', $message);
        die;
    }
}
