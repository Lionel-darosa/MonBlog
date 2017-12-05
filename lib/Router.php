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

    /*
     * @var Collection
     * Implémenter la propriété
     * Collection de route
     */
    public function __construct()
    {
        $this->routeCollection= new Collection();
    }



    /*
     * @param Route $route
     * @return Router
     * Une méthode pour ajouter une route dans notre collection
     */
    public function add(Route $route)
    {
        $this->routeCollection->add($route);
    }

    /*
     * @param $requestUri
     * @return Route
     * Méthode qui renvoi la première route qui "match" entre l'url et le patj (regex)
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