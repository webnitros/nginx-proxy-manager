<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.01.2023
 * Time: 10:44
 */

namespace App;


use App\Helpers\RequestClient;
use GuzzleHttp\Client;

class Token
{
    /**
     * @var mixed
     */
    private $token;
    /**
     * @var mixed
     */
    private $expires;

    public function __construct()
    {
        $this->create();
    }

    public function get()
    {
        return $this->token;
    }

    public function expires()
    {
        return $this->expires;
    }

    public function toArray()
    {
        if (!$this->get()) {
            return null;
        }
        return [
            'token' => $this->get(),
            'expires' => $this->expires(),
        ];
    }

    public function create()
    {
        $Client = new Client([
            'base_uri' => getenv('API_URL'),
        ]);

        $ResponseInterface = $Client->post('api/tokens', [
            'json' => [
                'identity' => getenv('API_INENTITY'),
                'secret' => getenv('API_SECRET'),
            ]
        ]);

        $body = $ResponseInterface->getBody()->getContents();
        $array = \GuzzleHttp\json_decode($body, true, 512);
        $token = $array['token'];
        $expires = $array['expires'];
        return $this->setToken($token, $expires);
    }

    public function setToken(string $token, string $expires)
    {
        if ($token) {
            $this->token = $token;
        }
        if ($expires) {
            $this->expires = $expires;
        }
        return $this;
    }
}
