<?php
namespace UTI\Core;

use UTI\Lib\Session;

/**
 * Class Model
 * @package UTI\Core
 */
abstract class Model
{
    /**
     * @var \UTI\Lib\Session
     */
    protected $session;

    public function __construct()
    {
        $this->session = new Session(APP_SES, APP_SES_DUR);
        $this->session->run();
    }

    /**
     * Check logged or not
     *
     * @return bool
     */
    public function isLogged()
    {
        return (bool) $this->session->get('auth');
    }
}
