<?php


namespace Ixolit\Dislo\CDE\Ixoplan;

/**
 * Class Request
 * @package Ixolit\Dislo\CDE\Ixoplan
 */
class Request {

    /**
     * @var string
     */
    private $path;

    /**
     * @var RequestParameters
     */
    private $parameters;

    /**
     * Request constructor.
     * @param string $path
     * @param RequestParameters $parameters
     */
    public function __construct($path, RequestParameters $parameters)
    {
        $this->path = $path;
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return RequestParameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $request
     * @return Request
     */
    public static function createFromRequestArray($request) {

        if (empty($request['path'])) {
            throw new \Exception('Invalid request. Path not specified!');
        }

        if (!empty($request['parameters']) && !is_array($request['parameters'])) {
            throw new \Exception('Invalid request. Parameters have to be of type array!');
        }

        return new self($request['path'], new RequestParameters(!empty($request['parameters']) ? $request['parameters'] : []));

    }

    /**
     * @return array
     */
    public function toArray() {
        return [
            'path' => $this->path,
            'parameters' => $this->parameters->toArray()
        ];
    }



}