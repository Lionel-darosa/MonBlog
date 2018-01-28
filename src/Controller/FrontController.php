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
        $nbrArticles=$this->getDatabase()->countPosts(Article::class);
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->findPerPage(Article::class, $firstEnter, $nbrPerPage);
        $this->render('articles.html.twig', ["postsOnPage"=>$postsOnPage, "nbrPages"=>$nbrPages]);
    }


    /**
     * @param $id
     */
    public function Post($id)
    {
        $article= $this->getDatabase()->find(Article::class,$id);
        $nbrArticles=$this->getDatabase()->countPosts(Article::class);
        $ordre= $article->getOrdre();
        $lastPost= $this->getDatabase()->findMinMax(Article::class, "MAX(ordre)");
        $firstPost= $this->getDatabase()->findMinMax(Article::class, "MIN(ordre)");
        if ($ordre < $lastPost['0'])
        {
            $articleSuivant=$this->getDatabase()->findAll(Article::class, ["ordre"=>$ordreSuivant=$ordre+1]);
            while ($articleSuivant==null)
            {
                $articleSuivant=$this->getDatabase()->findAll(Article::class, ["ordre"=>$ordreSuivant=$ordreSuivant+1]);
            }
            $articleSuivant=$articleSuivant['0']->getId();
        }else{
            $articleSuivant='0';
        }
        if ($ordre>$firstPost['0'])
        {
            $articlePrecedent=$this->getDatabase()->findAll(Article::class, ["ordre"=>$ordrePrecedent=$ordre-1]);
            while ($articlePrecedent==null)
            {
                $articlePrecedent=$this->getDatabase()->findAll(Article::class, ["ordre"=>$ordrePrecedent=$ordrePrecedent-1]);
            }
            $articlePrecedent=$articlePrecedent['0']->getId();
        }else{
            $articlePrecedent='0';
            ;
        }
        $nbrPerPage=5;
        $nbrComments=$this->getDatabase()->countCommentsArticle(Commentaire::class, $id);
        $nbrPages= ceil($nbrComments/$nbrPerPage);
        $firstEnter= ($_GET["page"]-1)*$nbrPerPage;
        $CommentsOnPage= $this->getDatabase()->findPerPageDesc(Commentaire::class, $firstEnter, $nbrPerPage, $id);
        $this->render('article.html.twig', ["article"=>$article, "nbrArticles"=>$nbrArticles, "CommentOnPage"=>$CommentsOnPage, "nbrPages"=>$nbrPages, "articleSuivant"=>$articleSuivant, "articlePrecedent"=>$articlePrecedent, "lastPost"=>$lastPost['0']]);
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
        if (!empty($_POST['pseudo']) && !empty($_POST['commentaire'])){
            $database= new Database();
            $newComment = new Commentaire($database);
            $newComment->setPseudo($_POST['pseudo']);
            $newComment->setCommentaire($_POST['commentaire']);
            $newComment->setArticleId($id);
            $newComment->setSignale('0');
            $this->getDatabase()->insert($newComment);
            $this->redirect('/article/'.$id.'?page=1');
        }elseif (empty($_POST['pseudo']) && empty($_POST['commentaire'])){
            $message='Veuillez entrer un pseudo et un commentaire';

        }elseif (empty($_POST['pseudo']) && !empty($_POST['commentaire'])){
            $message='Veuillez entrer un pseudo';
        }elseif (!empty($_POST['pseudo']) && empty($_POST['commentaire'])){
            $message='Veuillez entrer un commentaire';
        }
        $article= $this->getDatabase()->find(Article::class, $id);
        $nbrPerPage=5;
        $nbrComments=$this->getDatabase()->countCommentsArticle(Commentaire::class, $id);
        $nbrPages= ceil($nbrComments/$nbrPerPage);
        $firstEnter= '0';
        $CommentsOnPage= $this->getDatabase()->findPerPageDesc(Commentaire::class, $firstEnter, $nbrPerPage, $id);
        $nbrArticles=$this->getDatabase()->countPosts(Article::class);
        $this->render('article.html.twig', ["article"=>$article, "nbrArticles"=>$nbrArticles, "CommentOnPage"=>$CommentsOnPage, "nbrPages"=>$nbrPages, "message"=>$message]);
    }

    public function Signal($id)
    {
        $comment= $this->getDatabase()->find(Commentaire::class, $id);
        $comment->setSignale('1');
        $this->getDatabase()->update($comment);
        $this->redirect('/article/'.$comment->getArticleId().'?page='.$_GET["page"].'#commentaires');
    }

    /**
     *
     */
    public function LogIn()
    {
        $this->render('connect.html.twig', array());
    }

    public function LogInControl()
    {
        if (empty($_POST['Id']) && empty($_POST['Pass'])){
            $message='Veuillez rentrer un identifiant et un mot de passe';
            $this->render('connect.html.twig', ['message'=>$message]);
        }else if (isset($_POST['Id']) && isset($_POST['Pass'])) {

            if ($_POST['Id'] === 'admin' && $_POST['Pass'] === '1234') {
                session_start();
                $_SESSION['Id']= $_POST['Id'];
                var_dump($_SESSION['Id']);
                $this->redirect('/admin/articles?page=1');
            } else {
                $message = 'Vos identifiant et mots de passe ne sont pas correct';
                $this->render('connect.html.twig', ['message' => $message]);
            }
        }
    }
}