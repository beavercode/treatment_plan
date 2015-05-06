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
                'action'     => 'main'
            ]);
        $this->router->add('plan.add', $this->uriBase . 'add')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'add'
            ]);
//        $this->router->add('plan.get', '/get')
//            ->addValues(array(
//                'controller' => 'Plan',
//                'action'     => 'get'
//            ));
        //optional
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

        # MATCHING
        // get the incoming request URL path
        // (!) not necessary, because SERVER['REQUEST_URI'] already stands as URI
        $path = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
        // get the route based on the path and server
        $route = $this->router->match($path, $this->server);

        if (! $route) {
            // no route object was returned
            throw new AppException('No such routes');
            //todo header('Location: /plan');
            //$this->router->generate('auth.login');
        }
        # DISPATCHING
        $params = $route->params;
        $class = '\\UTI\\Controller\\' . $params['controller'] . 'Controller';
        $method = $params['action'];
        $controller = new $class($this->router);
        $controller->$method($params);
    }
}
