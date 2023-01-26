<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.04.2022
 * Time: 10:38
 */

namespace NginxProxyManager\Abstracts;

use NginxProxyManager\Rest;
use function GuzzleHttp\Psr7\str;

abstract class Api
{
    protected $uri = null;
    protected $method = null;
    protected $data = [];
    protected $default = [];
    /**
     * @var int
     */
    private $id;
    /**
     * @var \NginxProxyManager\Rest
     */
    private $client;


    public function setClient(Rest $rest)
    {
        $this->client = $rest;
        return $this;
    }

    public function send_put($params = null)
    {
        return $this->send('put', $params);
    }

    public function send_post($params = null)
    {
        return $this->send('post', $params);
    }

    public function send_get($params = null)
    {
        return $this->send('get', $params);
    }

    public function send_delete()
    {
        return $this->send('delete');
    }

    private function send($method = null, $params = null)
    {
        $method = $method ?? $this->method();
        $params = $params ?? $this->toArray();
        $this->res = $this->client->sendRequest($method, $this->uri(), $params);
        $status = $this->client->getStatusCode();
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

    public function uri()
    {
        $uri = $this->uri;
        if ($id = $this->getId()) {
            $uri .= '/' . (string)$id;
        }
        return $uri;
    }

    private function method()
    {
        return $this->method;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function fromArray(array $response)
    {
        $this->data = $response;
        return $this;
    }

    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function get(string $key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }


    public function setId(int $int)
    {
        $this->id = $int;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }


    public function save()
    {
        $tmp = $this->toArray();
        $params = [];
        $fields = $this->default;
        foreach ($fields as $k => $v) {
            if (array_key_exists($k, $tmp)) {
                $params[$k] = $tmp[$k];
            }
        }


        if (!$this->getId()) {
            $response = $this->send_post($params);
            $this->setId($response['id']);
        } else {
            $response = $this->send_put($params);
        }

        $this->fromArray($response);
        return $this;
    }
}
