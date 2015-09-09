<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Lib\Config\Config;
use UTI\Lib\Session;

/**
 * Abstract Class Model.
 *
 * @package UTI
 */
abstract class AbstractModel
{
    /**
     * @var \UTI\Lib\Session
     */
    protected $session;

    /**
     * Init.
     *
     * Runs session.
     */
    public function __construct()
    {
        $this->session = Session::run(Config::$APP_SES, Config::$APP_SES_DUR);
    }

    /**
     * Check logged or not.
     *
     * @return bool
     */
    public function isLogged()
    {
        return $this->session->get('auth');
    }
}
