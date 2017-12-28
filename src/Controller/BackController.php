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
    public function Posts()
    {
        $articles= $this->getDatabase()->findAll(Article::class, [""]);
        $this->render('admin.html.twig', ["articles"=>$articles]);
    }

    /**
     * @param $id
     */
    public function Post($id)
    {
        $article= $this->getDatabase()->find(Article::class, $id);
        $reportComment= $this->getDatabase()->findall(Commentaire::class, ["signale"=>'1']);
        $this->render('adminArticle.html.twig', ["article"=>$article, "reportComment"=>$reportComment]);
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
        $database=new Database();
        $newArticle = new Article($database);
        $newArticle->setTitre($_POST['titre']);
        $newArticle->setAuteur($_POST['auteur']);
        $newArticle->setArticle($_POST['article']);
        $newArticle->setOrdre($_POST['ordre']);
        $this->getDatabase()->insert($newArticle);
        $this->redirect('/admin/articles');
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
        $this->redirect('/admin/articles');
    }

    /**
     * @param $id
     */
    public function DeletePost($id)
    {
        $database= new Database();
        $article= $database->find(Article::class, $id);
        $database->delete($article);
        $this->redirect('/admin/articles');
    }

    /**
     * @param $id
     */
    public function DeleteComment($id)
    {
        $database= new Database();
        $comment= $database->find(Commentaire::class, $id);
        $database->delete($comment);
        $this->redirect('/admin/article/'.$comment->getArticleId());
    }

}