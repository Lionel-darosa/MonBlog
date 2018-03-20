<?php

namespace Manager;

use Model\Article;
use Lib\Manager;

class ArticleManager extends Manager
{
    protected $class = Article::class;

    /**
     * @return array
     */
    public function findLast()
    {
        $statement = $this->database->query("SELECT * FROM articles ORDER BY ordre DESC LIMIT 1");
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
    }

    /**
     * @param $first
     * @param $nbr
     * @return array
     */
    public function findPerPage($first, $nbr)
    {
        $statement= $this->database->query('SELECT * FROM articles ORDER BY ordre LIMIT '. $first. ',' .$nbr.' ');
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
    }

    /**
     * @param $minmaxcolonne
     * @return mixed
     */
    public function findMinMax($minmaxcolonne)
    {
        $statement= $this->database->query(sprintf('SELECT %s  FROM articles ', $minmaxcolonne));
        return $statement->fetch();
    }

    /**
     * @param $ordre
     */
    public function changeOrdre($ordre, $signe)
    {
        $this->database->execute(sprintf("UPDATE articles SET ordre=ordre%s1 WHERE ordre>= %s",$signe, $ordre));
    }

    /**
     * @param $signe
     * @param $first
     * @param $second
     */
    public function changeOrdreUpdate($signe, $first, $second)
    {
        $this->database->execute(sprintf("UPDATE articles SET ordre=ordre%s1 WHERE %s ordre AND ordre %s", $signe, $first, $second));
    }

    /**
     * @return mixed
     */
    public function countPosts()
    {
        $req=$this->database->query("SELECT COUNT(*) AS content FROM articles");
        $total= $req->fetch();
        return $total['content'];
    }

    /**
     * @param $ordre
     * @return array
     */
    public function nextPost($ordre)
    {
        $statement=$this->database->query("SELECT id FROM articles WHERE ordre > ".$ordre." ORDER BY ordre LIMIT 0,1");
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
    }

    /**
     * @param $ordre
     * @return array
     */
    public function previousPost($ordre)
    {
        $statement=$this->database->query("SELECT id FROM articles WHERE ordre < ".$ordre." ORDER BY ordre DESC LIMIT 0,1");
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
    }

}