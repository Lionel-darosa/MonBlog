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
        $this->render('admin.html.twig', array());
    }

    /**
     * @param $id
     */
    public function Post($id)
    {
        $database = new Database();
        $article= $database->find(Article::class, $id);
        $article->getCommentaire();
        $this->render('adminArticle.html.twig', ["article"=>$article]);
    }

    /**
     *
     */
    public function NewPost($id)
    {

    }

    /**
     * @param $id
     */
    public function UpdatePost($id)
    {
        echo "UpdatePost ". $id;
    }

    /**
     * @param $id
     */
    public function DeletePost($id)
    {
        echo "DeletePost ".$id;
    }

    /**
     * @param $id
     */
    public function DeleteComment($id)
    {
        echo "DeleteComment ".$id;
    }

}