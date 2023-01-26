<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.03.2021
 * Time: 22:49
 */

namespace Tests;

use NginxProxyManager\Rest;
use NginxProxyManager\Token;
use Mockery\Adapter\Phpunit\MockeryTestCase;

abstract class TestCase extends MockeryTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function token()
    {
        return new Token(getenv('NGINX_API_URL'), getenv('NGINX_API_INENTITY'), getenv('NGINX_API_SECRET'));
    }

    public function client()
    {
        return new Rest($this->token());
    }
}
