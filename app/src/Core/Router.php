<?php
/**
 * (c) Lex Kachan <lex.kachan@gmail.com>
 */

namespace UTI\Core;

use UTI\Core\Exceptions\RoutingException;
use UTI\Lib\Config\ConfigData;
use UTI\Controller;
use Aura\Router\RouterFactory;
use Aura\Router\Exception\RouteNotFound as AuraRouteNotFound;
use UTI\Lib\Config\Exceptions\ConfigException;

/**
 * Router using Aura/Router and inline routes.
 *
 * @package UTI
 */
class Router
{
    /**
     * @var ConfigData Class that stores configuration information
     */
    public $conf;

    /**
     * @var \Aura\Router\Router
     */
    protected $auraRouter;

    /**
     * @var array Super-global $_SERVER variable.
     */
    protected $server;

    /**
     * @var string Protocol URI schema.
     */
    protected $schema;

    /**
     * @var string Identifies the document containing the URI reference (base URI of the HTML document).
     */
    protected $uriBase;

    /**
     * Init.
     *
     * @param ConfigData $conf
     * @param array      $server Super global variable $_SERVER
     *
     * @throws RoutingException
     */
    public function __construct(ConfigData $conf, $server)
    {
        try {
            $this->server = $server;
            $this->auraRouter = (new RouterFactory)->newInstance();
            $this->conf = $conf;
            $this->schema = $this->conf->get('http_schema');
            $this->uriBase = $this->conf->get('uri_base');
        } catch (ConfigException $e) {
            // Catch if config option do not exists (wrong name, misspelling etc.)
            throw new RoutingException($e->getMessage(), null, $e);
        }
    }

    /**
     * Starts routing.
     *
     * @throws RoutingException
     */
    public function run()
    {
        // Set routes.
        $this->register($this->uriBase);
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
     * @throws RoutingException
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
     * @return string A URI path string if the route name is found
     *
     * @throws RoutingException If route is not found
     */
    public function getUri($route, array $data = [])
    {
        try {
            $routUri = $this->auraRouter->generate($route, $data);
        } catch (AuraRouteNotFound $e) {
            throw new RoutingException($e->getMessage(), null, $e);
        }

        return $routUri;
    }

    /**
     * Populate AuraRouter with routes.
     *
     * todo Route in separate file?
     *
     * @param string $uriBase Base uri for ap[
     *
     * @return int Number of routes
     */
    private function register($uriBase)
    {
        //todo
//        $this->getRoutes();

        // Auth
        $this->auraRouter->add('auth.login', $uriBase.'login')
            ->addValues([
                'controller' => 'Auth',
                'action'     => 'login'
            ]);
        $this->auraRouter->add('auth.logout', $uriBase.'logout')
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
        $this->auraRouter->add('plan.index', $uriBase)
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'index'
            ]);
        $this->auraRouter->add('plan.get', $uriBase.'get{/name}')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'get'
            ]);

        // Show list of plan saved in DB.
        $this->auraRouter->add('plan.show', $uriBase.'show')
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'show'
            ]);

        // Show filed form with recovered/submitted form.
        $this->auraRouter->add('plan.show.name', $uriBase.'show/name')
            ->addTokens([
                'name' => '\w+'
            ])
            ->addValues([
                'controller' => 'Plan',
                'action'     => 'showByName'
            ]);

        // add doctor name and photo
        $this->auraRouter->add('doctor.index', $uriBase.'doctor')
            ->addValues([
                'controller' => 'Doctor',
                'action'     => 'index'
            ]);
        $this->auraRouter->add('doctor.add', $uriBase.'doctor/add')
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
     * @throws RoutingException
     */
    private function match()
    {
        // Get incoming request's URL path.
        //todo  (!) not necessary, because SERVER['REQUEST_URI'] already stands as URI
        $path = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);

        // Get route based on the path and server.
        $route = $this->auraRouter->match($path, $this->server);
        if (!$route) {
//            no route object was returned
            throw new RoutingException('No such route.');
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
        $controller = new $class($this, $this->conf);

        //todo Not all methods need params, what to do in this situation?
        //todo what about $controller->parameters = $params ?
        $controller->$method($params);
    }

    /**
     * @not_used
     *
     * Dispatch route to specific controller.
     *
     * //todo This approach of routing limiting controller with concrete model. :(
     *
     * @param \Aura\Router\Route $route
     */
    private function dispatch_new_experimental($route)
    {
        $namespace = '\\UTI';
        $params = $route->params;

        // Create controller and model.
        $controller = $namespace.'\\Controller\\'.$params['controller'].'Controller';
        $model = $namespace.'\\Model\\'.$params['controller'].'Model';
        $action = $params['action'];

        $controller = new $controller($this, new $model($this->conf), $this->conf);
        //todo Not all methods need params, what to do in this situation?
        //todo what about $controller->parameters = $params ?
        $controller->$action($params);
    }

    /**
     * @not_used
     *
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
}
