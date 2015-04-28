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

    public function __construct($server, $uriBase = '/')
    {
        $this->server = $server;
        // rid of '/base/path/' in REQUEST_URI
        $this->server['REQUEST_URI'] = System::getRealUri($this->server['REQUEST_URI'], $uriBase);
        $routerFactory = new RouterFactory;
        $this->router = $routerFactory->newInstance();
    }

    public function run()
    {
        # CREATING ROUTES
        //login
        $this->router->add('auth.login', '/login')
            ->addValues([
                'controller' => 'Auth',
                'action'     => 'login'
            ]);
        $this->router->add('auth.logout', '/logout')
            ->addValues([
                'controller' => 'Auth',
                'action'     => 'logout'
            ]);
        //plan
        $this->router->add('plan.main', '/')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'main'
            ]);
        $this->router->add('plan.add', '/add')
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
        $this->router->add('plan.show', '/show')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'show'
            ]);
        $this->router->add('plan.show.name', '/show/{name}')
            ->addTokens([
                'name' => '\w+'
            ])
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'showByName'
            ]);
        # MATCHING
        $path = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);  // get the incoming request URL path
        $route = $this->router->match($path, $this->server);  // get the route based on the path and server

        if (! $route) {
            // no route object was returned
            throw new AppException('No such routes');
            //header('Location: /plan');
        }
        # DISPATCHING
        $params = $route->params;
        $class = '\\UTI\\Controller\\' . $params['controller'] . 'Controller';
        $method = $params['action'];
        $controller = new $class($this->router);
        $controller->$method($params);
    }
}
