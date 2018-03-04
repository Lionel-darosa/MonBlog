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

    private $session;

    /**
     * Route constructor.
     * @param $name
     * @param $path
     * @param $controller
     * @param $action
     * @param $session
     */
    public function __construct($name, $path, $controller, $action, $session)
    {
        $this->name=$name;
        $this->path=$path;
        $this->controller=$controller;
        $this->action=$action;
        $this->session=$session;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
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

    /**
     * @param $requestUri
     * @return int
     */
    public function match($requestUri)
    {
        return preg_match($this->getPath(),$requestUri);
    }

    /**
     * @param $requestUri
     */
    public function call($requestUri)
    {
        session_start();
        if ($this->getSession() == '1') {
            if (!isset($_SESSION['Id'])) {
                header('Location: http://monblog.test/LogIn');
            }
        }
        preg_match_all($this->getPath(), $requestUri, $matches);
        $controller= $this->getcontroller();
        $controller= new $controller();
        call_user_func_array(array($controller, $this->getAction()), isset($matches[1]) ? $matches[1] : []);
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
}