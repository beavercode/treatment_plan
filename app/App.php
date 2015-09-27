<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI;

require('vendor/autoload.php');

use UTI\Core\Exceptions\RoutingException;
use UTI\Core\Router;
use UTI\Lib\Memory\Memory;
use UTI\Lib\Config\AbstractConfig;
use UTI\Lib\Config\ConfigData;
use UTI\Lib\Logger\AbstractLogger;
use UTI\Lib\Config\Exceptions\ConfigException;
use UTI\Model\PlanModel\Exceptions\PlanStagesModelException;
use UTI\Core\Exceptions\ModelException;
use UTI\Core\Exceptions\ViewException;
use UTI\Core\Exceptions\AppException;

/**
 * Application runner class.
 *
 * @package UTI
 */
class App
{
    /**
     * @var string Minimum PHP version requirement
     */
    private $minPhpVersion = '5.4.0';

    /**
     * @var int Maximum execution time of the script
     */
    private $executionTime = 180;

    /**
     * @var int Memory limit for php script
     */
    private $memoryLimit = '64M';

    /**
     * @var string Internal encoding for MB extension
     */
    private $mbEncoding = 'UTF-8';

    /**
     * @var string Application source directory
     */
    private $dir;

    /**
     * @var ConfigData
     */
    private $conf;

    /**
     * @var array PHP extensions needed.
     */
    private $extensions = [
        'dom',
        'gd',
        'json',
        'pcre',
        'PDO',
        'pdo_sqlite',
        'sqlite3',
        'mbstring',
        'session',
        'xsl',
        'zip'
    ];

    /**
     * Init.
     *
     * @param array  $server Supper global array $_SERVER
     * @param string $appDir Root directory for sources
     */
    public function __construct($server, $appDir = 'src/')
    {
        $this->server = $server;

        // Deny 'favicon.ico' requests.
        $this->noFavicon($this->server['REQUEST_URI']);

        // Version check.
        $this->checkVersion($this->minPhpVersion);

        //Extension check
        //todo on first run or ...?
        $this->checkExtensions($this->extensions);

        // Setup php internal environment.
        $this->phpSetup();

        // Source directory.
        $this->dir = __DIR__.'/'.$appDir;
    }

    /**
     * Run application.
     *
     * @param string $config Dsn used to get configuration options
     *
     * @throws ConfigException
     * @throws RoutingException
     */
    public function start($config = 'config.php')
    {
        try {
            // Create configuration class.
            $this->conf = AbstractConfig::init($this->dir, $config);

            $this->debugStart();//todo toDel, debug, memory usage

            // Start routing.
            $router = new Router($this->conf, $this->server);
            $router->run();

            $this->debugEnd();//todo toDel, debug, memory usage
        } catch (RoutingException $e) {
            // Routing level errors.
            AbstractLogger::init($this->conf->get('env'), $this->conf->get('dir.log').'routing.log')
                ->log($e->getError());
        } catch (PlanStagesModelException $e) {
            // Model level errors.
            // PlanStageModelException works with Ajax, cant see exception message at browser, must log.
            AbstractLogger::init('log', $this->conf->get('dir.log').'ajax.model.log')
                ->log($e->getError());
        } catch (ModelException $e) {
            // Model level errors.
            AbstractLogger::init($this->conf->get('env'), $this->conf->get('dir.log').'model.log')
                ->log($e->getError());
        } catch (ViewException $e) {
            // View level errors.
            AbstractLogger::init($this->conf->get('env'), $this->conf->get('dir.log').'view.log')
                ->log($e->getError());
        } catch (AppException $e) {
            // Common app errors.
            AbstractLogger::init($this->conf->get('env'), $this->conf->get('dir.log').'app.log')
                ->log($e->getError());
        } catch (\Exception $e) {
            // This catch block would never reached. If not - you messed up with exceptions.
            die('Oops! You messed up with exceptions');
        }
    }

    /**
     * Set PHP directives:
     *    - execution_time - time for script executions
     *    - internal_encoding - encoding for multibyte extension
     *    - memory_limit - limit script memory usage
     *
     * Note:
     *  Some of php directives can not be set here. Thus php user config(.user.ini) used.
     *  Look for it in application root folder.
     */
    private function phpSetup()
    {
        set_time_limit($this->executionTime);
        mb_internal_encoding($this->mbEncoding);

        //todo Something wrong if memory_limit set here.
        // http://php.net/manual/en/ini.core.php#ini.memory-limit
        ini_set('memory_limit', $this->memoryLimit);
    }

    /**
     * Constraints for PHP version.
     *
     * @param string $min Minimal PHP version
     */
    private function checkVersion($min = '5.4.0')
    {
        version_compare(phpversion(), $min, '>=') ?: die('PHP 5.4.0+ required.');
    }

    /**
     * Check if needed extensions are present.
     *
     * Caution: names of extensions not case insensitive.
     *
     * @param array $array Must have extensions
     */
    private function checkExtensions($array)
    {
        $ret = array_diff($array, get_loaded_extensions());
        if (true === (bool)$ret) {
            die('Extensions required: '.implode(',', $ret).'.');
        }
    }

    /**
     * No favicon.
     *
     * @param string $uri $_SERVER['REQUEST_URI']
     * @param bool   $restrict
     */
    private function noFavicon($uri, $restrict = false)
    {
        //todo Add more checks like http['SERVER']?
        if ('/favicon.ico' === $uri) {
            die;
        }
    }

    /**
     * Show memory usage and script execution time.
     *
     * Start.
     */
    private function debugStart()
    {
        Memory::start([
            // Callback: do not check for stage ajax requests
            function () {
                if ('dev' === $this->conf->get('env') && !isset($_POST['stage'])) {
                    return true;
                }
            }
        ]);
    }

    /**
     * Show memory usage and script execution time.
     *
     * Stop.
     */
    private function debugEnd()
    {
        Memory::finish();
    }
}
