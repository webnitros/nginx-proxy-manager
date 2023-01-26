<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.01.2023
 * Time: 11:34
 */

namespace Tests;

use NginxProxyManager\Meta\Domain;

class DomainTest extends TestCase
{

    /*public function testAddDomain()
   {

         $is = rand(1, 1122);

          $Domain = Domain::create($this->client());

          $domain = 'site' . $is . '.ru';
          $Domain
              ->addDomain($domain)
              ->forwardHost('127.0.0.1')
              ->forwardPort(80)
              ->forwardScheme('http')//->ssl('')
          ;

          $Domain->save();
          $Domain->set('forward_host', '827.0.0.1');
          $Domain->save();


        self::assertArrayHasKey('id', $res);
    }*/


    public function testDomainGet()
    {
        $Domain = Domain::object(11, $this->client());
        $params = $Domain->toArray();
        self::assertArrayHasKey('id', $params);
    }

    public function testDomainSearch()
    {
        $results = Domain::search('time', $this->client());
        self::assertIsArray($results);
        self::assertArrayHasKey('id', $results[0]);
    }

    public function testDomainDelete()
    {
        $Domain = Domain::object(11, $this->client());
        $res = $Domain->delete();
        self::assertTrue($res);
    }



}
