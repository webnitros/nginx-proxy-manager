<?php
declare(strict_types=1);

namespace NginxProxyManager\Helpers;

use Symfony\Component\Dotenv\Dotenv;
use Throwable;

class Env
{
    public static function loadFile(string $file)
    {
        try {
            $dotenv = new Dotenv(true);
            $dotenv->loadEnv($file);
            return null;
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
