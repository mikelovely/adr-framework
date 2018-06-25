<?php

namespace Adr\Tests;

class Definitions
{
    public static function appPath()
    {
        if (!getenv('DOCKER_FRESH_ENVIRONMENT')) {
            putenv('DOCKER_FRESH_ENVIRONMENT=development');
        }

        if (!defined("APP_PATH")) {
            define("APP_PATH", __DIR__ . '/../');
        }
    }
}
