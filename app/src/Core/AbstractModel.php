<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Core\Exceptions\ModelException;
use UTI\Lib\Config\ConfigData;
use UTI\Lib\Session;
use UTI\Lib\Config\Exceptions\ConfigException;

/**
 * Abstract Class Model.
 *
 * @package UTI
 */
abstract class AbstractModel
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var ConfigData Class that stores configuration information
     */
    protected $conf;

    /**
     * Init.
     *
     * Runs session.
     *
     * @param  ConfigData $conf Configuration object
     *
     * @throws ModelException
     */
    public function __construct(ConfigData $conf)
    {
        try {
            $this->conf = $conf;
            $this->session = Session::run($conf->get('dir.session'), $conf->get('session.duration'));
        } catch (ConfigException $e) {
            throw new ModelException($e->getMessage(), null, $e);
        }
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

    /**
     * Log out.
     */
    public function logOut()
    {
        $this->session->halt();
    }
}
