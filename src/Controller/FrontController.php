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
        $lastPost= $this->getDatabase()->findLast(Article::class);
        $this->render('index.html.twig', ["postsOnPage"=>$postsOnPage, "lastPost"=>$lastPost]);
    }

    public function Posts($page)
    {
        $nbrPerPage=5;
        $nbrArticles=$this->getDatabase()->count(Article::class);
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->findPerPage(Article::class, $firstEnter, $nbrPerPage);
        $this->twig->addGlobal('_get', $_GET);
        $this->render('articles.html.twig', ["postsOnPage"=>$postsOnPage, "nbrPages"=>$nbrPages]);
    }


    /**
     * @param $id
     */
    public function Post($id)
    {
        $article= $this->getDatabase()->find(Article::class, $id);
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
        $newComment->setSignale('0');
        $this->getDatabase()->insert($newComment);
        $this->redirect('/article/'.$id);
    }

    public function Signal($id)
    {
        $comment= $this->getDatabase()->find(Commentaire::class, $id);
        $comment->setSignale('1');
        $this->getDatabase()->update($comment);
        $this->redirect('/article/'.$comment->getArticleId());
    }

    /**
     *
     */
    public function LogIn()
    {
        echo "LogIn ";
    }
}