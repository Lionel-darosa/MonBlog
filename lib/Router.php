<?php

namespace Lib;


/**
 * Class Router
 * @package Lib
 */
class Router
{
    private $routeCollection;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->routeCollection= new Collection();
    }

    /**
     * @param Route $route
     */
    public function add(Route $route)
    {
        $this->routeCollection->add($route);
    }

    /**
     * @param $requestUri
     * @return mixed|null
     */
    public function match($requestUri)
    {
        $routeCollection=$this->routeCollection->filter(
            function (Route $route ) use ($requestUri) {
                return $route->match($requestUri);
            }
        );
        if ($routeCollection->count()) {
            return $routeCollection->first();
        }
        return null;
    }


}