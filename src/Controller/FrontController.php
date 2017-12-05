<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:39
 */

namespace Controller;

use Lib\Controller;
use Model\Database;
use Model\Article;
use Model\Commentaire;

class FrontController extends Controller
{
    /**
     *
     */
    public function Accueil()
    {
        $this->render('index.html.twig', array());
    }


    /**
     * @param $id
     */
    public function Post($id)
    {
        $database = new Database();
        $database->findall(Article::class, [$id]);
        print_r($database);



        $this->render('article.html.twig',[$database]);
    }

    /**
     *
     */
    public function About()
    {
        $this->render('about.html.twig', array());
    }

    /**
     * @param $id
     */
    public function AddComment($id)
    {

    }

    /**
     *
     */
    public function LogIn()
    {
        echo "LogIn ";
    }
}