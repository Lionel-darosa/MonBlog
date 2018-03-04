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
use Manager\ArticleManager;
use Manager\CommentManager;
use Model\Article;
use Model\Commentaire;

class BackController extends Controller
{
    /**
     * @param $page
     */
    public function Posts($page)
    {
        $nbrPerPage=10;
        $nbrArticles=$this->getDatabase()->getManager(ArticleManager::class)->countPosts();
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->getManager(ArticleManager::class)->findPerPage($firstEnter, $nbrPerPage);
        $this->render('admin.html.twig', [
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
        $reportComment= $this->getDatabase()->getManager(CommentManager::class)->findAll(["signale"=>'1', "article_id"=>$id]);
        $this->render('adminArticle.html.twig', [
            "article"=>$article,
            "reportComment"=>$reportComment
        ]);
    }

    /**
     * @param $page
     */
    public function Comments($page)
    {
        $nbrPerPage=10;
        $nbrSignalComment=$this->getDatabase()->getManager(CommentManager::class)->countComments("1");
        $nbrPages= ceil($nbrSignalComment / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $reportComment= $this->getDatabase()->getManager(CommentManager::class)->findComment($firstEnter, $nbrPerPage, "1");
        $this->render('adminComments.html.twig', [
            "reportComment"=>$reportComment,
            "nbrPages"=>$nbrPages
        ]);
    }

    /**
     *
     */
    public function NewPost()
    {
        $erreur=[];
        $database = new Database();
        $article = new Article($database);

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $myInputs = $this->getDatabase()->getManager(ArticleManager::class)->postFilter($_POST);
            $article->hydrate($myInputs);
            $erreur = $article->valid();
            if (count($erreur)==0){
                if ($this->getDatabase()->getManager(ArticleManager::class)->findall(['ordre' => $article->getOrdre()]) == true) {
                    $this->getDatabase()->getManager(ArticleManager::class)->changeOrdre($article->getOrdre(), '+');
                }
                $this->getDatabase()->getManager()->insert($article);
                $this->redirect('/admin/articles?page=1');
            }
        }
        $route="NewArticle";
        $this->render('NewPost.html.twig', [
            "article"=>$article,
            "erreur"=>$erreur,
            "route"=>$route
        ]);
    }

    /**
     * @param $id
     */
    public function NewUpdatePost($id)
    {
        $article = $this->getDatabase()->getManager(ArticleManager::class)->find($id);
        $erreur=[];
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $myInputs = $this->getDatabase()->getManager(ArticleManager::class)->postFilter($_POST);
            $originalArticle= clone $article;
            $article->hydrate($myInputs);
            $erreur = $article->valid();
            if (count($erreur)==0){
                if ($article->getOrdre() > $originalArticle->getOrdre()){
                    $this->getDatabase()->getManager(ArticleManager::class)->changeOrdreUpdate('-', $originalArticle->getOrdre().'<', '<='.$article->getOrdre());
                }elseif ($article->getOrdre() < $originalArticle->getOrdre()) {
                    $this->getDatabase()->getManager(ArticleManager::class)->changeOrdreUpdate('+', $originalArticle->getOrdre() . '>', '>=' . $article->getOrdre());
                }
                $this->getDatabase()->getManager()->update($article);
                $this->redirect('/admin/articles?page=1');
            }
        }
        $route="NewUpdateArticle/".$id;
        $this->render('NewPost.html.twig', [
            "article"=>$article,
            "erreur"=>$erreur,
            "route"=>$route
        ]);
    }

    /**
     * @param $id
     */
    public function DeletePost($id)
    {
        $article= $this->getDatabase()->getManager(ArticleManager::class)->find($id);
        $this->getDatabase()->getManager()->delete($article);
        $this->redirect('/admin/articles?page=1');
    }

    /**
     * @param $id
     */
    public function DeleteComment($id)
    {
        $comment= $this->getDatabase()->getManager(CommentManager::class)->find($id);
        $this->getDatabase()->getManager()->delete($comment);
        $this->redirect('/admin/article/'.$comment->getArticleId());
    }

    /**
     * @param $id
     */
    public function DeleteCommentPage($id)
    {
        $comment= $this->getDatabase()->getManager(CommentManager::class)->find($id);
        $this->getDatabase()->getManager()->delete($comment);
        $this->redirect('/admin/comments?page=1');
    }

    /**
     *
     */
    public function LogOut ()
    {
        session_start();
        session_unset();
        session_destroy();
        $this->redirect('/');
    }

}