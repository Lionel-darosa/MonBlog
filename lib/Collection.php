<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/10/17
 * Time: 18:04
 */

namespace Lib;

class Collection implements \Countable, \Iterator
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var int
     */
    private $position = 0;

    /**
     * Collection constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    /**
     * Inverser les valeurs du tableau
     * return Collection;
     */
    public function reverse()
    {
        return new Collection(array_reverse($this->data));
    }

    /**
     * Récupérer un tableau contenant les clés du table $this->data
     * return Collection;
     */
    public function keys()
    {
        return new Collection(array_keys($this->data));
    }

    /**
     * Effectuer un traitement sur chaque entrée de mon table
     * @param $callback
     * @return $this
     */
    public function walk($callback)
    {
        foreach($this->data as &$value) {
            $callback($value);
        }

        return $this;
    }

    /**
     * Retourner seulement les valeurs que l'on cherche via la fonction de callback
     * return Collection;
     */
    public function filter($callback)
    {
        /* Alternative
            $newData = [];
            foreach($this->data as $data) {
                if($callback($data)) {
                    $newData[] = $data;
                }
            }
            return new Collection($newData);
        */
        return new Collection(array_values(array_filter($this->data, $callback)));
    }

    /**
     * Retourner un tableau ou l'on appliquera la fonction de callback sur chaque élément
     * return Collection;
     */
    public function map($callback)
    {
        return new Collection(array_map($callback, $this->data));
    }

    /**
     * Trier le tableau selon le callback
     * Voir la documentation de la fonciton usort
     *
     * return Collection;
     */
    public function sort($callback) {}

    /**
     * @param $value
     * @return $this
     */
    public function add($value)
    {
        $this->data[] = $value;

        return $this;
    }

    /**
     * @param $position
     * @return $this
     */
    public function remove($position)
    {
        array_splice($this->data, $position, 1);

        return $this;
    }

    /**
     * @param $position
     * @param $newValue
     * @return $this
     */
    public function set($position, $newValue)
    {
        $this->data[$position] = $newValue;

        return $this;
    }

    /**
     * @param $position
     * @return mixed
     */
    public function get($position)
    {
        return $this->data[$position];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->position];
    }

    /**
     * Set position to the next one
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    /**
     * Set position to 0
     */
    public function rewind()
    {
        $this->position = 0;
    }

    public function first()
    {
        return $this->data[0];
    }
}