<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 16/10/17
 * Time: 19:04
 */

namespace Lib;

class Route
{
    private $name;

    private $path;

    private $controller;

    private $action;

    private $parameters=array();




    public function __construct($name, $path,  $controller, $action)
    {
        $this->name=$name;
        $this->path=$path;
        $this->controller=$controller;
        $this->action=$action;
    }



    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name=$name;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }



    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function match($requestUri)
    {
        return preg_match($this->getPath(),$requestUri);
    }

    public function call($requestUri)
    {
        preg_match_all($this->getPath(), $requestUri, $matches);
        $controller= $this->getcontroller();
        $controller= new $controller();
        call_user_func_array(array($controller, $this->getAction()), isset($matches[1]) ? $matches[1] : []);
    }


}