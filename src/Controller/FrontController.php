<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:39
 */

namespace Controller;


use Lib\Collection;
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
        $article= $this->getDatabase()->find(Article::class, $id);
        $article->getCommentaire();
        $this->render('article.html.twig', ["article"=>$article]);
    }

    /**
     *
     */
    public function About()
    {
        $this->render('about.html.twig', array());
    }

    public function Contact()
    {
        $this->render('contact.html.twig', array());
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