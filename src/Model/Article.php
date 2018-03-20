<?php

namespace Model;

use Lib\Entity;
use Manager\ArticleManager;
use Manager\CommentManager;

/**
 * Class Article
 * @package Model
 */
class Article extends Entity
{
    /**
     * @var array
     */
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
        ],
        "args" => [
                "titre" => FILTER_SANITIZE_SPECIAL_CHARS,
                "article" => FILTER_UNSAFE_RAW,
                "ordre" => [
                    "filter" => FILTER_SANITIZE_NUMBER_INT,
                    "options" => [
                        'min_range' => 0
                    ],
                ],
                "auteur" => FILTER_SANITIZE_SPECIAL_CHARS
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

    /**
     * @var
     */
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

    /**
     * @return mixed
     */
    public function getFirstPost()
    {
        $first = $this->database->getManager(ArticleManager::class)->findMinMax("MIN(ordre)");
        return $first['0'];
    }

    /**
     * @return mixed
     */
    public function getLastPost()
    {
        $last = $this->database->getManager(ArticleManager::class)->findMinMax("MAX(ordre)");
        return $last['0'];
    }

    /**
     * @return mixed
     */
    public function getNextPost()
    {
        $next = $this->database->getManager(ArticleManager::class)->nextPost($this->getOrdre());
        return $next['0']->getId();
    }

    /**
     * @return mixed
     */
    public function getPreviousPost()
    {
        $previous = $this->database->getManager(ArticleManager::class)->previousPost($this->getOrdre());
        return $previous['0']->getId();
    }

    /**
     * @return mixed
     */
    public function getNbrComment()
    {
        return $this->database->getManager(CommentManager::class)->countCommentsArticle($this->getId());
    }

}