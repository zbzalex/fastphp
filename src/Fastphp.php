<?php

namespace fastphp;

class Fastphp
{
    /**
     * @var string
     */
    private $basePath = "/";

    /**
     * @var Route[]
     */
    private $routes;

    private EventDispatcher $dispatcher;

    public function __construct(
        $basePath = null
    ) {
        $this->routes = [];
        $this->dispatcher = new EventDispatcher();
        $this->basePath = $basePath;
    }
    
    public function setBasePath($path)
    {
        $this->basePath = $path;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }

    public function getEventDispatcher(): EventDispatcher
    {
        return $this->dispatcher;
    }

    public function route($path, $controller, $requestMethod = RequestMethod::GET)
    {
        $path = $this->basePath . trim($path, '/');
        if ($path != "/") {
            $path = rtrim($path, "/");
        }

        $route = new Route(
            $requestMethod,
            $path,
            $controller
        );

        // compile pattern
        $route->compile();

        $this->routes[] = $route;

        return $this;
    }

    public function start()
    {
        /** @var Response|null $response */
        $response = $this->handleRequest();
        if ($response !== null) {
            $response->send();
        }
    }

    public function handleRequest(Request $request = null)
    {
        $request = $request === null
            ? Request::createFromGlobals()
            : $request;

        $matcher = new RouteMatcher($this->routes);
        $requestHandler = new RequestHandler($matcher, $this->dispatcher);

        return $requestHandler->handle($request);
    }
}
