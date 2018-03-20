<?php

namespace Lib;

/**
 * Class Manager
 * @package Lib
 */
class Manager
{
    /**
     * @var Database
     */
    protected $database;

    /**
     * @var string
     */
    protected $class="";

    /**
     * Manager constructor.
     * @param Database $database
     */
    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $statement = $this->database->query(sprintf("SELECT * FROM %s WHERE id=%s", $this->class::getTable(), $id));
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
        return $statement->fetch();
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function findAll($criteria = [])
    {
        return $this->database->query(sprintf("SELECT * FROM %s WHERE %s", $this->class::getTable(), implode(" AND ", array_map(function($criterion, $value) {
            return $criterion . "='" . $value."'";
        }, array_keys($criteria), $criteria))))->fetchAll(\PDO::FETCH_CLASS, $this->class, ["database" => &$this->database]);
    }

    /**
     * @param $object
     */
    public function delete($object)
    {
        $this->database->execute(sprintf("DELETE  FROM %s WHERE id=%s", $object::getTable(), $object->getId()));
    }

    /**
     * @param $object
     */
    public function update($object)
    {
        $columns = [];
        foreach ($object->metadata["columns"] as $field=>$option) {
            if ($option["required"]) {
                $columns[] = $field . " = " . "'" . str_replace("'", "''", $object->__get($field)) . "'";
            }
        }
        $this->database->execute(sprintf("UPDATE %s SET %s  WHERE id=%s", $object::getTable(), implode(",", $columns), $object->getId()));

    }

    /**
     * @param $object
     */
    public function insert($object)
    {
        $columns = [];
        foreach ($object->metadata["columns"] as $field=>$option) {
            if ($option["required"]) {
                $columns[] = $field . " = " . "'" . str_replace("'", "''", $object->__get($field)) . "'";
            }
        }
        $this->database->execute(sprintf("INSERT INTO %s SET %s", $object::getTable(), implode(",", $columns)));
    }
}