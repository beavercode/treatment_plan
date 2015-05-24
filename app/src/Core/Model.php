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
        $this->session = Session::run(APP_SES, APP_SES_DUR);
    }

    /**
     * Check logged or not
     *
     * @return bool
     */
    public function isLogged()
    {
        return $this->session->get('auth');
    }
}
