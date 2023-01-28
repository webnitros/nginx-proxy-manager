<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.01.2023
 * Time: 10:44
 */

namespace NginxProxyManager;


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
    /**
     * @var string
     */
    private $url;
    private $login;
    private $password;

    public function __construct(string $url, string $login, string $password)
    {
        $this->url = $url;
        $this->login = $login;
        $this->password = $password;
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
            'base_uri' => $this->url,
            'verify' => false,
            'timeout' => 25.0,
            'connect_timeout' => 5
        ]);

        $ResponseInterface = $Client->post('api/tokens', [
            'json' => [
                'identity' => $this->login,
                'secret' => $this->password,
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
