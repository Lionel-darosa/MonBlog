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

    public function find($class, $id)
    {
        $statement = $this->pdo->query(sprintf("SELECT * FROM %s WHERE id=%s", $class::getTable(), $id));
        $statement->setFetchMode(\PDO::FETCH_CLASS, $class, ["database"=>&$this]);
        return $statement->fetch();
    }

    public function findAll($class, $criteria = [])
    {
        return $this->pdo->query(sprintf("SELECT * FROM %s WHERE %s", $class::getTable(), implode(" AND ", array_map(function($criterion, $value) {
            return $criterion . "='" . $value."'";
        }, array_keys($criteria), $criteria))))->fetchAll(\PDO::FETCH_CLASS, $class, ["database" => &$this]);
    }

    public function findPerPage($class, $first, $nbr)
    {
        $statement= $this->pdo->query(sprintf('SELECT * FROM %s ORDER BY ordre DESC LIMIT '.$first.','.$nbr.'', $class::getTable()));
        return $statement->fetchAll();

    }

    public function delete($object)
    {
        $this->pdo->exec(sprintf("DELETE  FROM %s WHERE id=%s", $object::getTable(), $object->getId()));
    }

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

    public function insert( $object)
    {
        $columns = [];
        foreach($object->fields as $field=>$option) {
            if ($option != "pk" && $option != "default") {
                $columns[] = $field . " = " . "'" . str_replace("'", "''", $object->__get($field)) . "'";
            }
        }

        $this->pdo->exec(sprintf("INSERT INTO %s SET %s", $object::getTable(), implode(",", $columns)));
    }

    public function count($class)
    {
        $req=$this->pdo->query(sprintf("SELECT COUNT(*) AS content FROM %s", $class::getTable()));
        $total= $req->fetch();
        return $total['content'];
    }
}