<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:42
 */

namespace Lib;

abstract class Entity
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function hydrate($_post)
    {
        foreach($_post as $property => $value) {
            $this->{$property} = $value;
        }
    }

    public function valid()
    {
        $errors = [];
        foreach($this->metadata["columns"] as $column => $validation) {
            if($validation["required"] && $this->{$column} == "") {
                $errors[$column] = $validation["message"];
            }
        }
        return $errors;
    }

    public abstract static function getTable();
}