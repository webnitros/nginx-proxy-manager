<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.04.2022
 * Time: 10:38
 */

namespace App\Abstracts;

use App\Rest;

abstract class Api
{
    protected $uri = null;
    protected $method = null;

    public function __construct(Rest $rest)
    {
        $this->rest = $rest;
    }

    public function send()
    {
        $this->res = $this->rest->sendRequest($this->method(), $this->uri(), $this->toArray());
        $status = $this->rest->getStatusCode();

        switch ($status) {
            case 201:
            case 200:
                break;
            default:
                throw new \Exception('Error status code ' . $status . print_r($this->res, 1));
        }
        return $this->response();
    }

    private function response()
    {
        return $this->res;
    }

    private function uri()
    {
        return $this->uri;
    }

    private function method()
    {
        return $this->method;
    }

    public function toArray()
    {
        return [];
    }

}
