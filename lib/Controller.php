<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22/11/2017
 * Time: 18:58
 */

namespace Lib;


use Model\Database;

abstract class Controller
{
    protected $twig;
    protected $database;

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
        if ($this->database===null)
        {
            $this->database= new Database();
        }
        return $this->database;
    }

    public function render($view, $data)
    {
        $template= $this->twig->load($view);
        echo $template->render($data);
    }

    public function redirect($page)
    {
        header('Location: http://monblog.test'.$page);
    }

}