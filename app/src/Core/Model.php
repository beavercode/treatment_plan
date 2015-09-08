<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Lib\Session;

/**
 * Abstract Class Model.
 *
 * @package UTI
 */
abstract class Model
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
