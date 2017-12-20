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

class BackController extends Controller
{
    /**
     *
     */
    public function Posts($page)
    {
        $nbrPerPage=5;
        $nbrArticles=$this->getDatabase()->count(Article::class);
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->findPerPage(Article::class, $firstEnter, $nbrPerPage);
        $this->render('admin.html.twig', ["postsOnPage"=>$postsOnPage]);
    }

    /**
     * @param $id
     */
    public function Post($id)
    {
        $article= $this->getDatabase()->find(Article::class, $id);
        $article->getCommentaire();
        $this->render('adminArticle.html.twig', ["article"=>$article]);
    }

    /**
     *
     */
    public function NewPost()
    {
        $this->render('NewPost.html.twig', array());
    }

    /**
     *
     */
    public function InsertPost()
    {
        $newArticle = new Article($this->getDatabase());
        $newArticle->setTitre($_POST['titre']);
        $newArticle->setAuteur($_POST['auteur']);
        $newArticle->setArticle($_POST['article']);
        $newArticle->setOrdre($_POST['ordre']);
        $this->getDatabase()->insert($newArticle);
        $this->render('admin.html.twig', array());
    }

    /**
     * @param $id
     */
    public function NewUpdatePost($id)
    {
        $article= $this->getDatabase()->find(Article::class, $id);
        $this->render('updatePost.html.twig', ["article"=>$article]);
    }

    /**
     * @param $id
     */
    public function UpdatePost($id)
    {
        $article = $this->getDatabase()->find(Article::class, $id );
        $article->setTitre($_POST['titre']);
        $article->setAuteur($_POST['auteur']);
        $article->setArticle($_POST['article']);
        $article->setOrdre($_POST['ordre']);
        $this->getDatabase()->update($article);
        $this->Posts(1);
    }

    /**
     * @param $id
     */
    public function DeletePost($id)
    {
        $database= new Database();
        $article= $database->find(Article::class, $id);
        $database->delete($article);
        $this->render('admin.html.twig', array());
    }

    /**
     * @param $id
     */
    public function DeleteComment($id)
    {
        echo "DeleteComment ".$id;
    }

}