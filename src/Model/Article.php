<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:40
 */

namespace Model;

class Article extends Entity
{
    public $fields = [
        "id"=>"pk",
        "titre"=>"",
        "article"=>"",
        "ordre"=>"",
        "auteur"=>"",
        "date_article"=>"default"
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $titre;

    /**
     * @var string
     */
    private $article;

    /**
     * @var int
     */
    private $ordre;

    /**
     * @var string
     */
    private $auteur;

    private $commentaires;

    /**
     * @var \DateTime
     */
    private $date_article;

    public static function getTable()
    {
        return "articles";
    }

    public function getCommentaire()
    {
        if($this->commentaires === null) {
            $this->commentaires = $this->database->findAll(Commentaire::class, ["article_id" => $this->id]);
        }
        return $this->commentaires;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function setArticle($article)
    {
        $this->article = $article;
    }

    public function getOrdre()
    {
        return $this->ordre;
    }

    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    public function getAuteur()
    {
        return $this->auteur;
    }

    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }

    public function getDate_article()
    {
        return \DateTime::createFromFormat("Y-m-d H:i:s", $this->date_article);
    }

    public function setDate_article($date_article)
    {
        $this->date_article = $date_article->format("Y-m-d H:i:s");
    }

    public function __get($name)
    {
        return $this->$name;
    }
}