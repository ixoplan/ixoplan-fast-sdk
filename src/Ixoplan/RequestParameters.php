<?php

namespace Ixolit\Dislo\CDE\Ixoplan;

/**
 * Class RequestParameters
 * @package Ixolit\Dislo\CDE\Ixoplan
 */
class RequestParameters
{

    /**
     * @var array
     */
    private $parameters = array();

    /**
     * RequestParameters constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters=array())
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->parameters;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!array_key_exists($name, $this->parameters)){
            throw new \Exception("property $name not defined!");
        }
        return $this->parameters[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public function getOptional($name, $default=null) {
        if (!array_key_exists($name, $this->parameters)) {
            return $default;
        }
        return $this->parameters[$name];
    }


}