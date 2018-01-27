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
        $nbrPerPage=10;
        $nbrArticles=$this->getDatabase()->countPosts(Article::class);
        $nbrPages= ceil($nbrArticles / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $postsOnPage= $this->getDatabase()->findPerPage(Article::class, $firstEnter, $nbrPerPage);
        $this->render('admin.html.twig', ["postsOnPage"=>$postsOnPage, "nbrPages"=>$nbrPages]);
    }

    /**
     * @param $id
     */
    public function Post($id)
    {
        $article= $this->getDatabase()->find(Article::class, $id);
        $reportComment= $this->getDatabase()->findall(Commentaire::class, ["signale"=>'1', "article_id"=>$id]);
        $this->render('adminArticle.html.twig', ["article"=>$article, "reportComment"=>$reportComment]);
    }

    public function Comments($page)
    {
        $nbrPerPage=10;
        $nbrSignalComment=$this->getDatabase()->countComments(Commentaire::class, "1");
        $nbrPages= ceil($nbrSignalComment / $nbrPerPage);
        $firstEnter=($page-1)*$nbrPerPage;
        $reportComment= $this->getDatabase()->findComment($firstEnter, $nbrPerPage, "1");
        $this->render('adminComments.html.twig', ["reportComment"=>$reportComment, "nbrPages"=>$nbrPages]);
    }

    /**
     *
     */
    public function NewPost()
    {
        $erreur=[];

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if (!isset($_POST["titre"]) || empty($_POST["titre"])){
                $erreur["titre"]= "Veuillez saisir un titre";
            }
            if (!isset($_POST["auteur"]) || empty($_POST["auteur"])){
                $erreur["auteur"]= "Veuillez saisir un auteur";
            }
            if (!isset($_POST["article"]) || empty($_POST["article"])){
                $erreur["article"]= "Veuillez saisir un article";
            }
            if (!isset($_POST["ordre"]) || empty($_POST["ordre"])){
                $erreur["ordre"]= "Veuillez saisir un numero d'article";
            }
            if (count($erreur)==0){
                $database = new Database();
                $article = new Article($database);
                $article->setTitre($_POST['titre']);
                $article->setAuteur($_POST['auteur']);
                $article->setArticle($_POST['article']);
                $article->setOrdre($_POST['ordre']);

                if ($this->getDatabase()->findall(Article::class, ['ordre' => $article->getOrdre()]) == true) {
                    $this->getDatabase()->changeOrdre($article->getOrdre(), '+');
                }
                $this->getDatabase()->insert($article);
                $this->redirect('/admin/articles?page=1');
            }
        }
        $route="NewArticle";
        $this->render('NewPost.html.twig', ["article"=>$_POST, "erreur"=>$erreur, "route"=>$route]);
    }

    /**
     * @param $id
     */
    public function NewUpdatePost($id)
    {
        $article = $this->getDatabase()->find(Article::class, $id );
        $erreur=[];

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if (!isset($_POST["titre"]) || empty($_POST["titre"])){
                $erreur["titre"]= "Veuillez saisir un titre";
            }
            if (!isset($_POST["auteur"]) || empty($_POST["auteur"])){
                $erreur["auteur"]= "Veuillez saisir un auteur";
            }
            if (!isset($_POST["article"]) || empty($_POST["article"])){
                $erreur["article"]= "Veuillez saisir un article";
            }
            if (!isset($_POST["ordre"]) || empty($_POST["ordre"])){
                $erreur["ordre"]= "Veuillez saisir un numero d'article";
            }
            if (count($erreur)==0){
                $originalArticle= clone $article;
                $article->setTitre($_POST['titre']);
                $article->setAuteur($_POST['auteur']);
                $article->setArticle($_POST['article']);
                $article->setOrdre($_POST['ordre']);

                if ($article->getOrdre() > $originalArticle->getOrdre()){
                    $this->getDatabase()->changeOrdreUpdate('-', $originalArticle->getOrdre().'<', '<='.$article->getOrdre());
                }elseif ($article->getOrdre() < $originalArticle->getOrdre()) {
                    $this->getDatabase()->changeOrdreUpdate('+', $originalArticle->getOrdre() . '>', '>=' . $article->getOrdre());
                }
                $this->getDatabase()->update($article);
                $this->redirect('/admin/articles?page=1');
            }
            $article->setTitre($_POST['titre']);
            $article->setAuteur($_POST['auteur']);
            $article->setArticle($_POST['article']);
            $article->setOrdre($_POST['ordre']);
            $route="NewUpdateArticle/".$id;
            $this->render('NewPost.html.twig', ["article"=>$article, "erreur"=>$erreur, "route"=>$route]);
        }
        $route="NewUpdateArticle/".$id;
        $this->render('NewPost.html.twig', ["article"=>$article, "erreur"=>$erreur, "route"=>$route]);
    }

    /**
     * @param $id
     */
    public function DeletePost($id)
    {
        $database= new Database();
        $article= $database->find(Article::class, $id);
        $database->delete($article);
        $this->redirect('/admin/articles?page=1');
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

    public function LogOut ()
    {
        session_start();
        session_unset();
        session_destroy();
        $this->redirect('/');
    }

}