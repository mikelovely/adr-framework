<?php

namespace Adr\Tests\Integration;

class PostsTest extends Integration
{
    public function test_get_posts()
    {
        $pdo_statement_object = \Mockery::spy(\PDOStatement::class);

        $this->app->getContainer()['Database']->shouldReceive('prepare')
            ->once()
            ->andReturn($pdo_statement_object);

        $pdo_statement_object->shouldReceive('rowCount')
            ->once()
            ->andReturn(1);

        $record = new \stdClass;
        $record->id = 1;
        $record->title = 'Something';

        $pdo_statement_object->shouldReceive('fetchAll')
            ->once()
            ->andReturn([$record]);

        $request = $this->request('GET', '/posts');

        $this->app->getContainer()['request'] = $request;

        $response = $this->app->run(true);

        $result = json_decode($response->getBody(), true);

        $this->assertEquals($result, [['id' => 1, 'title' => 'Something']]);
    }

    public function test_create_will_pass()
    {
        $pdo_statement_object = \Mockery::spy(\PDOStatement::class);

        $this->app->getContainer()['Database']->shouldReceive('prepare')
            ->once()
            ->andReturn($pdo_statement_object);

        $request = $this->request('POST', '/posts', ['id' => 2, 'title' => 'Foo']);

        $this->app->getContainer()['request'] = $request;

        $response = $this->app->run(true);

        $result = json_decode($response->getBody(), true);

        $this->assertEquals($result, ['id' => 2, 'title' => 'Foo']);
    }

    public function test_create_will_fail()
    {
        $request = $this->request('POST', '/posts', ['id' => 2]);

        $this->app->getContainer()['request'] = $request;

        $response = $this->app->run(true);

        $result = json_decode($response->getBody(), true);

        $this->assertEquals($result, ['title' => ['Title is required']]);
    }
}
