<?php
/**
 * Created by Andrey Stepanenko.
 * User=> webnitros
 * Date=> 23.01.2023
 * Time=> 11=>12
 */

namespace App\Meta\Domain;


use App\Abstracts\Api;

class Add extends Api
{
    protected $uri = '/api/nginx/proxy-hosts';
    protected $method = 'post';


    private $domains = [];
    private $scheme = "http";
    private $forward_hostname = null;
    private $forward_port = 80;
    private $ssl_forced = false;
    private $meta = [
        "letsencrypt_agree" => false,
        "dns_challenge" => false
    ];
    private $certificate_id = 0;
    private $http2_support = false;
    private $hsts_enabled = false;
    private $hsts_subdomains = false;

    public function addDomain(string $domain)
    {
        $this->domains[] = $domain;
        return $this;
    }

    public function forwardHostname(string $ip)
    {
        $this->forward_hostname = $ip;
        return $this;
    }

    public function forwardPort(int $port)
    {
        $this->forward_port = $port;
        return $this;
    }

    public function forwardScheme(string $scheme)
    {
        if ($scheme !== 'http' && $scheme !== 'https') {
            throw new \Exception('scheme http or https ');
        }
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * Добавляем SSL сертификат
     * @return $this
     */
    public function ssl(string $email)
    {
        $this->ssl_forced = true;
        $this->certificate_id = 'new';
        $this->meta = [
            'letsencrypt_email' => $email,
            'letsencrypt_agree' => true,
            'dns_challenge' => true,
        ];
        return $this;
    }

    public function toArray()
    {
        return [
            "domain_names" => $this->domains,
            "forward_scheme" => $this->scheme,
            "forward_host" => $this->forward_hostname,
            "forward_port" => $this->forward_port,
            "access_list_id" => "0",
            "certificate_id" => $this->certificate_id,
            "ssl_forced" => $this->ssl_forced,
            "meta" => $this->meta,
            "advanced_config" => "",
            "locations" => [],
            "block_exploits" => false,
            "caching_enabled" => false,
            "allow_websocket_upgrade" => false,
            "http2_support" => $this->http2_support,
            "hsts_enabled" => $this->hsts_enabled,
            "hsts_subdomains" => $this->hsts_subdomains
        ];
    }


}
