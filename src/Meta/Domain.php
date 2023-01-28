<?php
/**
 * Created by Andrey Stepanenko.
 * User=> webnitros
 * Date=> 23.01.2023
 * Time=> 11=>12
 */

namespace NginxProxyManager\Meta;


use NginxProxyManager\Abstracts\Api;
use NginxProxyManager\Rest;

class Domain extends Api
{
    protected $uri = '/api/nginx/proxy-hosts';
    protected $default = [
        "domain_names" => [],
        "forward_scheme" => "http",
        "forward_host" => null,
        "forward_port" => 80,
        "access_list_id" => "0",
        "certificate_id" => 0,
        "ssl_forced" => false,
        "meta" => [
            "letsencrypt_agree" => false,
            "dns_challenge" => false
        ],
        "advanced_config" => "",
        "locations" => [],
        "block_exploits" => false,
        "caching_enabled" => false,
        "allow_websocket_upgrade" => false,
        "http2_support" => false,
        "hsts_enabled" => false,
        "hsts_subdomains" => false
    ];

    public static function create(Rest $rest)
    {
        $Domain = new Domain();
        $Domain->setClient($rest);
        $Domain->fromArray($Domain->default);
        return $Domain;
    }

    public static function object(int $id, Rest $rest)
    {
        $Domain = new Domain();
        $Domain->setClient($rest);
        $Domain->setId($id);
        $Domain->fromArray($Domain->send_get());
        return $Domain;
    }

    public static function search(string $query, Rest $rest)
    {
        $Domain = new Domain();
        $Domain->setClient($rest);
        $response = $Domain->send_get([
            "query" => $query,
            "expand" => 'owner,access_list,certificate',
        ]);
        return $response;
    }


    public function addDomain(string $domain)
    {
        $domains = $this->get('domain_names');
        $domains[] = $domain;
        $domains = array_filter($domains);
        $domains = array_unique($domains);
        $this->set('domain_names', $domains);
        return $this;
    }

    public function forwardHost(string $ip)
    {
        $this->set('forward_host', $ip);
        return $this;
    }

    public function forwardPort(int $port)
    {
        $this->set('forward_port', $port);
        return $this;
    }

    public function forwardScheme(string $scheme)
    {
        if ($scheme !== 'http' && $scheme !== 'https') {
            throw new \Exception('scheme http or https ');
        }
        $this->set('forward_scheme', $scheme);
        return $this;
    }

    /**
     * Создать новый сертификат
     * @return $this
     */
    public function createSsl(string $email)
    {
        $this->set('certificate_id', 'new');
        $this->set('meta', [
            'letsencrypt_email' => $email,
            'letsencrypt_agree' => true,
            'dns_challenge' => false,
        ]);
        return $this;
    }


    /**
     * Прикрепить существующий сертификат
     * @return $this
     */
    public function ssl(string $certificate_id)
    {
        $this->set('certificate_id', $certificate_id);
        return $this;
    }


    /**
     * HSTS является технологией, которая ограничивает открытие сайта из браузера только по протоколу HTTPS.
     * @param bool $value
     * @return $this
     */
    public function hstsEnabled(bool $value = true)
    {
        $this->set('hsts_enabled', $value);
        return $this;
    }

    /**
     * Перенаправление на https
     * @param bool $value
     * @return $this
     */
    public function sslForced(bool $value = true)
    {
        $this->set('ssl_forced', $value);
        return $this;
    }


    public function hstsSubdomains(bool $value = true)
    {
        $this->set('hsts_subdomains', $value);
        return $this;
    }

    /**
     * Протокол HTTP/2 существенно ускоряет открытие сайтов за счет следующих особенностей:
     * соединения: несколько запросов могут быть отправлены через одно TCP-соединение,
     * и ответы могут быть получены в любом порядке.
     * @param bool $value
     * @return $this
     */
    public function http2Support(bool $value = true)
    {
        $this->set('http2_support', $value);
        return $this;
    }

    public function delete()
    {
        return $this->send_delete();
    }

}
