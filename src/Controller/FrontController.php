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
        $postsOnPage= $this->getDatabase()->findPerPage(Article::class, 0, 5);
        $this->render('index.html.twig', ["postsOnPage"=>$postsOnPage]);
    }

    public function Posts($page)
    {
        $nbrPerPage=5;
        $nbrArticles=$this->getDatabase()->count(Article::class);
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->findPerPage(Article::class, $firstEnter, $nbrPerPage);
        $this->render('articles.html.twig', ["postsOnPage"=>$postsOnPage]);
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
        $database= new Database();
        $newComment = new Commentaire($database);
        $newComment->setPseudo($_POST['pseudo']);
        $newComment->setCommentaire($_POST['commentaire']);
        $newComment->setArticleId($id);
        $this->getDatabase()->insert($newComment);
    }

    /**
     *
     */
    public function LogIn()
    {
        echo "LogIn ";
    }
}