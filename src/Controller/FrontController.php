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
use Lib\Database;
use Model\Article;
use Model\Commentaire;
use Manager\ArticleManager;
use Manager\CommentManager;

class FrontController extends Controller
{
    /**
     *
     */
    public function Accueil()
    {
        $postsOnPage= $this->getDatabase()->getManager(ArticleManager::class)->findPerPage(0, 5);
        $lastPost= $this->getDatabase()->getManager(ArticleManager::class)->findLast();
        $this->render('index.html.twig', [
            "postsOnPage"=>$postsOnPage,
            "lastPost"=>$lastPost
        ]);
    }

    /**
     * @param $page
     */
    public function Posts($page)
    {
        $nbrPerPage=5;
        $nbrArticles=$this->getDatabase()->getManager(ArticleManager::class)->countPosts();
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->getManager(ArticleManager::class)->findPerPage($firstEnter, $nbrPerPage);
        $this->render('articles.html.twig', [
            "postsOnPage"=>$postsOnPage,
            "nbrPages"=>$nbrPages
        ]);
    }

    /**
     * @param $id
     */
    public function Post($id)
    {
        $article= $this->getDatabase()->getManager(ArticleManager::class)->find($id);
        $erreur=[];
        $database= new Database();
        $newComment = new Commentaire($database);
        if ($_SERVER["REQUEST_METHOD"]=="POST"){
            $newComment->setArticleId($id);
            $newComment->setSignale('0');
            $newComment->hydrate($_POST);
            $erreur = $newComment->valid();
            if (count($erreur)==0){
                $this->getDatabase()->getManager()->insert($newComment);
                $this->redirect('/article/'.$id.'?page=1');
            }
        }
        $nbrArticles=$this->getDatabase()->getManager(ArticleManager::class)->countPosts();
        $ordre= $article->getOrdre();
        $lastPost= $this->getDatabase()->getManager(ArticleManager::class)->findMinMax("MAX(ordre)");
        $firstPost= $this->getDatabase()->getManager(ArticleManager::class)->findMinMax("MIN(ordre)");
        $nextAndPrevious= $this->getDatabase()->getManager(ArticleManager::class)->nextPreviousPost($ordre, $firstPost, $lastPost);
        $nbrPerPage=5;
        $nbrComments=$this->getDatabase()->getManager(CommentManager::class)->countCommentsArticle($id);
        $nbrPages= ceil($nbrComments/$nbrPerPage);
        $firstEnter= ($_GET["page"]-1)*$nbrPerPage;
        $CommentsOnPage= $this->getDatabase()->getManager(CommentManager::class)->findPerPageDesc($firstEnter, $nbrPerPage, $id);
        $this->render('article.html.twig', [
            "article"=>$article,
            "erreur"=>$erreur,
            "newComment"=>$newComment,
            "nbrArticles"=>$nbrArticles,
            "CommentOnPage"=>$CommentsOnPage,
            "nbrPages"=>$nbrPages,
            "nextAndPrevious"=>$nextAndPrevious,
            "lastPost"=>$lastPost['0']
        ]);
    }

    /**
     *
     */
    public function About()
    {
        $this->render('about.html.twig', array());
    }

    public function Signal($id)
    {
        $comment= $this->getDatabase()->getManager(CommentManager::class)->find($id);
        $comment->setSignale('1');
        $this->getDatabase()->getManager()->update($comment);
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