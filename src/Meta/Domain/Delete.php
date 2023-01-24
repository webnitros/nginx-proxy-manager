<?php
/**
 * Created by Andrey Stepanenko.
 * User=> webnitros
 * Date=> 23.01.2023
 * Time=> 11=>12
 */

namespace App\Meta\Domain;


use App\Abstracts\Api;

class Delete extends Api
{
    protected $method = 'DELETE';

    public function setId(int $int)
    {
        $this->uri = '/api/nginx/proxy-hosts/' . $int;
        return $this;
    }
}
