<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31/01/2018
 * Time: 00:28
 */

namespace Lib;

class Manager
{
    protected $database;

    protected $class="";

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    public function find($id)
    {
        $statement = $this->database->query(sprintf("SELECT * FROM %s WHERE id=%s", $this->class::getTable(), $id));
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
        return $statement->fetch();
    }

    public function findAll($criteria = [])
    {
        return $this->database->query(sprintf("SELECT * FROM %s WHERE %s", $this->class::getTable(), implode(" AND ", array_map(function($criterion, $value) {
            return $criterion . "='" . $value."'";
        }, array_keys($criteria), $criteria))))->fetchAll(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
    }



    public function delete($object)
    {
        $this->database->execute(sprintf("DELETE  FROM %s WHERE id=%s", $object::getTable(), $object->getId()));
    }

    public function update($object)
    {
        $columns = [];
        foreach($object->metadata["columns"] as $field=>$option) {
            if ($option["required"]) {
                $columns[] = $field . " = " . "'" . str_replace("'", "''", $object->__get($field)) . "'";
            }
        }
        $this->database->execute(sprintf("UPDATE %s SET %s  WHERE id=%s", $object::getTable(), implode(",", $columns), $object->getId()));

    }

    public function insert($object)
    {
        $columns = [];
        foreach($object->metadata["columns"] as $field=>$option) {
            if($option["required"]) {
                $columns[] = $field . " = " . "'" . str_replace("'", "''", $object->__get($field)) . "'";
            }
        }
        $this->database->execute(sprintf("INSERT INTO %s SET %s", $object::getTable(), implode(",", $columns)));
    }

    public function logInFilter($object)
    {
        $args = array(
            'Id' => FILTER_SANITIZE_SPECIAL_CHARS,
            'Pass' => FILTER_SANITIZE_SPECIAL_CHARS
        );

        return $myInput = filter_var_array($object, $args);
    }

}