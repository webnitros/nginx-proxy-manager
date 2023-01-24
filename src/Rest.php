<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.01.2023
 * Time: 11:12
 */

namespace App;

use App\Helpers\RequestClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class Rest
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /* @var \GuzzleHttp\Psr7\Response $Response */
    private $Response;

    public function __construct(Token $token)
    {
        $this->client = new Client([
            'base_uri' => getenv('API_URL'),
            'headers' => [
                'Authorization' => 'Bearer ' . $token->get(),
                'Accept' => 'application/json'
            ],
            'http_errors' => true, // Для выброса статуса страницы 400
            'timeout' => 30, // Таймаут для содинения
            'connect_timeout' => 15, // Время ожидания для конекта
        ]);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $params
     * @return mixed
     */
    public function sendRequest(string $method, string $uri, $params = [])
    {
        $options = [
            'json' => $params
        ];
        $method = mb_strtolower($method);

        if ($method == 'get' && !empty($params)) {
            $uri .= '?' . Psr7\build_query($params);
        }

        $this->Response = $this->client()->{$method}($uri, $options);
        $body = $this->Response->getBody()->getContents();
        return \GuzzleHttp\json_decode($body, true, 512);
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->Response->getStatusCode();
    }

}
