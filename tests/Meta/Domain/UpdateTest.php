<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 26.01.2023
 * Time: 11:28
 */

namespace Tests\Meta\Domain;

use App\Meta\Domains\Get;
use App\Meta\Domains\Update;
use Tests\TestCase;

class UpdateTest extends TestCase
{

    public function testSetId()
    {
        $client = $this->client();

        $Get = new Get($client);
        $Get->getObject(10);
        $Get->set('forward_host', '827.0.0.1');


        $Get->save();

        echo '<pre>';
        print_r($Get->toArray());
        die;
        $Get->set('forward_host', '227.0.0.3');
        $Get->save();

    }
}
