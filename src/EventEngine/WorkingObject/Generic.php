<?php

namespace Ixolit\Dislo\CDE\EventEngine\WorkingObject;

/**
 * Class Generic
 * If there is not yet a mapping class defined, an class instance of Generic will be created as data wrapper
 * @package Ixolit\Dislo\CDE\EventEngine\WorkingObject
 */
class Generic extends AbstractWorkingObject  {

    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets an untyped member of this
     *
     * @param string $key the member name to fetch.
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default=null) {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        } else {
            return $default;
        }
    }

    /**
	 * Fetches an untyped member of this EJP object.
	 *
	 * @param string $key   the member name to set
	 * @param mixed  $value the value to set.
	 *
	 * @return $this
	 */
	public function set($key, $value) {
		$this->_data[$key] = $value;
		return $this;
	}

}