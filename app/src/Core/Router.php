<?php
namespace UTI\Core;

use Aura\Router\RouterFactory;
use UTI\Controller;

/**
 * Class Route
 * @package UTI\Core
 */
class Router
{
    /**
     * @var $_SERVER
     */
    protected $server;
    /**
     * @var \Aura\Router\Router
     */
    protected $router;
    protected $uriBase;

    /**
     * Constructor.
     *
     * @param        $server
     * @param string $uriBase
     */
    public function __construct($server, $uriBase = '/')
    {
        $this->server = $server;
        $this->uriBase = $uriBase;

        // rid of '/base/path/' in REQUEST_URI
        // upd. not necessary, because i can use uriBase when generating the routes
        //$this->server['REQUEST_URI'] = System::getRealUri($this->server['REQUEST_URI'], $uriBase);

        $routerFactory = new RouterFactory;
        $this->router = $routerFactory->newInstance();
    }

    public function run()
    {
        # CREATING ROUTES
        //login
        $this->router->add('auth.login', $this->uriBase . 'login')
            ->addValues([
                'controller' => 'Auth',
                'action'     => 'login'
            ]);
        $this->router->add('auth.logout', $this->uriBase . 'logout')
            ->addValues([
                'controller' => 'Auth',
                'action'     => 'logout'
            ]);
        //plan
        $this->router->add('plan.main', $this->uriBase)
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'index'
            ]);
        $this->router->add('plan.get', $this->uriBase . 'get{/hash}')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'get'
            ]);


        // OPTIONAL OPTIONAL OPTIONAL OPTIONAL OPTIONAL OPTIONAL OPTIONAL OPTIONAL
        $this->router->add('plan.show', $this->uriBase . 'show')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'show'
            ]);
        $this->router->add('plan.show.name', $this->uriBase . 'show/{name}')
            ->addTokens([
                'name' => '\w+'
            ])
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'showByName'
            ]);

        // add doctor name and photo
        $this->router->add('doctor.main', $this->uriBase . 'doctor')
            ->addValues([
                'controller' => 'Doctor',
                'action'     => 'main'
            ]);
        $this->router->add('doctor.add', $this->uriBase . 'doctor/add')
            ->addValues([
                'controller' => 'Doctor',
                'action'     => 'add'
            ]);

        # MATCHING
        // get the incoming request URL path
        // (!) not necessary, because SERVER['REQUEST_URI'] already stands as URI
        $path = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
        // get the route based on the path and server
        $route = $this->router->match($path, $this->server);
        if (! $route) {
            // no route object was returned
            throw new AppException('No such routes');
            //System::redirect2Url($this->router->generate('plan.main'), $_SERVER);
        }

        # DISPATCHING
        $params = $route->params;
        $class = '\\UTI\\Controller\\' . $params['controller'] . 'Controller';
        $method = $params['action'];
        $controller = new $class($this->router);
        $controller->$method($params);
    }
}
