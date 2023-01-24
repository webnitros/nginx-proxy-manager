<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 23.01.2023
 * Time: 10:54
 */

namespace Tests;

use App\Token;
use Tests\TestCase;

class TokenTest extends TestCase
{
    /**
     * Создаем новый токен
     * @throws \Exception
     */
    public function testCreate()
    {
        $Token = (new Token())->create();

echo '<pre>';
print_r(22); die;


        self::assertArrayHasKey('token', $data);
        self::assertArrayHasKey('expires', $data);
    }
}
