<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01/11/2017
 * Time: 14:42
 */

namespace Model;

abstract class Entity
{
    protected $database;

    public function __construct(Database &$database)
    {
        $this->database = $database;
    }

    public abstract static function getTable();
}