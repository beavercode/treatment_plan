<?php
namespace UTI\Lib;

/**
 * Session handling
 *
 * @src
 * http://amdy.su/work-with-session/
 * http://phpfaq.ru/sessions
 * http://phpclub.ru/detail/article/sessions
 * http://php.net/manual/en/session.configuration.php
 * http://www.softtime.ru/bookphp/gl8_1.php
 * https://solutionfactor.net/blog/2014/02/08/implementing-session-timeout-with-php/
 * http://stackoverflow.com/questions/3684620/is-possible-to-keep-session-even-after-the-browser-is-closed/3684674#3684674
 *
 * Class Session
 * @package UTI\Lib
 */
class Session
{
    protected static $instance;
    protected        $session;
    protected        $duration;

    /**
     * Run session and return its instance
     *
     * @param null $savePath
     * @param int  $duration
     * @return Session
     */
    public static function run($savePath = null, $duration = 1800)
    {
        if (empty(self::$instance)) {
            self::$instance = new self($savePath, $duration);
            self::$instance->start();
        }

        return self::$instance;
    }

    /**
     * Get session values by key
     *
     * @param string $key Key to search in SESSION array
     * @return mixed Result, null if not exists
     */
    public function get($key = '')
    {
        if (! $key) {
            return $this->session ?: null;
        }

        return isset($this->session[$key]) ? $this->session[$key] : null;
    }

    /**
     * Set key value in $_SESSION
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        return $this->session[$key] = $value;
    }

    /**
     * Start session
     *
     * @return bool
     */
    protected function start()
    {
        $state = session_start();
        // for less super global variables usage
        $this->session =& $_SESSION;
        $this->timeout($this->duration);

        return $state;
    }

    /**
     * Destroy session, empty $_SESSION array, unset cookies
     *
     * @return void
     */
    public function halt()
    {
        session_unset();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - $this->duration,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * Custom session duration
     *
     * @param $duration
     */
    protected function timeout($duration)
    {
        $time = time();
        if ($this->get('last_seen') && ($time - $this->get('last_seen') > $duration)) {
            $this->halt();
        }
        $this->set('last_seen', $time);
    }

    /**
     * Init
     *
     * @param $savePath
     * @param $duration
     */
    private function __construct($savePath, $duration)
    {
        if (null !== $savePath) {
            session_save_path($savePath);
        }
        $this->duration = $duration;
        //ini_set('session.cookie_lifetime', $this->duration); //don't use it, session must live until browser is closed
        ini_set('session.gc_maxlifetime', $this->duration);
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
