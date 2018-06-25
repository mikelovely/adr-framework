<?php

namespace Adr\Tests\Integration;

use Adr\AppFactory;
use Adr\Tests\Definitions;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Slim\Http\Body;
use Slim\Http\Environment;
use Slim\Http\Request;

class Integration extends MockeryTestCase
{
    /**
     * @var Object
     */
    protected $app;

    public function setUp()
    {
        if (!getenv('DOCKER_FRESH_ENVIRONMENT')) {
            putenv('DOCKER_FRESH_ENVIRONMENT=development');
        }

        Definitions::appPath();

        parent::setUp();

        $factory = new AppFactory;

        $this->app = $factory->make();

        $this->app->getContainer()['Database'] = Mockery::spy(\PDO::class);
    }

    protected function request($method, $uri, $payload = null)
    {
        $request = Request::createFromEnvironment(Environment::mock([
            'REQUEST_METHOD' => $method,
            'REQUEST_URI' => $uri,
        ]));

        if ($payload) {
            $request = $this->body($request, $payload);
        }

        return $request;
    }

    protected function body($request, $payload)
    {
        $body = new Body(fopen('php://temp', 'r+'));
        $body->write(http_build_query($payload, null, '&'));
        $body->rewind();

        $request = $request->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request = $request->withBody($body);

        return $request;
    }
}
