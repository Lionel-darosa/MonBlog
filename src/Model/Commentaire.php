<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:40
 */

namespace Model;

use Lib\Entity;

class Commentaire extends Entity
{
    public $metadata = [
        "table" => "commentaire",
        "primary_key" => "id",
        "columns" => [
            "id" => [
                "required" => false
            ],
            "pseudo" => [
                "required" => true,
                "message" => "Veuillez saisir un pseudo"
            ],
            "commentaire" => [
                "required" => true,
                "message" => "Veuillez saisir un commentaire"
            ],
            "article_id" => [
                "required" => true
            ],
            "signale" => [
                "required" => true
            ],
            "date_Comment" => [
                "required" => false
            ]
        ]
    ];

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $pseudo;

    /**
     * @var string
     */
    protected $commentaire;

    /**
     * @var int
     */
    protected $article_id;

    /**
     * @var boolean
     */
    protected $signale;

    protected $date_Comment;

    protected $article;

    /**
     * @return bool
     */
    public function isSignale()
    {
        return $this->signale;
    }

    /**
     * @param bool $signale
     */
    public function setSignale($signale)
    {
        $this->signale = $signale;
    }


    /**
     * @return string
     */
    public static function getTable()
    {
        return "commentaires";
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        if($this->article === null) {
            $this->article = $this->database->find(Article::class, $this->article_id);
        }
        return $this->article;
    }

    /**
     * @return int
     */
    public function getArticleId()
    {
        return $this->article_id;
    }

    /**
     * @param $articleId
     */
    public function setArticleId($articleId)
    {
        $this->article_id = $articleId;
    }

    /**
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param $pseudo
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return bool|\DateTime
     */
    public function getDate_Comment()
    {
        return \DateTime::createFromFormat("Y-m-d H:i:s", $this->date_Comment);
    }

    /**
     * @param $date_Comment
     */
    public function setDate_Comment($date_Comment)
    {
        $this->date_Comment = $date_Comment->format("Y-m-d H:i:s");
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name;
    }
}