<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22/11/2017
 * Time: 18:58
 */

namespace Lib;


abstract class Controller
{
    protected $twig;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../src/View');
        $this->twig = new \Twig_Environment($loader);
    }

    public function render($view, $data)
    {
        $template= $this->twig->load($view);
        echo $template->render($data);
    }

}