<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:40
 */

namespace Model;

class Database
{
    protected $pdo;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        try
        {
            $this->pdo = new \PDO("mysql:dbname=blog;host=localhost", "root", "", array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"'));
        }
        catch (\Exception $e)
        {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * @param $class
     * @param $id
     * @return mixed
     */
    public function find($class, $id)
    {
        $statement = $this->pdo->query(sprintf("SELECT * FROM %s WHERE id=%s", $class::getTable(), $id));
        $statement->setFetchMode(\PDO::FETCH_CLASS, $class, ["database"=>&$this]);
        return $statement->fetch();
    }

    /**
     * @param $class
     * @return array
     */
    public function findLast($class)
    {
        $statement = $this->pdo->query(sprintf("SELECT * FROM %s ORDER BY ordre DESC LIMIT 1", $class::getTable()));
        return $statement->fetchAll();
    }

    /**
     * @param $class
     * @param array $criteria
     * @return array
     */
    public function findAll($class, $criteria = [])
    {
        return $this->pdo->query(sprintf("SELECT * FROM %s WHERE %s", $class::getTable(), implode(" AND ", array_map(function($criterion, $value) {
            return $criterion . "='" . $value."'";
        }, array_keys($criteria), $criteria))))->fetchAll(\PDO::FETCH_CLASS, $class, ["database" => &$this]);
    }

    public function findMinMax($class, $minmaxcolonne)
    {
        $statement= $this->pdo->query(sprintf('SELECT %s FROM %s ', $minmaxcolonne, $class::getTable()));
        return $statement->fetch();
    }

    /**
     * @param $class
     * @param $first
     * @param $nbr
     * @return array
     */
    public function findPerPage($class, $first, $nbr)
    {
        $statement= $this->pdo->query(sprintf('SELECT * FROM %s ORDER BY ordre LIMIT '. $first. ',' .$nbr.'', $class::getTable()));
        return $statement->fetchAll();

    }

    /**
     * @param $class
     * @param $first
     * @param $nbr
     * @param $id
     * @return array
     */
    public function findPerPageDesc($class, $first, $nbr, $id)
    {
        $statement= $this->pdo->query(sprintf('SELECT * FROM %s WHERE article_id=%s ORDER BY date_Comment DESC LIMIT '.$first.','.$nbr.'', $class::getTable(), $id));
        return $statement->fetchAll();

    }

    public function findComment($first, $nbr, $report)
    {
        $statement= $this->pdo->query(sprintf('SELECT * FROM commentaires WHERE signale=%s ORDER BY date_Comment DESC LIMIT '.$first.','.$nbr.'', $report));
        return $statement->fetchAll();
    }

    /**
     * @param $object
     */
    public function delete($object)
    {
        $this->pdo->exec(sprintf("DELETE  FROM %s WHERE id=%s", $object::getTable(), $object->getId()));
    }

    /**
     * @param $object
     */
    public function update($object)
    {
        $columns = [];
        foreach($object->fields as $field=>$option) {
            if ($option != "pk" && $option != "default") {
                $columns[] = $field . " = " . "'" . str_replace("'", "''", $object->__get($field)) . "'";
            }
        }
        $this->pdo->exec(sprintf("UPDATE %s SET %s  WHERE id=%s", $object::getTable(), implode(",", $columns), $object->getId()));

    }

    /**
     * @param $object
     */
    public function insert($object)
    {
        $columns = [];
        foreach($object->fields as $field=>$option) {
            if ($option != "pk" && $option != "default") {
                $columns[] = $field . " = " . "'" . str_replace("'", "''", $object->__get($field)) . "'";
            }
        }
        $this->pdo->exec(sprintf("INSERT INTO %s SET %s", $object::getTable(), implode(",", $columns)));
    }

    /**
     * @param $ordre
     */
    public function changeOrdre($ordre, $signe)
    {
        $this->pdo->exec(sprintf("UPDATE articles SET ordre=ordre%s1 WHERE ordre>= %s",$signe, $ordre));
    }

    /**
     * @param $signe
     * @param $first
     * @param $second
     */
    public function changeOrdreUpdate($signe, $first, $second)
    {
        $this->pdo->exec(sprintf("UPDATE articles SET ordre=ordre%s1 WHERE %s ordre AND ordre %s", $signe, $first, $second));
    }

    /**
     * @param $class
     * @return mixed
     */
    public function countPosts($class)
    {
        $req=$this->pdo->query(sprintf("SELECT COUNT(*) AS content FROM %s", $class::getTable()));
        $total= $req->fetch();
        return $total['content'];
    }

    /**
     * @param $class
     * @param $id
     * @return mixed
     */
    public function countCommentsArticle($class, $id)
    {
        $req=$this->pdo->query(sprintf("SELECT COUNT(*) AS content FROM %s WHERE article_id=%s", $class::getTable(), $id));
        $total= $req->fetch();
        return $total['content'];
    }

    public function countComments($class, $signale)
    {
        $req=$this->pdo->query(sprintf("SELECT COUNT(*) AS content FROM %s WHERE signale=%s", $class::getTable(), $signale));
        $total= $req->fetch();
        return $total['content'];
    }
}