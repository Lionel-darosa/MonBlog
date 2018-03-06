<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:40
 */

namespace Model;

use Lib\Entity;
use Manager\CommentManager;

class Article extends Entity
{
    public $metadata = [
        "table" => "article",
        "primary_key" => "id",
        "columns" => [
            "id" => [
                "required" => false
            ],
            "titre" => [
                "required" => true,
                "message" => "Veuillez saisir le titre de l'article"
            ],
            "article" => [
                "required" => true,
                "message" => "Veuillez saisir le contenu de l'article"
            ],
            "ordre" => [
                "required" => true,
                "message" => "Veuillez saisir l'ordre de l'article"
            ],
            "auteur" => [
                "required" => true,
                "message" => "Veuillez saisir l'auteur de l'article"
            ],
            "date_article" => [
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
    protected $titre;

    /**
     * @var string
     */
    protected $article;

    /**
     * @var int
     */
    protected $ordre;

    /**
     * @var string
     */
    protected $auteur;

    protected $commentaires;

    /**
     * @var \DateTime
     */
    protected $date_article;

    /**
     * @return string
     */
    public static function getTable()
    {
        return "articles";
    }

    /**
     * @return mixed
     */
    public function getCommentaires()
    {
        if ($this->commentaires === null) {

            $this->commentaires = $this->database->getManager(CommentManager::class)->findAll(["article_id" => $this->id]);
        }
        return $this->commentaires;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param $auteur
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }

    /**
     * @return bool|\DateTime
     */
    public function getDate_article()
    {
        return \DateTime::createFromFormat("Y-m-d H:i:s", $this->date_article);
    }

    /**
     * @param $date_article
     */
    public function setDate_article($date_article)
    {
        $this->date_article = $date_article->format("Y-m-d H:i:s");
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