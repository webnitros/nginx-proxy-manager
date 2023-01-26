<?php
/**
 * Created by Andrey Stepanenko.
 * User=> webnitros
 * Date=> 23.01.2023
 * Time=> 11=>12
 */

namespace App\Meta;


use App\Abstracts\Api;
use App\Rest;

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
     * Добавляем SSL сертификат
     * @return $this
     */
    public function ssl(string $email)
    {
        $this->set('ssl_forced', true);
        $this->set('certificate_id', 'new');
        $this->set('meta', [
            'letsencrypt_email' => $email,
            'letsencrypt_agree' => true,
            'dns_challenge' => true,
        ]);
        return $this;
    }

    public function delete()
    {
        return $this->send_delete();
    }

}
