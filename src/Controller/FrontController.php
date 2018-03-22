<?php

namespace Controller;

use Lib\Controller;
use Model\Commentaire;
use Manager\ArticleManager;
use Manager\CommentManager;

/**
 * Class FrontController
 * @package Controller
 */
class FrontController extends Controller
{
    /**
     *
     */
    public function accueil()
    {
        $postsOnPage= $this->getDatabase()->getManager(ArticleManager::class)->findPerPage(0, 5);
        $lastPost= $this->getDatabase()->getManager(ArticleManager::class)->findLast();
        $this->render(
            'index.html.twig', [
                "postsOnPage"=>$postsOnPage,
                "lastPost"=>$lastPost
            ]
        );
    }

    /**
     * @param $page
     */
    public function posts($page)
    {
        $nbrPerPage=5;
        $nbrArticles=$this->getDatabase()->getManager(ArticleManager::class)->countPosts();
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->getManager(ArticleManager::class)->findPerPage($firstEnter, $nbrPerPage);
        $this->render(
            'articles.html.twig', [
                "postsOnPage"=>$postsOnPage,
                "nbrPages"=>$nbrPages
            ]
        );
    }

    /**
     * @param $id
     */
    public function post($id)
    {
        $article= $this->getDatabase()->getManager(ArticleManager::class)->find($id);
        $newComment = new Commentaire($this->database);
        $commentData=[
            'newComment'=>'',
            'erreur'=>''
        ];
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            $myInput = filter_var_array($_POST, $newComment->metadata['args']);
            $commentData = $this->getDatabase()->getManager(CommentManager::class)->formDataComment($myInput, $newComment, $id);
        }
        $nbrArticles=$this->getDatabase()->getManager(ArticleManager::class)->countPosts();
        $CommentsOnPage= $this->getDatabase()->getManager(CommentManager::class)->findPerPageDesc(($_GET["page"]-1)*5, 5, $id);
        $this->render(
            'article.html.twig', [
                "article"=>$article,
                "erreur"=>$commentData['erreur'],
                "newComment"=>$commentData['newComment'],
                "nbrArticles"=>$nbrArticles,
                "CommentOnPage"=>$CommentsOnPage,
            ]
        );
    }

    /**
     *
     */
    public function about()
    {
        $this->render('about.html.twig', array());
    }

    /**
     * @param $id
     */
    public function signal($id)
    {
        $comment= $this->getDatabase()->getManager(CommentManager::class)->find($id);
        $comment->setSignale('1');
        $this->getDatabase()->getManager()->update($comment);
        $this->redirect('/article/'.$comment->getArticleId().'?page='.$_GET["page"].'#commentaires');
    }

    /**
     *
     */
    public function logIn()
    {
        $this->render('connect.html.twig', array());
    }

    /**
     *
     */
    public function logInControl()
    {
        $myInputs=[];
        if ($_SERVER["REQUEST_METHOD"]=="POST") {
            $args = array(
                'Id' => FILTER_SANITIZE_SPECIAL_CHARS,
                'Pass' => FILTER_SANITIZE_SPECIAL_CHARS
            );
            $myInputs = filter_var_array($_POST, $args);
        }
        if (empty($myInputs['Id']) && empty($myInputs['Pass'])) {
            $message='Veuillez rentrer un identifiant et un mot de passe';
            $this->render('connect.html.twig', ['message'=>$message]);
        } else if (isset($myInputs['Id']) && isset($myInputs['Pass'])) {
            if ($myInputs['Id'] === 'admin' && $myInputs['Pass'] === '1234') {
                session_start();
                $_SESSION['Id']= $myInputs['Id'];
                $this->redirect('/admin/articles?page=1');
            } else {
                $message = 'Vos identifiant et mots de passe ne sont pas correct';
                $this->render('connect.html.twig', ['message' => $message]);
            }
        }
    }

    /**
     *
     */
    public function missingPage()
    {
        $this->render('missingPage.html.twig', array());
    }
}