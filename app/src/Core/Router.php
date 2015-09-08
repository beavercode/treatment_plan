<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use Aura\Router\Exception\RouteNotFound;
use Aura\Router\RouterFactory;
use UTI\Controller;

/**
 * Router using Aura/Router and inline routes.
 *
 * @package UTI
 */
class Router
{
    /**
     * @var array Super-global $_SERVER variable.
     */
    protected $server;

    /**
     * @var string Protocol URI schema.
     */
    protected $schema;

    /**
     * @var \Aura\Router\Router
     */
    protected $auraRouter;

    /**
     * @var string Store current application URI base.
     */
    protected $uriBase;

    /**
     * Init.
     *
     * @param string $server Super-global $_SERVER variable.
     * @param string $uriBase The base URL/target for all relative URLs.
     * @param string $schema Protocol schema used for redirect.
     */
    public function __construct($server, $uriBase = '/', $schema = 'http://')
    {
        $this->server = $server;
        $this->uriBase = $uriBase;
        $this->schema = $schema;
        $routerFactory = new RouterFactory;
        $this->auraRouter = $routerFactory->newInstance();
    }

    /**
     * Starts routing.
     *
     * @throws AppException
     */
    public function run()
    {
        // Set routes.
        $this->register();

        // Matching route.
        $route = $this->match();

        // Dispatching route.
        $this->dispatch($route);
    }

    /**
     * Redirect according to route name.
     *
     * @param string $route Route declared using $this->routerFactory->add().
     * @return false|null Nothing if redirect and false if no route URI.
     *
     * @throws AppException
     */
    public function redirect($route)
    {
        if ($uri = $this->getUri($route)) {
            header('Location: '.$this->schema.$this->server['HTTP_HOST'].$uri);
        }

        return $uri;
    }

    /**
     * Get URI what corresponds to route name.
     *
     * @param string $route Route name added through aura router.
     * @param array  $data The data to interpolate into the URI; data keys map to param tokens in the path.
     *
     * @return false|string A URI path string if the route name is found, or boolean false if not.
     *
     * @throws AppException
     */
    public function getUri($route, array $data = [])
    {
        try {
            $routUri = $this->auraRouter->generate($route, $data);
        } catch (RouteNotFound $e) {
            throw new AppException($e->getMessage().
                '; rethrow from "Aura\Router\Exception\RouteNotFound:"', 911, $e);
        }

        return $routUri;
    }

    /**
     * Get routes.
     *
     * todo Read routes from config file(xml,yaml)
     *
     * @return array Array of routes to register.
     */
    private function getRoutes()
    {
        //todo Make universal AuraRouter compatible format for routes got from the file.
        return [
            'auth.login' => [
                'uri'    => 'login',
                'values' => [
                    'controller' => 'Auth',
                    'action'     => 'login'],
                'tokens' => [
                    'name' => '\w+',
                ],
                'server' => [],
                'method' => [],
                'accept' => [],
            ],
        ];
    }

    /**
     * Populate AuraRouter with routes.
     *
     * todo Route in separate file?
     *
     * @return int Number of routes
     */
    private function register()
    {
        //todo
//        $this->getRoutes();

        // Auth
        $this->auraRouter->add('auth.login', $this->uriBase.'login')
            ->addValues([
                'controller' => 'Auth',
                'action'        => 'login'
            ]);
        $this->auraRouter->add('auth.logout', $this->uriBase.'logout')
            ->addValues([
                'controller' => 'Auth',
                'action'     => 'logout'
            ]);

        // Plan.
        //todo way to separate form and ajax stages
        //catch ajax main first
        /*$this->router->add('plan.index.ajax', $this->uriBase)
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'indexAjax'
            ])
            ->addServer([
                'HTTP_ACCEPT' => 'application/json(;q=(\*|0\.01|[0\.[1-9]]))?'
            ]);*/
        $this->auraRouter->add('plan.index', $this->uriBase)
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'index'
            ]);
        $this->auraRouter->add('plan.get', $this->uriBase.'get{/name}')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'get'
            ]);

        // Show list of plan saved in DB.
        $this->auraRouter->add('plan.show', $this->uriBase.'show')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'show'
            ]);

        // Show filed form with recovered/submitted form.
        $this->auraRouter->add('plan.show.name', $this->uriBase.'show/name')
            ->addTokens([
                'name' => '\w+'
            ])
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'showByName'
            ]);

        // add doctor name and photo
        $this->auraRouter->add('doctor.index', $this->uriBase.'doctor')
            ->addValues([
                'controller' => 'Doctor',
                'action'     => 'index'
            ]);
        $this->auraRouter->add('doctor.add', $this->uriBase.'doctor/add')
            ->addValues([
                'controller' => 'Doctor',
                'action'     => 'add'
            ]);

        return $this->auraRouter->count();
    }

    /**
     * Match current request_uri to declared route.
     *
     * @return \Aura\Router\Route|false
     *
     * @throws AppException
     */
    private function match()
    {
//        get the incoming request URL path
        //todo  (!) not necessary, because SERVER['REQUEST_URI'] already stands as URI
        $path = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);

        // get the route based on the path and server
        $route = $this->auraRouter->match($path, $this->server);
        if (! $route) {
//            no route object was returned
            throw new AppException('No such routes');
//            $this->redirect('plan.index');
        }

        return $route;
    }

    /**
     * Dispatch route to specific controller.
     *
     * @param \Aura\Router\Route $route
     */
    private function dispatch($route)
    {
        $params = $route->params;
        $class = '\\UTI\\Controller\\'.$params['controller'].'Controller';
        $method = $params['action'];
        $controller = new $class($this);

        //todo Not all methods need params, what to do in this situation?
        //todo what about $controller->parameters = $params ?
        $controller->$method($params);
    }
}
