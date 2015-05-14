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
    protected $session;
    protected $duration;

    /**
     * Constructor.
     *
     * @param null $savePath
     * @param int  $duration
     */
    public function __construct($savePath = null, $duration = 1800)
    {
        if (null !== $savePath) {
            session_save_path($savePath);
        }
        $this->duration = $duration;
        //ini_set('session.cookie_lifetime', $this->duration); //don't use it, session must live until browser is closed
        ini_set('session.gc_maxlifetime', $this->duration);
    }

    /**
     * Start session
     *
     * @return bool
     */
    public function run()
    {
        $state = session_start();
        // for less super global variables usage
        $this->session =& $_SESSION;
        $this->timeout($this->duration);

        return $state;
    }

    /**
     * Get session values by key
     *
     * @param $key
     * @return mixed
     */
    public function get($key = null)
    {
        if (null === $key) {
            return $this->session ?: false;
        }

        return array_key_exists($key, $this->session) ? $this->session[$key] : false;
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
        $this->session[$key] = $value;
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
}
