<?php

namespace Ixolit\Dislo\CDE\PSR7;

use Ixolit\CDE\PSR7\Response;
use Ixolit\Dislo\WorkingObjects\WorkingObject;

/**
 * Class JsonResponse
 *
 * @package Ixolit\CDE\PSR7
 */
class JsonResponse extends Response {

    const STATUS_CODE = 200;
    const VERSION = '1.1';

    /**
     * @param array $data
     * @param int $status
     */
    public function __construct($data, $status = self::STATUS_CODE) {
        if (\is_array($data)) {
            $body = $this->convert($data);
        } elseif (empty($data)) {
            $body = '';
        } else {
            $body = $data;
        }

        $body = \json_encode($body);

        parent::__construct(
            $status,
            [
                'content-type' => [
                    'application/json'
                ]
            ],
            $body,
            self::VERSION
        );
    }

    /**
     * @param array $data
     *
     * @return array|null
     */
    protected function convert($data) {
        if (\is_null($data)) {
            return null;
        }

        $arrayData = array();
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $arrayData[$key] = self::convert($value);
            } elseif ($value instanceof WorkingObject) {
                $arrayData[$key] = $value->toArray();
            } else {
                $arrayData[$key] = $value;
            }
        }

        return $arrayData;
    }

}