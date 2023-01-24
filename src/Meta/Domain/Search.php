<?php
/**
 * Created by Andrey Stepanenko.
 * User=> webnitros
 * Date=> 23.01.2023
 * Time=> 11=>12
 */

namespace App\Meta\Domain;


use App\Abstracts\Api;

class Search extends Api
{
    protected $uri = '/api/nginx/proxy-hosts';
    protected $method = 'get';

    public function query(string $query)
    {
        $this->query = $query;
        return $this;
    }

    public function toArray()
    {
        return [
            "query" => $this->query,
            "expand" => 'owner,access_list,certificate',
        ];
    }


}
