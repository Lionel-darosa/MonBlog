<?php

namespace Lib;

/**
 * Class Controller
 * @package Lib
 */
abstract class Controller
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var
     */
    protected $database;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../src/View');
        $this->twig = new \Twig_Environment($loader);
        $this->twig->addGlobal('_get', $_GET);
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        if ($this->database===null) {
            $this->database= new Database();
        }
        return $this->database;
    }

    /**
     * @param $view
     * @param $data
     */
    public function render($view, $data)
    {
        $template= $this->twig->load($view);
        echo $template->render($data);
    }

    /**
     * @param $page
     */
    public function redirect($page)
    {
        header('Location: http://'.$_SERVER['HTTP_HOST'].$page);
    }

}