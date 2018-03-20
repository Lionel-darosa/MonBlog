<?php

namespace Lib;

/**
 * Class Database
 * @package Lib
 */
class Database
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var array
     */
    private $instancesManager = [];

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
     * @param null $class
     * @return mixed
     */
    public function getManager($class = null)
    {
        $class = $class ?? Manager::class;
        $this->instancesManager[$class] = $this->instancesManager[$class] ?? new $class($this);
        return $this->instancesManager[$class];
    }

    /**
     * @param $sql
     */
    public function execute($sql)
    {
        $this->pdo->exec($sql);
    }

    /**
     * @param $sql
     * @return \PDOStatement
     */
    public function query($sql)
    {
        return $this->pdo->query($sql);
    }
}