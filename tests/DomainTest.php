<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.01.2023
 * Time: 11:34
 */

namespace Tests;

use App\Meta\Domain\Add;
use App\Meta\Domain\Delete;
use App\Meta\Domain\Get;
use App\Meta\Domain\Search;
use App\Rest;

class DomainTest extends TestCase
{

    public function testAddDomain()
    {
        $Domain = new Add($this->client());
        $Domain
            ->addDomain('site.ru')
            ->forwardHostname('91.201.215.165')
            ->forwardPort(80)
            ->forwardScheme('http')//->ssl('')
        ;
        $res = $Domain->send();
        self::assertArrayHasKey('id', $res);
    }


    public function testDomainGet()
    {
        $Domain = new Get($this->client());
        $res = $Domain->setId(1)->send();
        self::assertArrayHasKey('id', $res);
    }

    public function testDomainSearch()
    {
        $Domain = new Search($this->client());
        $res = $Domain->query('dasdasd.ru')->send();
        self::assertIsArray($res);
        self::assertArrayHasKey('id', $res[0]);
    }

    public function testDomainDelete()
    {
        $Domain = new Delete($this->client());
        $res = $Domain->setId(15)->send();
        self::assertTrue($res);
    }
}
