<?php

namespace Ixolit\Dislo\CDE\EventEngine;

/**
 * Class Thread
 * @package Ixolit\Dislo\CDE\EventEngine
 */
class Thread {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * Thread constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id   = $id;
        $this->name = $name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $array
     * @return Thread
     */
    public static function fromArray(array $array) {
        return new self($array['id'], $array['name']);
    }

}