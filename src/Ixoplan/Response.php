<?php

namespace Ixolit\Dislo\CDE\Ixoplan;

use Ixolit\Dislo\CDE\EventEngine\ActionResult;

/**
 * Class Response
 * @package Ixolit\Dislo\CDE\Ixoplan
 */
class Response {

    /**
     * @var bool
     */
    private $success;

    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var int|null 
     */
    private $errorCode;

    /**
     * @var JsonSerializable
     */
    private $content;

    /**
     * Response constructor.
     * @param bool $success
     * @param string $errorMessage
     * @param int $errorCode
     * @param JsonSerializable|array $content
     */
    public function __construct($success=false, $errorMessage=null, $errorCode=null, $content=null)
    {
        $this->success       = $success;
        $this->errorMessage  = $errorMessage;
        $this->errorCode     = $errorCode;
        $this->content          = $content;
    }

    /**
     * @param JsonSerializable $content
     * @return Response
     */
    public static function createWithSuccess($content=null) {
        return new self(true, null, null, $content);
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param JsonSerializable|array $content
     * @return Response
     */
    public static function createWithError($errorCode, $errorMessage, $content=null) {
        return new self(false, $errorCode, $errorMessage, $content);
    }

    /**
     * @return bool
     */
    public function isSuccess(){
        return $this->success;
    }

    /**
     * @return string
     */
    public function getErrorMessage(){
        return $this->errorMessage;
    }

    /**
     * @return int
     */
    public function getErrorCode(){
        return $this->errorCode;
    }

    /**
     * @return JsonSerializable|array
     */
    public function getContent(){
        return $this->content;
    }

    /**
     * @param JsonSerializable|array $content
     * @return $this
     * @throws \Exception
     */
    public function setContent($content) {
        if ($content !== false && @json_encode($content) === false) {
            throw new \Exception("Provided data could not be converted to JSON");
        }
        $this->content = $content;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray() {

        $error = null;
        if ($this->errorMessage) {
            $error = [
                'code' => $this->errorCode ? $this->errorCode : 0,
                'message' => $this->errorMessage,
            ];
        }   

        $array = [
            'success' => $this->success,
            'error' => $error,
            'content' => $this->getContent()
        ];

        return $array;

        return array_filter($array, function($value) {
            return $value !== null;
        });
    }

    /**
     * @return string
     */
    public function __toString(){
        return json_encode($this->toArray());
    }


}