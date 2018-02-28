<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 16/10/17
 * Time: 19:03
 */

namespace Lib;

use Lib\Collection;
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
        $routeCollection=$this->routeCollection->filter(function (Route $route ) use ($requestUri){
            return $route->match($requestUri);
        });
        if ($routeCollection->count()){
            return $routeCollection->first();
        }else{
            return null;
        }
    }


}