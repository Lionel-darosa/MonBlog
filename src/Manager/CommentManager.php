<?php

namespace Manager;

use Model\Commentaire;
use Lib\Manager;

/**
 * Class CommentManager
 * @package Manager
 */
class CommentManager extends Manager
{
    /**
     * @var string
     */
    protected $class = Commentaire::class;

    /**
     * @param $first
     * @param $nbr
     * @param $id
     * @return array
     */
    public function findPerPageDesc($first, $nbr, $id)
    {
        $statement= $this->database->query(sprintf('SELECT * FROM commentaires WHERE article_id=%s ORDER BY date_Comment DESC LIMIT '.$first.','.$nbr.'', $id));
        return $statement->fetchAll();
    }

    /**
     * @param $first
     * @param $nbr
     * @param $report
     * @return mixed
     */
    public function findComment($first, $nbr, $report)
    {
        $statement= $this->database->query(sprintf('SELECT * FROM commentaires WHERE signale=%s ORDER BY date_Comment DESC LIMIT '.$first.','.$nbr.'', $report));
        return $statement->fetchAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function countCommentsArticle($id)
    {
        $req=$this->database->query(sprintf("SELECT COUNT(*) AS content FROM commentaires WHERE article_id=%s", $id));
        $total= $req->fetch();
        return $total['content'];
    }

    /**
     * @param $signale
     * @return mixed
     */
    public function countComments($signale)
    {
        $req=$this->database->query(sprintf("SELECT COUNT(*) AS content FROM commentaires WHERE signale=%s", $signale));
        $total= $req->fetch();
        return $total['content'];
    }

    /**
     * @param $object
     * @param $newComment
     * @param $id
     * @return mixed
     */
    public function formDataComment($object, $newComment, $id)
    {
        $newComment->setArticleId($id);
        $newComment->setSignale('0');
        $newComment->hydrate($object);
        $formData['newComment'] = $newComment;
        $formData['erreur'] = $newComment->valid();
        if (count($formData['erreur'])==0) {
            $this->insert($formData['newComment']);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/article/'.$id.'?page=1');
        }
        return $formData;
    }
}